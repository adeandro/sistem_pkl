<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Placement;

class PrintLetter extends Component
{
    use WithFileUploads;

    public $startDate;
    public $endDate;
    public $headmasterName;
    public $headmasterNip;
    public $letterNumber;
    public $attachment;
    
    public $logoUpload;
    public $logoPath;

    public $selectedPlacementId = null;

    public function mount()
    {
        // Default values from session cache
        $this->startDate = session('print_start_date', date('Y-m-d'));
        $this->endDate = session('print_end_date', date('Y-m-d', strtotime('+3 months')));
        $this->headmasterName = session('print_headmaster_name', '');
        $this->headmasterNip = session('print_headmaster_nip', '');
        $this->letterNumber = session('print_letter_number', '... /SMK.AL/PKL/' . date('Y'));
        $this->attachment = session('print_attachment', '1 (Satu) Berkas');
        $this->logoPath = session('print_logo_path', '');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'headmasterName', 'headmasterNip', 'letterNumber', 'attachment'])) {
            session(['print_' . \Illuminate\Support\Str::snake($propertyName) => $this->$propertyName]);
        }
    }

    public function updatedLogoUpload()
    {
        $this->validate([
            'logoUpload' => 'image|max:1024', // 1MB Max
        ]);

        $path = $this->logoUpload->store('logos', 'public');
        $this->logoPath = $path;
        session(['print_logo_path' => $path]);
    }

    public function removeLogo()
    {
        $this->logoPath = '';
        $this->logoUpload = null;
        session(['print_logo_path' => '']);
    }

    public function selectPlacementToPrint($id)
    {
        $this->selectedPlacementId = $id;
    }

    public function closePrintPreview()
    {
        $this->selectedPlacementId = null;
    }

    public function render()
    {
        $placements = Placement::with(['students', 'teacher'])->orderBy('company_name')->get();
        $selectedPlacement = $this->selectedPlacementId ? Placement::with(['students', 'teacher'])->find($this->selectedPlacementId) : null;

        return view('livewire.admin.print-letter', [
            'placements' => $placements,
            'selectedPlacement' => $selectedPlacement,
        ])->layout('layouts.app', ['title' => 'Cetak Surat Pengantar']);
    }
}
