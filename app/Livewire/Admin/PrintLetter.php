<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Placement;

class PrintLetter extends Component
{
    public $startDate;
    public $endDate;
    public $headmasterName;
    public $headmasterNip;

    public $selectedPlacementId = null;

    public function mount()
    {
        // Default values from session cache
        $this->startDate = session('print_start_date', date('Y-m-d'));
        $this->endDate = session('print_end_date', date('Y-m-d', strtotime('+3 months')));
        $this->headmasterName = session('print_headmaster_name', '');
        $this->headmasterNip = session('print_headmaster_nip', '');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'headmasterName', 'headmasterNip'])) {
            session(['print_' . \Illuminate\Support\Str::snake($propertyName) => $this->$propertyName]);
        }
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
        ]);
    }
}
