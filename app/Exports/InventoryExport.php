<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Inventory::with('program')->get()->map(function ($item) {
            return [
                'Title' => $item->title,
                'Authors' => $item->authors,
                'Adviser' => $item->adviser,
                'Program' => $item->program->name ?? '',
                'Year' => $item->academic_year,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Authors',
            'Adviser',
            'Program',
            'Year',
        ];
    }
}
