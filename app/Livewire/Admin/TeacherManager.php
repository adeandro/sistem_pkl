<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Teacher;

class TeacherManager extends Component
{
    public $teachers;
    public $name = '';
    public $nip = '';
    public $id_type = 'NIP';
    public $editingId = null;

    public function mount()
    {
        $this->loadTeachers();
    }

    #[On('teacherImported')]
    public function loadTeachers()
    {
        $this->teachers = Teacher::orderBy('name')->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->nip = '';
        $this->id_type = 'NIP';
        $this->editingId = null;
        $this->resetValidation();
    }

    public function editTeacher($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $this->editingId = $teacher->id;
            $this->name = $teacher->name;
            $this->nip = $teacher->nip;
            $this->id_type = $teacher->id_type ?? 'NIP';
        }
    }

    public function saveTeacher()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'id_type' => 'required|in:NIP,NIY',
        ]);

        if ($this->editingId) {
            $teacher = Teacher::find($this->editingId);
            $teacher->update([
                'name' => $this->name,
                'nip' => $this->nip,
                'id_type' => $this->id_type,
            ]);
            session()->flash('message', 'Data guru berhasil diperbarui.');
        } else {
            Teacher::create([
                'name' => $this->name,
                'nip' => $this->nip,
                'id_type' => $this->id_type,
            ]);
            session()->flash('message', 'Data guru berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadTeachers();
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacher->delete();
            $this->loadTeachers();
            session()->flash('message', 'Data guru berhasil dihapus.');
        }
    }

    public function render()
    {
        return view('livewire.admin.teacher-manager')->layout('layouts.app', ['title' => 'Guru Pembimbing']);
    }
}
