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
    public $headmasterIdType;
    public $letterNumber;
    public $attachment;
    public $letterType;
    
    public $logoUpload;
    public $logoPath;

    public $signatureUpload;
    public $signaturePath;

    public $selectedPlacementId = null;
    public $isBatchPrint = false;

    public function mount()
    {
        // Default values from session cache
        $this->startDate = session('print_start_date', date('Y-m-d'));
        $this->endDate = session('print_end_date', date('Y-m-d', strtotime('+3 months')));
        $this->headmasterName = session('print_headmaster_name', '');
        $this->headmasterNip = session('print_headmaster_nip', '');
        $this->headmasterIdType = session('print_headmaster_id_type', 'NIP');
        $this->letterNumber = session('print_letter_number', '... /SMK.AL/PKL/' . date('Y'));
        $this->attachment = session('print_attachment', '1 (Satu) Berkas');
        $this->letterType = session('print_letter_type', 'permohonan');
        $this->logoPath = session('print_logo_path', '');
        $this->signaturePath = session('print_signature_path', '');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'headmasterName', 'headmasterNip', 'letterNumber', 'attachment', 'letterType'])) {
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

    public function updatedSignatureUpload()
    {
        $this->validate([
            'signatureUpload' => 'image|max:1024', // 1MB Max
        ]);

        $path = $this->signatureUpload->store('signatures', 'public');
        $this->signaturePath = $path;
        session(['print_signature_path' => $path]);
    }

    public function removeSignature()
    {
        $this->signaturePath = '';
        $this->signatureUpload = null;
        session(['print_signature_path' => '']);
    }

    public function selectPlacementToPrint($id)
    {
        $this->selectedPlacementId = $id;
        $this->isBatchPrint = false;
    }

    public function selectAllToPrint()
    {
        $this->isBatchPrint = true;
        $this->selectedPlacementId = null;
    }

    public function closePrintPreview()
    {
        $this->selectedPlacementId = null;
        $this->isBatchPrint = false;
    }

    public function render()
    {
        $placements = Placement::with(['students', 'teacher'])->orderBy('company_name')->get();
        
        $placementsToPrint = collect();
        if ($this->selectedPlacementId) {
            $p = Placement::with(['students', 'teacher'])->find($this->selectedPlacementId);
            if ($p) $placementsToPrint->push($p);
        } elseif ($this->isBatchPrint) {
            $placementsToPrint = $placements->filter(function($p) {
                return $p->students->count() > 0 && ($this->letterType === 'permohonan' || $p->teacher);
            });
        }

        return view('livewire.admin.print-letter', [
            'placements' => $placements,
            'placementsToPrint' => $placementsToPrint,
        ])->layout('layouts.app', ['title' => 'Cetak Surat PKL']);
    }
}
