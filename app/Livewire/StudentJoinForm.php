<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class StudentJoinForm extends Component
{
    public $showModal = false;
    
    public $nis = '';
    public $student = null;
    public $nisError = '';
    
    public $placementId = null;
    public $placementName = '';

    #[On('openStudentJoinModal')]
    public function openModal($placementId, $placementName)
    {
        $this->resetForm();
        $this->placementId = $placementId;
        $this->placementName = $placementName;
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->nis = '';
        $this->student = null;
        $this->nisError = '';
        $this->placementId = null;
        $this->placementName = '';
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
    }

    public function submit()
    {
        if (!$this->student || !$this->placementId) return;

        DB::beginTransaction();
        try {
            $placement = Placement::lockForUpdate()->find($this->placementId);
            
            if (!$placement) {
                $this->addError('general', 'Instansi tidak ditemukan.');
                DB::rollBack();
                return;
            }

            if (!is_null($placement->quota)) {
                $currentCount = $placement->students()->count();
                if ($currentCount >= $placement->quota) {
                    $this->addError('general', 'Kuota instansi ini sudah penuh.');
                    DB::rollBack();
                    return;
                }
            }

            $s = Student::lockForUpdate()->find($this->student->id);
            if ($s->is_assigned) {
                $this->addError('general', 'Siswa ini sudah didaftarkan ke tempat lain.');
                DB::rollBack();
                return;
            }

            $s->update(['is_assigned' => true]);
            $placement->students()->attach($s->id);

            DB::commit();

            $this->closeModal();
            $this->dispatch('placementCreated'); // Reusing the same event to refresh board
            
            session()->flash('message', 'Berhasil bergabung dengan kelompok PKL!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.student-join-form');
    }
}
