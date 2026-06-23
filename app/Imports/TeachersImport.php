<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TeachersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public $rowsImported = 0;
    public $rowsSkipped = 0;

    public function model(array $row)
    {
        // Require name
        if (!isset($row['name'])) {
            $this->rowsSkipped++;
            return null;
        }

        $name = trim($row['name']);
        if (empty($name)) {
            $this->rowsSkipped++;
            return null;
        }

        $nip = isset($row['nip']) ? trim($row['nip']) : null;
        $idType = isset($row['id_type']) ? strtoupper(trim($row['id_type'])) : 'NIP';
        if (!in_array($idType, ['NIP', 'NIY'])) {
            $idType = 'NIP';
        }

        // Check uniqueness by name to prevent duplicates since NIP can be empty
        if (Teacher::where('name', $name)->exists() || ($nip && Teacher::where('nip', $nip)->exists())) {
            $this->rowsSkipped++;
            return null;
        }

        $this->rowsImported++;
        return new Teacher([
            'name' => $name,
            'nip' => $nip,
            'id_type' => $idType,
        ]);
    }
}
