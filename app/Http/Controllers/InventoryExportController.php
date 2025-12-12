<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\Models\Inventory;
use App\Models\UserActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse; // <-- add this

class InventoryExportController extends Controller
{
    public function pdf(): StreamedResponse
    {
        // <-- change here
        // <-- change here
        $inventories = Inventory::with('program')->get();
        $pdf = Pdf::loadView('exports.inventory_pdf', compact('inventories'))->setPaper('a4', 'landscape');

        return response()->streamDownload(fn () => print $pdf->output(), 'inventories.pdf');
    }

    public function excel(): BinaryFileResponse
    {
        // Log inventory export (excel)
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_INVENTORY_EXPORTED, 'inventories', null, [
            'format' => 'excel',
            'filename' => 'inventories.xlsx',
        ]);

        return Excel::download(new InventoryExport, 'inventories.xlsx');
    }
}
