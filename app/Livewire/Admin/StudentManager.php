<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $name = '';
    public $nis = '';
    public $editingId = null;

    #[On('placementAdded')]
    public function refreshStudents()
    {
        // just refresh
    }

    public function resetForm()
    {
        $this->name = '';
        $this->nis = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    public function editStudent($id)
    {
        $student = Student::find($id);
        if ($student) {
            $this->editingId = $student->id;
            $this->name = $student->name;
            $this->nis = $student->nis;
        }
    }

    public function saveStudent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:students,nis,' . $this->editingId,
        ]);

        if ($this->editingId) {
            $student = Student::find($this->editingId);
            $student->update([
                'name' => $this->name,
                'nis' => $this->nis,
            ]);
            session()->flash('message', 'Data siswa berhasil diperbarui.');
        } else {
            Student::create([
                'name' => $this->name,
                'nis' => $this->nis,
            ]);
            session()->flash('message', 'Data siswa berhasil ditambahkan.');
        }

        $this->resetForm();
    }

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
