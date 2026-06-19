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
    public $step = 1;
    
    // Step 1
    public $nis = '';
    public $student = null;
    
    // Step 2
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
        $this->step = 1;
        $this->nis = '';
        $this->student = null;
        $this->companyName = '';
        $this->selectedStudents = [];
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function verifyNis()
    {
        $this->validate([
            'nis' => 'required|string',
        ], [
            'nis.required' => 'NIS tidak boleh kosong.',
        ]);

        $student = Student::where('nis', trim($this->nis))->first();

        if (!$student) {
            $this->addError('nis', 'NIS tidak terdaftar di sistem.');
            return;
        }

        if ($student->is_assigned) {
            $this->addError('nis', 'Siswa ini sudah mendapatkan penempatan PKL.');
            return;
        }

        $this->student = $student;
        $this->selectedStudents = [$student->id];
        $this->step = 2;
    }

    public function verifyCompany()
    {
        $this->validate([
            'companyName' => 'required|string|min:3',
        ], [
            'companyName.required' => 'Nama instansi wajib diisi.',
            'companyName.min' => 'Nama instansi terlalu pendek.',
        ]);

        $this->step = 3;
    }

    public function submit()
    {
        $this->validate([
            'companyName' => 'required|string|min:3',
            'selectedStudents' => 'required|array|min:1',
        ], [
            'companyName.required' => 'Nama instansi wajib diisi.',
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
                    $this->step = 2; // Go back to company name step
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
            $this->step = 2;
        }
    }

    public function render()
    {
        $availableStudents = [];
        if ($this->step === 3) {
            $availableStudents = Student::where('is_assigned', false)
                ->orderBy('name')
                ->get();
        }

        return view('livewire.student-placement-form', [
            'availableStudents' => $availableStudents,
        ]);
    }
}
