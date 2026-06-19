<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class StudentPlacementForm extends Component
{
    public $showModal = false;
    
    public $nis = '';
    public $student = null;
    public $nisError = '';
    
    public $companyName = '';
    public $selectedStudents = [];

    #[On('openStudentPlacementModal')]
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->nis = '';
        $this->student = null;
        $this->nisError = '';
        $this->companyName = '';
        $this->selectedStudents = [];
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function updatedNis($value)
    {
        $this->student = null;
        $this->nisError = '';
        $this->companyName = '';
        $this->selectedStudents = [];
        $this->resetValidation();

        $value = trim($value);
        if (empty($value)) {
            return;
        }

        $student = Student::where('nis', $value)->first();

        if (!$student) {
            $this->nisError = 'NIS tidak terdaftar di sistem.';
            return;
        }

        if ($student->is_assigned) {
            $this->nisError = 'Siswa ini sudah mendapatkan penempatan PKL.';
            return;
        }

        $this->student = $student;
        $this->selectedStudents = [$student->id];
    }

    public function submit()
    {
        if (!$this->student) return;

        $this->validate([
            'companyName' => 'required|string|min:3',
            'selectedStudents' => 'required|array|min:1',
        ], [
            'companyName.required' => 'Nama instansi wajib diisi.',
            'companyName.min' => 'Nama instansi terlalu pendek.',
            'selectedStudents.required' => 'Pilih minimal 1 siswa.',
        ]);

        if (!in_array($this->student->id, $this->selectedStudents)) {
            $this->selectedStudents[] = $this->student->id;
        }

        DB::beginTransaction();
        try {
            $placement = Placement::firstOrCreate(
                ['company_name' => trim($this->companyName)],
                ['quota' => null]
            );

            if (!is_null($placement->quota)) {
                $currentCount = $placement->students()->count();
                $newCount = count($this->selectedStudents);
                if (($currentCount + $newCount) > $placement->quota) {
                    $this->addError('companyName', 'Kuota instansi ini tidak mencukupi untuk jumlah siswa yang dipilih.');
                    DB::rollBack();
                    return;
                }
            }

            $studentsToAssign = Student::whereIn('id', $this->selectedStudents)->lockForUpdate()->get();
            foreach ($studentsToAssign as $s) {
                if ($s->is_assigned) {
                    $this->addError('selectedStudents', 'Siswa ' . $s->name . ' baru saja didaftarkan ke tempat lain.');
                    DB::rollBack();
                    return;
                }
            }

            Student::whereIn('id', $this->selectedStudents)->update(['is_assigned' => true]);

            $placement->students()->attach($this->selectedStudents);

            DB::commit();

            $this->closeModal();
            $this->dispatch('placementCreated');
            
            session()->flash('message', 'Berhasil mendaftar lokasi PKL secara mandiri!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('companyName', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function render()
    {
        $availableStudents = [];
        if ($this->student && strlen(trim($this->companyName)) >= 3) {
            $availableStudents = Student::where('is_assigned', false)
                ->orderBy('name')
                ->get();
        }

        return view('livewire.student-placement-form', [
            'availableStudents' => $availableStudents,
        ]);
    }
}
