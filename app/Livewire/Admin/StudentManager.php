<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentManager extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteStudent($id)
    {
        DB::transaction(function () use ($id) {
            $student = Student::find($id);
            if ($student) {
                // Detach from placements
                $student->placements()->detach();
                $student->delete();
                session()->flash('message', 'Data siswa berhasil dihapus.');
            }
        });
    }

    public function render()
    {
        $students = Student::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nis', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.admin.student-manager', [
            'students' => $students
        ])->layout('layouts.app', ['title' => 'Data Siswa']);
    }
}
