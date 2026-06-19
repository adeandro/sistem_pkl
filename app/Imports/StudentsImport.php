<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public $rowsImported = 0;
    public $rowsSkipped = 0;

    public function model(array $row)
    {
        // Require name and nis
        if (!isset($row['name']) || !isset($row['nis'])) {
            $this->rowsSkipped++;
            return null;
        }

        $nis = trim($row['nis']);
        $name = trim($row['name']);

        if (empty($nis) || empty($name)) {
            $this->rowsSkipped++;
            return null;
        }

        // Check uniqueness
        if (Student::where('nis', $nis)->exists()) {
            $this->rowsSkipped++;
            return null;
        }

        $this->rowsImported++;
        return new Student([
            'nis' => $nis,
            'name' => $name,
            'is_assigned' => false,
        ]);
    }
}
