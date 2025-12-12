<?php

namespace App\Http\Controllers;

use App\Models\FacultyFormSubmission;
use App\Models\Inventory;
use App\Models\Submission;
use App\Models\UserActivityLog;
use App\Notifications\FormSubmissionApprovedNotification;
use App\Notifications\FormSubmissionForwardedNotification;
use App\Notifications\FormSubmissionRejectedNotification;
use App\Notifications\SubmissionApprovedNotification;
use App\Notifications\SubmissionRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    /**
     * Derive a program acronym from its display name.
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

    public function pending()
    {
        $submissions = Submission::where('submitted_by', Auth::id())->where('status', 'pending')->orderBy('submitted_at', 'desc')->get();

        return response()->json($submissions);
    }

    public function pendingForms()
    {
        $forms = FacultyFormSubmission::where('status', FacultyFormSubmission::STATUS_PENDING)->with('submitter')->orderBy('submitted_at', 'desc')->get()->map(
            fn($f) => [
                'id' => $f->id,
                'form_type' => $f->form_type,
                'note' => $f->note,
                'document_filename' => $f->document_filename,
                'document_size' => $f->document_size,
                'document_mime' => $f->document_mime,
                'submitted_at' => $f->submitted_at,
                'submitted_by' => $f->submitter ? $f->submitter->full_name : 'Unknown',
                'status' => $f->status,
                'forwarded_to' => $f->forwarded_to,
            ],
        );

        return response()->json($forms);
    }

    public function submitThesis(Request $request)
    {
        // Debug: Log file upload information
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            \Log::info('File upload attempt', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'size_mb' => round($file->getSize() / 1024 / 1024, 2),
                'mime_type' => $file->getMimeType(),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
            ]);
        }

        // Pre-sanitize inputs and normalize title for case-insensitive checks
        $sanitizedTitle = $this->sanitizeTitle((string) $request->input('title'));
        $sanitizedAdviser = $this->sanitizeAdviser((string) $request->input('adviser'));
        $sanitizedAuthors = $this->sanitizeAuthors((string) $request->input('authors'));
        $sanitizedAbstract = $this->sanitizeAbstract((string) $request->input('abstract'));
        $request->merge([
            'title' => $sanitizedTitle,
            'adviser' => $sanitizedAdviser,
            'authors' => $sanitizedAuthors,
            'abstract' => $sanitizedAbstract,
        ]);
        $normalizedTitle = strtoupper($sanitizedTitle);

        // Validate the request data
        $validator = Validator::make(
            $request->all(),
            [
                // Enforce uniqueness on submissions table (case-insensitive via normalized uppercase)
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:submissions,title',
                    // Allow: letters, numbers, spaces and . , : ; ( ) & ' - /
                    "regex:~^[A-Za-z0-9 .,:;()&'/-]+$~",
                ],
                'adviser' => [
                    'required',
                    'string',
                    'max:255',
                    // Allow: letters, spaces, period, hyphen, apostrophe
                    "regex:~^[A-Za-z .\\-']+$~",
                ],
                'authors' => [
                    'required',
                    'string',
                    // Allow: letters, spaces, commas, period, hyphen, apostrophe
                    "regex:~^[A-Za-z ,\\.\\-']+$~",
                    function ($attribute, $value, $fail) {
                        $user = Auth::user();
                        if (!$this->validateAuthorInclusion($user, $value)) {
                            $fail('You must include your name in the authors list.');
                        }
                    },
                ],
                // Abstract: plain text only, min 100 characters and max 500 words
                'abstract' => [
                    'required',
                    'string',
                    'min:100',
                    function ($attribute, $value, $fail) {
                        $trimmed = trim((string) $value);
                        if ($trimmed === '') {
                            return;
                        }
                        $words = preg_split('/\s+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
                        $wordCount = is_array($words) ? count($words) : 0;
                        if ($wordCount > 500) {
                            $fail('Abstract must not exceed 500 words.');
                        }
                    },
                ],
                'document' => 'required|file|mimes:pdf|max:15360', // 15MB max
            ],
            [
                'title.unique' => 'Title already exists.',
                'title.regex' => 'Title contains invalid characters.',
                'adviser.regex' => 'Adviser contains invalid characters.',
                'authors.regex' => 'Authors contains invalid characters.',
            ],
        );

        if ($validator->fails()) {
            // Debug: Log validation errors
            \Log::error('File upload validation failed', [
                'errors' => $validator->errors()->toArray(),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
            ]);

            return response()->json(
                [
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ],
                422,
            );
        }

        try {
            $user = Auth::user();
            $program = $user->program;

            // Handle file upload
            $file = $request->file('document');

            // Debug: Log file storage attempt
            \Log::info('Attempting to store file', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'temp_path' => $file->getPathname(),
            ]);

            $filePath = $file->store('submissions', 'public');

            // Create the submission
            $submission = Submission::create([
                'title' => $normalizedTitle,
                'adviser' => ucwords(strtolower($sanitizedAdviser)),
                'authors' => ucwords(strtolower($sanitizedAuthors)),
                'abstract' => $sanitizedAbstract,
                'manuscript_path' => $filePath,
                'manuscript_filename' => $file->getClientOriginalName(),
                'manuscript_size' => $file->getSize(),
                'manuscript_mime' => $file->getMimeType(),
                'program_id' => $program ? $program->id : null,
                'submitted_by' => $user->id,
                'submitted_at' => now(),
                'status' => 'pending',
            ]);

            // Log thesis submission with metadata (non-fatal)
            try {
                UserActivityLog::log($user, UserActivityLog::ACTION_THESIS_SUBMITTED, $submission, $program ? $program->id : null, [
                    'submission' => [
                        'id' => $submission->id,
                        'title_hash' => hash('sha256', $submission->title),
                    ],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('UserActivityLog failed after thesis submission', [
                    'error' => $e->getMessage(),
                    'user_id' => $user?->id,
                    'submission_id' => $submission->id ?? null,
                ]);
            }

            return response()->json(
                [
                    'message' => 'Submission created successfully',
                    'data' => $submission,
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Failed to create submission',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function submitForm(Request $request)
    {
        // Validate form submission data
        $validator = Validator::make($request->all(), [
            'form_type' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:15360', // 15MB max
            'note' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        try {
            $user = Auth::user();

            // Handle file upload
            $file = $request->file('document');
            $filePath = $file->store('form-submissions', 'public');

            // Create the form submission
            $formSubmission = FacultyFormSubmission::create([
                'form_type' => $request->form_type,
                'note' => $request->note,
                'document_path' => $filePath,
                'document_filename' => $file->getClientOriginalName(),
                'document_size' => $file->getSize(),
                'document_mime' => $file->getMimeType(),
                'submitted_by' => $user->id,
                'submitted_at' => now(),
                'status' => FacultyFormSubmission::STATUS_PENDING,
            ]);

            // Log form submission with metadata (non-fatal)
            try {
                UserActivityLog::log($user, UserActivityLog::ACTION_FORM_SUBMITTED, $formSubmission, null, [
                    'form_submission' => [
                        'id' => $formSubmission->id,
                        'form_type' => $request->form_type,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('UserActivityLog failed after form submission', [
                    'error' => $e->getMessage(),
                    'user_id' => $user?->id,
                    'form_submission_id' => $formSubmission->id ?? null,
                ]);
            }

            return response()->json(
                [
                    'message' => 'Form submission created successfully',
                    'data' => $formSubmission,
                ],
                201,
            );
        } catch (\Exception $e) {
            \Log::error('Form submission failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'form_type' => $request->form_type,
            ]);

            return response()->json(
                [
                    'message' => 'Failed to create form submission',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Stream a faculty form submission document for admin viewing
     */
    public function viewFacultyFormAdmin(int $id)
    {
        $form = FacultyFormSubmission::findOrFail($id);

        $relativePath = ltrim((string) $form->document_path, '/');
        $absolutePath = \Storage::disk('public')->path($relativePath);

        if (!file_exists($absolutePath)) {
            abort(404, 'File not found');
        }

        $mime = $form->document_mime ?: (mime_content_type($absolutePath) ?: 'application/pdf');
        $filename = $form->document_filename ?: basename($absolutePath);

        return response()->file($absolutePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Stream a faculty form submission document for the submitting user (preview)
     */
    public function viewFacultyForm(int $id)
    {
        $userId = Auth::id();
        $form = FacultyFormSubmission::where('id', $id)->where('submitted_by', $userId)->firstOrFail();

        $relativePath = ltrim((string) $form->document_path, '/');
        $absolutePath = \Storage::disk('public')->path($relativePath);

        if (!file_exists($absolutePath)) {
            abort(404, 'File not found');
        }

        $mime = $form->document_mime ?: (mime_content_type($absolutePath) ?: 'application/pdf');
        $filename = $form->document_filename ?: basename($absolutePath);

        return response()->file($absolutePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Delete a pending faculty form submission owned by the current user
     */
    public function deleteForm(Request $request, int $id)
    {
        $userId = Auth::id();

        $form = FacultyFormSubmission::where('id', $id)->where('submitted_by', $userId)->firstOrFail();

        if ($form->status !== FacultyFormSubmission::STATUS_PENDING) {
            return response()->json(
                [
                    'message' => 'Only pending submissions can be deleted.',
                ],
                422,
            );
        }

        try {
            if (!empty($form->document_path) && \Storage::disk('public')->exists($form->document_path)) {
                \Storage::disk('public')->delete($form->document_path);
            }

            $form->delete(); // soft delete

            try {
                UserActivityLog::log(Auth::user(), UserActivityLog::ACTION_FORM_DELETED ?? 'form_deleted', $form, null, [
                    'form_submission' => [
                        'id' => $form->id,
                        'form_type' => $form->form_type,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('UserActivityLog failed after form deletion', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId,
                    'form_submission_id' => $form->id ?? null,
                ]);
            }

            return response()->json(['message' => 'Submission deleted']);
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'Failed to delete submission',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Validate that the user is included in the authors list
     */
    protected function validateAuthorInclusion($user, $authorsString): bool
    {
        $userFirst = strtolower(trim(Crypt::decrypt($user->first_name)));
        $userLast = strtolower(trim(Crypt::decrypt($user->last_name)));

        // Normalize helpers
        $normalize = function (string $value): string {
            $v = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
            $v = strtolower($v ?? $value);
            // Replace punctuation with spaces and collapse
            $v = preg_replace('/[\.,]/', ' ', $v);
            $v = preg_replace('/\s+/', ' ', trim($v));

            return $v;
        };

        $first = $normalize($userFirst);
        $last = $normalize($userLast);
        $firstInitial = $first !== '' ? substr($first, 0, 1) : '';

        // Split authors by comma, but keep names intact
        $authorEntries = array_filter(array_map('trim', explode(',', $authorsString)));

        foreach ($authorEntries as $entry) {
            $name = $normalize($entry);

            // Support "Last First" or "Last, First" and with middle names/initials
            $hasLast = strpos(' ' . $name . ' ', ' ' . $last . ' ') !== false || preg_match('/\b' . preg_quote($last, '/') . '\b/u', $name);

            if (!$hasLast) {
                continue;
            }

            // Must also have first name (full) OR initial anywhere in the same entry
            $hasFirstFull = preg_match('/\b' . preg_quote($first, '/') . '\b/u', $name) === 1;
            $hasFirstInitial = $firstInitial !== '' && preg_match('/\b' . preg_quote($firstInitial, '/') . '\.?\b/u', $name) === 1;

            if ($hasFirstFull || $hasFirstInitial) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize name while removing middle initials for comparison
     */
    protected function normalizeWithoutInitials(string $name): string
    {
        // Remove 1-3 character middle initials with optional dots
        $withoutInitials = preg_replace('/\s[a-z]{1,3}\.?\s*/i', ' ', $name);

        // Standardize for comparison: lowercase and single spaces
        return strtolower(trim(preg_replace('/\s+/', ' ', $withoutInitials)));
    }

    /**
     * Paginated thesis submission history for the current user
     */
    public function userThesisHistory(Request $request)
    {
        $page = (int) $request->query('page', 1);

        $query = Submission::where('submitted_by', Auth::id())
            ->whereIn('status', [Submission::STATUS_ACCEPTED, Submission::STATUS_REJECTED, Submission::STATUS_PENDING])
            ->orderBy('submitted_at', 'desc');

        $history = $query->paginate(5, ['*'], 'page', $page)->through(
            fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'authors' => $s->authors,
                'abstract' => $s->abstract,
                'remarks' => $s->remarks,
                'submitted_at' => $s->submitted_at,
                'reviewed_at' => $s->reviewed_at,
                'status' => $s->status,
                'has_been_resubmitted' => $s->hasBeenResubmitted(),
            ],
        );

        return response()->json($history);
    }

    public function show_submission_history(Request $request)
    {
        $query = FacultyFormSubmission::where('submitted_by', Auth::id())->orderBy('submitted_at', 'desc');

        // Optional search filter (mirrors admin inventory behavior)
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('form_type', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhere('document_filename', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('submitted_at', 'like', "%{$search}%")
                    ->orWhere('reviewed_at', 'like', "%{$search}%");
            });
        }

        // Optional exact status filter
        if ($request->filled('status') && $request->query('status') !== 'all') {
            $status = $request->query('status');
            $query->where('status', $status);
        }

        // Optional exact form type filter
        if ($request->filled('form_type') && $request->query('form_type') !== 'all') {
            $formType = $request->query('form_type');
            $query->where('form_type', $formType);
        }

        // If a specific submission should be highlighted, compute its page
        $page = (int) $request->query('page', 1);
        if ($request->filled('highlight_id')) {
            $highlightId = (int) $request->query('highlight_id');
            $target = FacultyFormSubmission::where('submitted_by', Auth::id())->where('id', $highlightId)->first();
            if ($target) {
                // Count entries that come before the target in the same ordering
                $beforeCount = FacultyFormSubmission::where('submitted_by', Auth::id())
                    ->where(function ($q) use ($target) {
                        $q->where('submitted_at', '>', $target->submitted_at)->orWhere(function ($q2) use ($target) {
                            $q2->where('submitted_at', $target->submitted_at)->where('id', '>', $target->id);
                        });
                    })
                    ->count();
                $page = max(1, (int) floor($beforeCount / 3) + 1);
            }
        }

        $forms = $query->paginate(3, ['*'], 'page', $page)->through(
            fn($f) => [
                'id' => $f->id,
                'form_type' => $f->form_type,
                'note' => $f->note,
                'document_filename' => $f->document_filename,
                'submitted_at' => $f->submitted_at,
                'reviewed_at' => $f->reviewed_at,
                'status' => $f->status,
                'review_remarks' => $f->review_remarks,
                'forwarded_to' => $f->forwarded_to,
                'has_been_resubmitted' => $f->hasBeenResubmitted(),
            ],
        );

        return response()->json($forms);
    }

    /**
     * Admin: Paginated forms submission history across all faculty submissions
     */
    public function formsHistoryAdmin(Request $request)
    {
        // Base query across all submissions for admins
        $query = FacultyFormSubmission::with(['submitter', 'reviewer'])->orderBy('submitted_at', 'desc');

        // Optional search filter
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('form_type', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhere('document_filename', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('submitted_at', 'like', "%{$search}%")
                    ->orWhere('reviewed_at', 'like', "%{$search}%");
            });
        }

        // Optional exact status filter (accept arbitrary value like "forwarded" if present in data)
        if ($request->filled('status')) {
            $status = $request->query('status');
            if ($status !== '') {
                $query->where('status', $status);
            }
        } else {
            // Default: exclude pending from admin history when no explicit status provided
            $query->whereIn('status', ['accepted', 'approved', 'rejected', 'forwarded']);
        }

        // Optional exact form type filter
        if ($request->filled('form_type')) {
            $formType = $request->query('form_type');
            if ($formType !== '') {
                $query->where('form_type', $formType);
            }
        }

        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 10);

        $forms = $query->paginate($perPage, ['*'], 'page', $page)->through(
            fn($f) => [
                'id' => $f->id,
                'form_type' => $f->form_type,
                'note' => $f->note,
                'document_filename' => $f->document_filename,
                'submitted_by' => optional($f->submitter)->full_name ?? '—',
                'submitted_at' => $f->submitted_at,
                'status' => $f->status,
                'reviewed_by' => optional($f->reviewer)->full_name ?? '—',
                'review_remarks' => (string) ($f->review_remarks ?? ''),
                'reviewed_at' => $f->reviewed_at,
                'forwarded_to' => $f->forwarded_to,
            ],
        );

        return response()->json($forms);
    }

    // submission filters
    public function filtersSubs()
    {
        $years = DB::table('submissions')->selectRaw('DISTINCT strftime("%Y", submitted_at) as year')->orderBy('year')->pluck('year');

        return response()->json(['years' => $years]);
    }

    // history filters
    public function filtersHistory()
    {
        $years = DB::table('submissions')->selectRaw('DISTINCT strftime("%Y", submitted_at) as year')->orderBy('year')->pluck('year');

        return response()->json(['years' => $years]);
    }

    // data go go to table subs
    public function getSubmissionData(Request $request)
    {
        $query = Submission::with(['program', 'submitter'])->orderBy('submitted_at', 'desc');

        // optional filters
        if ($request->filled('program')) {
            $query->where('program_id', $request->query('program'));
        }

        if ($request->filled('year')) {
            $query->whereYear('submitted_at', $request->query('year'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('authors', 'like', "%{$search}%")
                    ->orWhere('abstract', 'like', "%{$search}%")
                    ->orWhere('manuscript_filename', 'like', "%{$search}%")
                    ->orWhere('adviser', 'like', "%{$search}%")
                    ->orWhere('submitted_by', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('submitted_at', 'like', "%{$search}%");
            });
        }

        $q = $query->get()->map(
            fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'authors' => $s->authors,
                'abstract' => $s->abstract,
                'adviser' => $s->adviser,
                'manuscript_path' => $s->manuscript_path,
                'manuscript_filename' => $s->manuscript_filename,
                'manuscript_size' => $s->manuscript_size,
                'manuscript_mime' => $s->manuscript_mime,
                'program' => $s->program->name ?? '',
                'submitted_by' => $s->submitter->full_name ?? '—',
                'submitted_at' => $s->submitted_at,
                'status' => $s->status_label,
                'remarks' => $s->remarks ?? '—',
            ],
        );

        return response()->json($q);
    }

    public function history(Request $request)
    {
        $query = Submission::with(['program', 'submitter', 'reviewer'])
            ->whereIn('status', [Submission::STATUS_ACCEPTED, Submission::STATUS_REJECTED])
            ->orderBy('reviewed_at', 'desc');

        // optional filters
        if ($request->filled('program')) {
            $query->where('program_id', $request->query('program'));
        }

        if ($request->filled('year')) {
            $query->whereYear('submitted_at', $request->query('year'));
        }

        $history = $query->get()->map(
            fn($s) => [
                'title' => $s->title,
                'authors' => $s->authors,
                'abstract' => $s->abstract,
                'adviser' => $s->adviser,
                'program' => $s->program->name ?? '',
                'submitted_by' => $s->submitter->full_name ?? '—',
                'submitted_at' => $s->submitted_at,
                'status' => $s->status_label,
                'reviewed_by' => optional($s->reviewer)->full_name ?? '—',
                'remarks' => $s->remarks ?? '—',
                'reviewed_at' => $s->reviewed_at,
            ],
        );

        return response()->json($history);
    }

    public function download($id)
    {
        $submissions = Submission::findOrFail($id);

        $filePath = storage_path('app/public/' . $submissions->manuscript_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download(
            $filePath,
            $submissions->manuscript_filename, // Preserve original filename
        );
    }

    public function downloadManuscript($id)
    {
        $submission = Submission::findOrFail($id);

        $relativePath = ltrim($submission->manuscript_path, '/');
        $absolutePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($absolutePath)) {
            abort(404, 'File not found');
        }

        return response()->download($absolutePath, $submission->manuscript_filename);
    }

    // submission actions
    public function approve(Request $request, $id)
    {
        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $submission = Submission::findOrFail($id);
        $previousStatus = $submission->status;

        $submission->update([
            'status' => 'accepted',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks' => $request->remarks,
        ]);

        // Log approval action
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_THESIS_APPROVED, $submission, $submission->program_id, [
            'submission' => ['id' => $submission->id],
            'remarks' => $request->remarks,
            'previous_status' => $previousStatus,
            'new_status' => $submission->status,
        ]);

        $programCode = \App\Models\Program::getAcronymForName($submission->program->name ?? '');
        $year = (int) \Carbon\Carbon::parse($submission->submitted_at)->year;

        $latest = Inventory::where('inventory_number', 'like', "{$programCode}-{$year}-%")
            ->orderBy('inventory_number', 'desc')
            ->value('inventory_number');

        $nextSerial = 1;
        if ($latest) {
            // extract the last used serial and increment
            preg_match("/-(\d+)$/", $latest, $m);
            $nextSerial = ((int) $m[1]) + 1;
        }

        $inventoryNumber = sprintf('%s-%d-%03d', $programCode, $year, $nextSerial);

        $inventory = Inventory::create([
            'submission_id' => $submission->id,
            'title' => $submission->title,
            'authors' => $submission->authors,
            'adviser' => $submission->adviser,
            'abstract' => $submission->abstract,
            'program_id' => $submission->program_id,
            'manuscript_path' => $submission->manuscript_path,
            'manuscript_filename' => $submission->manuscript_filename,
            'manuscript_size' => $submission->manuscript_size,
            'manuscript_mime' => $submission->manuscript_mime,
            'academic_year' => (int) \Carbon\Carbon::parse($submission->submitted_at)->year,
            'inventory_number' => $inventoryNumber,
            'archived_by' => auth()->id(),
            'archived_at' => now(),
        ]);

        // Log archive action (inventory created)
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_THESIS_ARCHIVED, $inventory, $submission->program_id, [
            'inventory' => [
                'id' => $inventory->id,
                'inventory_number' => $inventoryNumber,
            ],
            'submission_id' => $submission->id,
        ]);

        logger('Email to be sent to: ' . $submission->submitter->email);
        $submission->submitter->notify(new SubmissionApprovedNotification($submission));

        return response()->json(['message' => 'Submission approved']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $submission = Submission::findOrFail($id);
        $previousStatus = $submission->status;
        $submission->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks' => $request->remarks,
        ]);

        // Log decline action
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_THESIS_DECLINED, $submission, $submission->program_id, [
            'submission' => ['id' => $submission->id],
            'remarks' => $request->remarks,
            'previous_status' => $previousStatus,
            'new_status' => $submission->status,
        ]);

        $submission->submitter->notify(new SubmissionRejectedNotification($submission));

        return response()->json(['message' => 'Submission rejected']);
    }

    public function viewFile($id)
    {
        $submission = Submission::findOrFail($id);

        if (!auth()->check()) {
            \Log::error('Unauthorized access attempt to view file by user: ' . auth()->id());
            abort(403, 'Unauthorized');
        }

        \Log::info('Attempting to view file for submission ID: ' . $id);

        $fileName = ltrim($submission->manuscript_path, '/');
        $path = storage_path("app/public/{$fileName}");

        if (!file_exists($path)) {
            \Log::error("File not found at: {$path}");
            abort(404, "File not found at: {$path}");
        }

        \Log::info("File found at: {$path}");

        return response()->file($path);
    }

    /**
     * Check if a title already exists in submissions
     */
    public function checkDuplicateTitle(Request $request)
    {
        // Sanitize and validate incoming title
        $request->merge(['title' => $this->sanitizeTitle((string) $request->input('title'))]);
        $request->validate([
            'title' => ['required', 'string', 'max:255', "regex:~^[A-Za-z0-9 .,:;()&'/-]+$~"],
        ]);

        $normalizedTitle = strtolower(trim(preg_replace('/\s+/', ' ', $request->title)));

        // Check if title exists in submissions (case-insensitive)
        // Using PHP-based normalization for SQLite compatibility
        $existingSubmissions = Submission::get(['id', 'title', 'authors', 'submitted_at', 'status']);
        $existingSubmission = $existingSubmissions->first(function ($submission) use ($normalizedTitle) {
            $existingNormalizedTitle = strtolower(trim(preg_replace('/\s+/', ' ', $submission->title)));
            return $existingNormalizedTitle === $normalizedTitle;
        });

        if ($existingSubmission) {
            return response()->json([
                'isDuplicate' => true,
                'message' => 'A submission with this title already exists.',
                'existingSubmission' => [
                    'id' => $existingSubmission->id,
                    'title' => $existingSubmission->title,
                    'authors' => $existingSubmission->authors,
                    'submitted_at' => $existingSubmission->submitted_at,
                    'status' => $existingSubmission->status,
                ],
            ]);
        }

        return response()->json([
            'isDuplicate' => false,
            'message' => 'Title is available.',
        ]);
    }

    /**
     * Sanitize Title: allow letters, numbers, spaces and . , : ; ( ) & ' - /
     */
    protected function sanitizeTitle(string $value): string
    {
        $v = preg_replace("~[^A-Za-z0-9 .,:;()&'/-]~", '', $value);
        $v = preg_replace('/\s+/', ' ', trim($v));

        return $v ?? '';
    }

    /**
     * Sanitize Adviser: allow letters, spaces, period, hyphen, apostrophe
     */
    protected function sanitizeAdviser(string $value): string
    {
        $v = preg_replace("~[^A-Za-z .\-']+~", '', $value);
        $v = preg_replace('/\s+/', ' ', trim($v));

        return $v ?? '';
    }

    /**
     * Approve a faculty form submission
     */
    public function approveForm(Request $request, $id)
    {
        \Log::info('Form approval request received', ['id' => $id, 'remarks' => $request->remarks]);

        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $formSubmission = FacultyFormSubmission::findOrFail($id);
        $previousStatus = $formSubmission->status;

        $formSubmission->update([
            'status' => 'accepted',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_remarks' => $request->remarks,
        ]);

        // Log approval action
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_FORM_APPROVED, $formSubmission, null, [
            'form_submission' => ['id' => $formSubmission->id],
            'remarks' => $request->remarks,
            'previous_status' => $previousStatus,
            'new_status' => $formSubmission->status,
        ]);

        // Send email notification
        logger('Email to be sent to: ' . $formSubmission->submitter->email);
        $formSubmission->submitter->notify(new FormSubmissionApprovedNotification($formSubmission));

        return response()->json(['message' => 'Form submission approved']);
    }

    /**
     * Reject a faculty form submission
     */
    public function rejectForm(Request $request, $id)
    {
        \Log::info('Form rejection request received', ['id' => $id, 'remarks' => $request->remarks]);

        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $formSubmission = FacultyFormSubmission::findOrFail($id);
        $previousStatus = $formSubmission->status;

        $formSubmission->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_remarks' => $request->remarks,
        ]);

        // Log rejection action
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_FORM_REJECTED, $formSubmission, null, [
            'form_submission' => ['id' => $formSubmission->id],
            'remarks' => $request->remarks,
            'previous_status' => $previousStatus,
            'new_status' => $formSubmission->status,
        ]);

        // Send email notification
        logger('Email to be sent to: ' . $formSubmission->submitter->email);
        $formSubmission->submitter->notify(new FormSubmissionRejectedNotification($formSubmission));

        return response()->json(['message' => 'Form submission rejected']);
    }

    /**
     * Download a faculty form submission document
     */
    public function downloadForm($id)
    {
        $formSubmission = FacultyFormSubmission::findOrFail($id);

        $relativePath = ltrim($formSubmission->document_path, '/');
        $absolutePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($absolutePath)) {
            abort(404, 'File not found');
        }

        return response()->download($absolutePath, $formSubmission->document_filename);
    }

    /**
     * Sanitize Authors: allow letters, spaces, commas, period, hyphen, apostrophe; normalize commas
     */
    protected function sanitizeAuthors(string $value): string
    {
        $v = preg_replace("~[^A-Za-z ,\.\-']+~", '', $value);
        // Normalize comma spacing
        $v = preg_replace('/\s*,\s*/', ', ', $v);
        // Collapse spaces
        $v = preg_replace('/\s+/', ' ', $v);
        // Trim stray commas/spaces
        $v = preg_replace('/^,\s*/', '', $v);
        $v = preg_replace('/\s*,\s*$/', '', $v);

        return trim($v ?? '');
    }

    /**
     * Sanitize Abstract: remove HTML brackets and control chars, normalize whitespace while preserving newlines
     */
    protected function sanitizeAbstract(string $value): string
    {
        // Strip angle brackets to prevent HTML and remove control chars except tab/newline/carriage return
        $v = preg_replace('/[<>]/', '', $value);
        $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $v);
        // Keep spaces as-is; only collapse excessive blank lines
        $v = preg_replace("/\n{3,}/", "\n\n", $v);

        return trim($v);
    }

    /**
     * Forward a faculty form submission via email
     */
    public function forwardForm(Request $request, $id)
    {
        \Log::info('Form forward request received', ['id' => $id, 'to_email' => $request->to_email]);

        $request->validate([
            'to_email' => 'required|email|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        $formSubmission = FacultyFormSubmission::findOrFail($id);
        $previousStatus = $formSubmission->status;

        // Update form status to forwarded with separate forwarded_to field
        // Preserve original review_remarks and don't store forwarding message
        $formSubmission->update([
            'status' => 'forwarded',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'forwarded_to' => $request->to_email,
            // Keep original review_remarks unchanged
        ]);

        // Log forward action
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_FORM_FORWARDED, $formSubmission, null, [
            'form_submission' => ['id' => $formSubmission->id],
            'to_email' => $request->to_email,
            'message' => $request->message,
            'previous_status' => $previousStatus,
            'new_status' => $formSubmission->status,
        ]);

        // Send email notifications (following same pattern as approve/reject)
        try {
            // 1. Notify the faculty member who submitted the form
            \Log::info('Sending email to faculty', [
                'faculty_email' => $formSubmission->submitter->email,
                'faculty_name' => $formSubmission->submitter->full_name,
            ]);
            $formSubmission->submitter->notify(new FormSubmissionForwardedNotification($formSubmission, $request->to_email, $request->message ?? ''));
            \Log::info('Faculty email sent successfully');

            // 2. Notify the recipient with attached file
            \Log::info('Sending email to recipient', [
                'recipient_email' => $request->to_email,
                'form_id' => $formSubmission->id,
                'has_document' => $formSubmission->hasDocument(),
                'document_path' => $formSubmission->document_path,
            ]);

            // Use Mailable class for recipient with file attachments
            \Log::info('Sending Mailable to recipient', [
                'recipient_email' => $request->to_email,
                'form_id' => $formSubmission->id,
                'has_document' => $formSubmission->hasDocument(),
                'document_path' => $formSubmission->document_path,
            ]);

            \Mail::to($request->to_email)->send(new \App\Mail\FormForwardedMail($formSubmission, $request->to_email, $request->message ?? ''));
            \Log::info('Recipient email sent successfully');

            \Log::info('Form forward emails sent successfully', [
                'form_id' => $formSubmission->id,
                'faculty_email' => $formSubmission->submitter->email,
                'recipient_email' => $request->to_email,
            ]);
        } catch (\Exception $e) {
            \Log::error('Form forward email failed', [
                'form_id' => $formSubmission->id,
                'faculty_email' => $formSubmission->submitter->email,
                'recipient_email' => $request->to_email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't fail the request if email fails, just log it
        }

        return response()->json(['message' => 'Form submission forwarded successfully']);
    }

    /**
     * Get submission data for resubmission (excluding attachment)
     */
    public function getResubmitData($id)
    {
        $submission = Submission::where('id', $id)->where('submitted_by', Auth::id())->where('status', 'rejected')->first();

        if (!$submission) {
            return response()->json(['message' => 'Submission not found or not eligible for resubmission'], 404);
        }

        return response()->json([
            'id' => $submission->id,
            'title' => $submission->title,
            'authors' => $submission->authors,
            'adviser' => $submission->adviser,
            'abstract' => $submission->abstract,
            'remarks' => $submission->remarks,
        ]);
    }

    /**
     * Get form submission data for resubmission (excluding attachment)
     */
    public function getFormResubmitData($id)
    {
        $formSubmission = FacultyFormSubmission::where('id', $id)->where('submitted_by', Auth::id())->where('status', 'rejected')->first();

        if (!$formSubmission) {
            return response()->json(['message' => 'Form submission not found or not eligible for resubmission'], 404);
        }

        return response()->json([
            'id' => $formSubmission->id,
            'form_type' => $formSubmission->form_type,
            'note' => $formSubmission->note,
            'review_remarks' => $formSubmission->review_remarks,
        ]);
    }

    /**
     * Resubmit a thesis submission
     */
    public function resubmitThesis(Request $request, $id)
    {
        $submission = Submission::where('id', $id)->where('submitted_by', Auth::id())->where('status', 'rejected')->first();

        if (!$submission) {
            return response()->json(['message' => 'Submission not found or not eligible for resubmission'], 404);
        }

        // Pre-sanitize inputs and normalize title for case-insensitive checks
        $sanitizedTitle = $this->sanitizeTitle((string) $request->input('title'));
        $sanitizedAdviser = $this->sanitizeAdviser((string) $request->input('adviser'));
        $sanitizedAuthors = $this->sanitizeAuthors((string) $request->input('authors'));
        $sanitizedAbstract = $this->sanitizeAbstract((string) $request->input('abstract'));
        $request->merge([
            'title' => $sanitizedTitle,
            'adviser' => $sanitizedAdviser,
            'authors' => $sanitizedAuthors,
            'abstract' => $sanitizedAbstract,
        ]);

        // Normalize title for duplicate checking
        $normalizedTitle = strtolower(trim(preg_replace('/\s+/', ' ', $sanitizedTitle)));

        // Validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:500',
            'adviser' => 'required|string|max:255',
            'abstract' => 'required|string|max:5000',
            'document' => 'required|file|mimes:pdf,doc,docx|max:15360', // 15MB max
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        // Check for duplicate titles (excluding current submission)
        // Since we're using SQLite, we'll do the normalization in PHP instead of using MySQL's REGEXP_REPLACE
        $existingSubmissions = Submission::where('id', '!=', $id)->get(['id', 'title']);
        $duplicateExists = $existingSubmissions->contains(function ($existingSubmission) use ($normalizedTitle) {
            $existingNormalizedTitle = strtolower(trim(preg_replace('/\s+/', ' ', $existingSubmission->title)));
            return $existingNormalizedTitle === $normalizedTitle;
        });

        if ($duplicateExists) {
            return response()->json(
                [
                    'message' => 'A submission with this title already exists',
                    'errors' => ['title' => ['This title is already taken by another submission.']],
                ],
                422,
            );
        }

        try {
            $user = Auth::user();
            $program = $user->program;

            // Handle file upload
            $file = $request->file('document');
            $filePath = $file->store('submissions', 'public');

            // Create a new submission record instead of updating the existing one
            $newSubmission = Submission::create([
                'title' => strtoupper($normalizedTitle),
                'adviser' => ucwords(strtolower($sanitizedAdviser)),
                'authors' => ucwords(strtolower($sanitizedAuthors)),
                'abstract' => $sanitizedAbstract,
                'manuscript_path' => $filePath,
                'manuscript_filename' => $file->getClientOriginalName(),
                'manuscript_size' => $file->getSize(),
                'manuscript_mime' => $file->getMimeType(),
                'program_id' => $program ? $program->id : null,
                'submitted_by' => $user->id,
                'resubmitted_from_id' => $submission->id,
                'submitted_at' => now(),
                'status' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
                'remarks' => null,
            ]);

            // Log resubmission activity
            try {
                UserActivityLog::log($user, UserActivityLog::ACTION_THESIS_SUBMITTED, $newSubmission, $program ? $program->id : null, [
                    'submission' => [
                        'id' => $newSubmission->id,
                        'title_hash' => hash('sha256', $newSubmission->title),
                        'resubmission' => true,
                        'original_submission_id' => $submission->id,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Failed to log resubmission activity', [
                    'error' => $e->getMessage(),
                    'user_id' => $user?->id,
                    'new_submission_id' => $newSubmission->id,
                    'original_submission_id' => $submission->id,
                ]);
            }

            return response()->json(
                [
                    'message' => 'Submission resubmitted successfully',
                    'data' => $newSubmission,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Failed to resubmit submission',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Resubmit a form submission
     */
    public function resubmitForm(Request $request, $id)
    {
        $formSubmission = FacultyFormSubmission::where('id', $id)->where('submitted_by', Auth::id())->where('status', 'rejected')->first();

        if (!$formSubmission) {
            return response()->json(['message' => 'Form submission not found or not eligible for resubmission'], 404);
        }

        // Validate form submission data
        $validator = Validator::make($request->all(), [
            'form_type' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:15360', // 15MB max
            'note' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        try {
            $user = Auth::user();

            // Handle file upload
            $file = $request->file('document');
            $filePath = $file->store('form-submissions', 'public');

            // Create a new form submission record instead of updating the existing one
            $newFormSubmission = FacultyFormSubmission::create([
                'form_type' => $request->form_type,
                'note' => $request->note,
                'document_path' => $filePath,
                'document_filename' => $file->getClientOriginalName(),
                'document_size' => $file->getSize(),
                'document_mime' => $file->getMimeType(),
                'submitted_by' => $user->id,
                'resubmitted_from_id' => $formSubmission->id,
                'submitted_at' => now(),
                'status' => FacultyFormSubmission::STATUS_PENDING,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_remarks' => null,
                'forwarded_to' => null,
            ]);

            // Log form resubmission activity
            try {
                UserActivityLog::log($user, UserActivityLog::ACTION_FORM_SUBMITTED, $newFormSubmission, null, [
                    'form_submission' => [
                        'id' => $newFormSubmission->id,
                        'form_type' => $request->form_type,
                        'resubmission' => true,
                        'original_form_submission_id' => $formSubmission->id,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Failed to log form resubmission activity', [
                    'error' => $e->getMessage(),
                    'user_id' => $user?->id,
                    'new_form_submission_id' => $newFormSubmission->id,
                    'original_form_submission_id' => $formSubmission->id,
                ]);
            }

            return response()->json(
                [
                    'message' => 'Form submission resubmitted successfully',
                    'data' => $newFormSubmission,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Failed to resubmit form submission',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
