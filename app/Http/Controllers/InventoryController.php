<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\Inventory;
use App\Models\Program;
use App\Models\UserActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class InventoryController extends Controller
{
    /**
     * Derive a program acronym from its display name.
     * - If parentheses exist, prefer the text inside (e.g., "Bachelor ... (BSIT)" → BSIT)
     * - Else, take the first letter of each word and uppercase (e.g., "Bachelor of Science in IT" → BOSIIT → BOSIIT)
     */
    protected function getProgramAcronymFromName(?string $programName): string
    {
        $name = trim((string) $programName);
        if ($name === '') {
            return 'GEN';
        }

        if (preg_match('/\(([^)]+)\)/', $name, $m)) {
            return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $m[1]));
        }

        $words = preg_split('/\s+/', $name) ?: [];
        $initials = array_map(function ($w) {
            return $w !== '' ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $w), 0, 1)) : '';
        }, $words);
        $acronym = implode('', $initials);
        $acronym = $acronym !== '' ? $acronym : strtoupper(preg_replace('/\s+/', '', $name));

        return $acronym !== '' ? $acronym : 'GEN';
    }

    public function store(Request $request)
    {
        // Normalize title early for case-insensitive duplicate checking
        $request->merge(['title' => strtoupper((string) $request->input('title'))]);

        $validated = $request->validate([
            // Enforce uniqueness on inventories table (case-insensitive by storing uppercase)
            'title' => 'required|string|max:255|unique:inventory,title',
            'authors' => 'required|string',
            'adviser' => 'required|string|max:255',
            'abstract' => 'required|string',
            'program_id' => 'required|exists:programs,id',
            'academic_year' => 'required|integer',
            'document' => 'nullable|file|mimes:pdf|max:15360',
        ]);

        // Additional validation: adviser must belong to selected program
        $adviser = Adviser::where('name', $validated['adviser'])->where('program_id', $validated['program_id'])->first();
        if (! $adviser) {
            return back()
                ->withErrors(['adviser' => 'Selected adviser does not belong to the chosen program.'])
                ->withInput();
        }

        // Load the program so we can read its name
        $program = Program::findOrFail($validated['program_id']);
        $programCode = Program::getAcronymForName($program->name ?? '');
        $year = $validated['academic_year']; // e.g. 2023

        // Next sequential number for this program-year pair
        $latest = Inventory::where('inventory_number', 'like', "{$programCode}-{$year}-%")
            ->orderBy('inventory_number', 'desc')
            ->value('inventory_number');

        $nextSerial = 1;
        if ($latest) {
            preg_match("/-(\d+)$/", $latest, $m);
            $nextSerial = ((int) $m[1]) + 1;
        }

        $inventoryNumber = sprintf('%s-%d-%03d', $programCode, $year, $nextSerial);

        // Default values for file fields
        $filePath = null;
        $fileName = null;
        $fileSize = null;
        $fileMime = null;

        // Handle file upload only if present
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filePath = $file->store('inventory', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileMime = $file->getMimeType();
        }

        $inventory = Inventory::create([
            'title' => $validated['title'],
            'authors' => ucwords(strtolower($validated['authors'])),
            'adviser' => $validated['adviser'],
            'abstract' => $validated['abstract'],
            'program_id' => $validated['program_id'],
            'manuscript_path' => $filePath,
            'manuscript_filename' => $fileName,
            'manuscript_size' => $fileSize,
            'manuscript_mime' => $fileMime,
            'academic_year' => $validated['academic_year'],
            'inventory_number' => $inventoryNumber,
            'archived_by' => auth()->id(),
            'archived_at' => now(),
        ]);

        // Log inventory add
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_INVENTORY_ADDED, $inventory, $validated['program_id'], [
            'inventory' => [
                'id' => $inventory->id,
                'inventory_number' => $inventoryNumber,
            ],
        ]);

        return redirect()->back()->with('success', 'Inventory added successfully!');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:200',
        ]);

        $query = (string) $request->query('query', '');
        // Sanitize: trim, strip tags, remove control chars
        $query = trim($query);
        $query = strip_tags($query);
        $query = preg_replace('/[\x00-\x1F\x7F]/u', '', $query);
        $query = mb_substr($query, 0, 200);

        if ($query === '') {
            // Return an empty paginator to keep the view logic simple
            $results = Inventory::where('id', 0)->with('program')->paginate(10);

            return view('layouts.landing.index', compact('results', 'query'));
        }

        $like = "%{$query}%";

        $results = Inventory::with('program')
            ->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('authors', 'like', $like)
                    ->orWhere('abstract', 'like', $like)
                    ->orWhere('adviser', 'like', $like)
                    ->orWhereHas('program', function ($q) use ($like) {
                        $q->where('name', 'like', $like);
                    })
                    ->orWhere('academic_year', 'like', $like);
            })
            ->orderBy('academic_year', 'desc')
            ->paginate(10); // 10 results per page

        return view('layouts.landing.index', compact('results', 'query'));
    }

    public function filtersInv()
    {
        $years = Inventory::distinct()->pluck('academic_year');

        return response()->json(['years' => $years]);
    }

    public function getInventoryData(Request $request)
    {
        $query = Inventory::with(['program', 'submission.submitter', 'submission.reviewer', 'archivist']);

        if ($request->program) {
            $query->where('program_id', $request->program);
        }

        if ($request->year) {
            $query->where('academic_year', $request->year);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('authors', 'LIKE', "%{$search}%")
                    ->orWhere('adviser', 'LIKE', "%{$search}%")
                    ->orWhere('abstract', 'LIKE', "%{$search}%")
                    ->orWhere('inventory_number', 'LIKE', "%{$search}%")
                    ->orWhere('academic_year', 'LIKE', "%{$search}%");
            });
        }

        return $query->get()->map(
            fn ($inv) => [
                'id' => $inv->id,
                'title' => $inv->title,
                'authors' => $inv->authors,
                'adviser' => $inv->adviser,
                'abstract' => $inv->abstract,
                'program' => optional($inv->program)->name ?? '—',
                'academic_year' => $inv->academic_year,
                'manuscript_path' => $inv->manuscript_path,
                'manuscript_filename' => $inv->manuscript_filename,
                'manuscript_size' => $inv->manuscript_size,
                'manuscript_mime' => $inv->manuscript_mime,
                'inventory_number' => $inv->inventory_number,
                'archived_at' => optional($inv->archived_at)->toDateTimeString(),
                'archiver' => optional($inv->archivist)->name ?? '—',
                'submitted_by' => optional($inv->submission)->submitter->full_name ?? '—',
                'reviewed_by' => $inv->submission ? optional($inv->submission->reviewer)->full_name ?? '—' : optional($inv->archivist)->full_name ?? '—',
                'can_edit' => $inv->submission_id === null && auth()->user()?->hasPermission('edit-inventory'),
                'download_url' => route('inventory.download', $inv->id),
            ],
        );
    }

    public function exportInventoriesDocx()
    {
        $inventories = Inventory::with('program')->get();

        $templatePath = storage_path('app/templates/inventory_template.docx');
        if (! file_exists($templatePath)) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        $template = new TemplateProcessor($templatePath);
        $template->cloneRow('title', $inventories->count());
        foreach ($inventories as $index => $item) {
            $i = $index + 1;

            $template->setValue("title#$i", $item->title);
            $template->setValue("authors#$i", $item->authors);
            $template->setValue("adviser#$i", $item->adviser);
            $template->setValue("program#$i", $item->program->name ?? '—');
            $template->setValue("academic_year#$i", $item->academic_year);
        }

        $filename = 'Inventory_Report_'.now()->format('Ymd_His').'.docx';
        $exportPath = storage_path("app/exports/{$filename}");

        if (! Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        $template->saveAs($exportPath);

        // Log inventory export (docx)
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_INVENTORY_EXPORTED, 'inventories', null, [
            'format' => 'docx',
            'count' => $inventories->count(),
            'filename' => $filename,
        ]);

        return response()->download($exportPath)->deleteFileAfterSend(true);
    }

    public function exportInventoriesPdf()
    {
        $inventories = Inventory::with('program')->get();
        $year = date('Y');
        $timestamp = now()->format('Ymd_His');

        $logo = base64_encode(file_get_contents(public_path('assets/usep-logo.png')));
        $footerImage = base64_encode(file_get_contents(public_path('assets/full-footer.png')));

        $output =
            '
    <html>
    <head>
        <style>
            @page {
                size: A4 landscape;
                margin: 120px 40px 100px 40px;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
            }

            header {
                position: fixed;
                top: -100px;
                left: 0;
                right: 0;
                height: 100px;
                text-align: center;
                display: block;
            }

            .header-content {
                display: block;
            }

            header img {
                width: 100px;
            }

            .usep-name {
                font-family: "Old English Text MT", serif;
                font-size: 20px;
            }

            footer {
                position: fixed;
                bottom: -80px;
                left: 0;
                right: 0;
                height: 80px;
                text-align: center;
            }

            footer img {
                width: 100%;
                height: auto;
                border: none;
            }

            .first-page-header {
                display: block;
            }

            .spacer {
                height: 110px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
                page-break-inside: auto;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th, td {
                border: 1px solid #ccc;
                padding: 6px;
                font-size: 11px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <!-- Header only on first page -->
        <div class="first-page-header">
            <header>
                <div class="header-content">
                    <img src="data:image/png;base64,'.
            $logo.
            '" alt="USEP Logo"><br>
                    <div class="usep-name">University of Southeastern Philippines</div>
                    <i>Office of the Student Affairs and Services - Tagum-Mabini Campus</i><br>
                    <h2>Inventory Report - '.
            $year.
            '</h2>
                </div>
            </header>
            <div class="spacer"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Submission ID</th>
                    <th>Title</th>
                    <th>Authors</th>
                    <th>Adviser</th>
                    <th>Program</th>
                    <th>Year</th>
                    <th>Inventory #</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($inventories as $inv) {
            $output .=
                '
                <tr>
                    <td>'.
                $inv->submission_id.
                '</td>
                    <td>'.
                $inv->title.
                '</td>
                    <td>'.
                $inv->authors.
                '</td>
                    <td>'.
                $inv->adviser.
                '</td>
                    <td>'.
                ($inv->program->name ?? '—').
                '</td>
                    <td>'.
                $inv->academic_year.
                '</td>
                    <td>'.
                $inv->inventory_number.
                '</td>
                </tr>';
        }

        $output .=
            '
            </tbody>
        </table>

        <!-- Footer appears on ALL pages -->
        <footer>
            <img src="data:image/png;base64,'.
            $footerImage.
            '" alt="Full Footer">
        </footer>

    </body>
    </html>';

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($output);
        $dompdf->render();

        // Log inventory export (pdf)
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_INVENTORY_EXPORTED, 'inventories', null, [
            'format' => 'pdf',
            'count' => $inventories->count(),
            'filename' => "Inventory_Report_{$timestamp}.pdf",
        ]);

        return $dompdf->stream("Inventory_Report_{$timestamp}.pdf");
    }

    public function checkDuplicateTitle(Request $request)
    {
        $title = strtoupper((string) $request->query('title', ''));
        if ($title === '') {
            return response()->json(['exists' => false]);
        }
        $exists = Inventory::where('title', $title)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'adviser' => 'required|string|max:255',
            'abstract' => 'required|string',
            'program_id' => 'required|exists:programs,id',
            'academic_year' => 'required|integer',
            'manuscript' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $inventory = Inventory::findOrFail($id);
        $changedColumns = [];

        // Update inventory number if program/year changes
        if ($inventory->program_id != $validated['program_id'] || $inventory->academic_year != $validated['academic_year']) {
            $program = Program::findOrFail($validated['program_id']);
            $programCode = Program::getAcronymForName($program->name ?? '');
            $year = $validated['academic_year'];

            $latest = Inventory::where('inventory_number', 'like', "{$programCode}-{$year}-%")
                ->orderBy('inventory_number', 'desc')
                ->value('inventory_number');

            $nextSerial = 1;
            if ($latest) {
                preg_match("/-(\d+)$/", $latest, $m);
                $nextSerial = ((int) $m[1]) + 1;
            }

            $inventoryNumber = sprintf('%s-%d-%03d', $programCode, $year, $nextSerial);
            $inventory->inventory_number = $inventoryNumber;
            $changedColumns[] = 'inventory_number';
        }

        // Handle file upload
        if ($request->hasFile('manuscript')) {
            // Delete old file if it exists
            if ($inventory->manuscript_path && Storage::disk('public')->exists($inventory->manuscript_path)) {
                Storage::disk('public')->delete($inventory->manuscript_path);
            }

            // Store the file in "manuscripts" inside public disk
            $file = $request->file('manuscript');
            $filePath = $file->store('inventory', 'public');

            // Save details in DB
            $inventory->manuscript_path = $filePath; // e.g. manuscripts/filename.pdf
            $inventory->manuscript_filename = $file->getClientOriginalName();
            $inventory->manuscript_size = $file->getSize();
            $inventory->manuscript_mime = $file->getMimeType();
            array_push($changedColumns, 'manuscript_path', 'manuscript_filename', 'manuscript_size', 'manuscript_mime');
        }

        // Validate adviser belongs to selected program
        $adviser = Adviser::where('name', $validated['adviser'])->where('program_id', $validated['program_id'])->first();
        if (! $adviser) {
            return response()->json(
                [
                    'errors' => ['adviser' => ['Selected adviser does not belong to the chosen program.']],
                ],
                422,
            );
        }

        // Update other fields
        $inventory
            ->fill([
                'title' => strtoupper($validated['title']),
                'authors' => ucwords(strtolower($validated['authors'])),
                'adviser' => $validated['adviser'],
                'abstract' => $validated['abstract'],
                'program_id' => $validated['program_id'],
                'academic_year' => $validated['academic_year'],
            ])
            ->save();

        // Detect changed scalar columns (without including actual values)
        $fieldsToCheck = ['title', 'authors', 'adviser', 'abstract', 'program_id', 'academic_year'];
        foreach ($fieldsToCheck as $field) {
            if ($inventory->wasChanged($field)) {
                $changedColumns[] = $field;
            }
        }

        $changedColumns = array_values(array_unique($changedColumns));
        if (count($changedColumns) > 0) {
            UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_THESIS_UPDATED, $inventory, $validated['program_id'], ['changed_columns' => $changedColumns]);
        }

        return response()->json(['success' => 'Inventory updated successfully!']);
    }

    public function download($id)
    {
        $inventory = Inventory::findOrFail($id);

        $filePath = storage_path('app/public/'.$inventory->manuscript_path);

        if (! file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download(
            $filePath,
            $inventory->manuscript_filename, // Preserve original filename
        );
    }

    public function viewFileInv($id)
    {
        $inventory = Inventory::findOrFail($id);

        if (! auth()->check()) {
            \Log::error('Unauthorized access attempt to view file by user: '.auth()->id());
            abort(403, 'Unauthorized');
        }

        \Log::info('Attempting to view file for submission ID: '.$id);

        $fileName = ltrim($inventory->manuscript_path, '/');
        $path = storage_path("app/public/{$fileName}");

        if (! file_exists($path)) {
            $submissions = Inventory::findOrFail($id);
            $fileName = ltrim($submissions->manuscript_path, '/');
            $path = storage_path("app/public/{$fileName}");
        }

        \Log::info("File found at: {$path}");

        return response()->file($path);
    }
}
