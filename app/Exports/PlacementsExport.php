<?php

namespace App\Exports;

use App\Models\Placement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlacementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Placement::with('students')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Instansi',
            'Siswa 1',
            'Siswa 2',
            'Siswa 3',
            'Siswa 4',
            'Siswa 5',
            'Siswa 6',
            'Siswa 7',
            'Siswa 8',
            'Siswa 9',
            'Siswa 10',
        ];
    }

    public function map($placement): array
    {
        $row = [
            $placement->company_name,
        ];

        foreach ($placement->students as $student) {
            $row[] = $student->name . ' (' . $student->nis . ')';
        }

        // Fill remaining columns if fewer than 10 students
        while (count($row) < 11) {
            $row[] = '';
        }

        return $row;
    }
}
