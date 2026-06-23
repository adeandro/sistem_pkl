<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;

new class extends Component {
    use WithFileUploads;

    public $file;
    public $importStats = null;

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new TeachersImport();
        
        try {
            Excel::import($import, $this->file);
            $this->importStats = [
                'imported' => $import->rowsImported,
                'skipped' => $import->rowsSkipped,
            ];
            $this->reset('file');
            session()->flash('message', 'Import berhasil diproses.');
            $this->dispatch('teacherImported'); // dispatch event to update list
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}; ?>

<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 p-6 flex flex-col">
    <div class="flex items-center gap-3 mb-1">
        <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg text-indigo-600 dark:text-indigo-400">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
        </div>
        <h3 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Import Guru</h3>
    </div>
    
    <div class="mb-5 text-sm text-slate-500 dark:text-slate-400">
        <p>Gunakan file Excel (.xlsx) dengan header: <code class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded font-mono text-xs text-slate-700 dark:text-slate-300">name</code>, <code class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded font-mono text-xs text-slate-700 dark:text-slate-300">nip</code>, dan <code class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded font-mono text-xs text-slate-700 dark:text-slate-300">id_type</code> (opsional, "NIP" atau "NIY").</p>
    </div>

    @if (session()->has('message'))
        <div class="p-3 mb-4 text-sm text-emerald-700 rounded-xl bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-3 mb-4 text-sm text-red-700 rounded-xl bg-red-50 border border-red-100 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    @if ($importStats)
        <div class="p-3 mb-4 text-sm text-blue-700 rounded-xl bg-blue-50 border border-blue-100 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
            Hasil: <strong>{{ $importStats['imported'] }}</strong> guru ditambahkan, <strong>{{ $importStats['skipped'] }}</strong> dilewati.
        </div>
    @endif

    <form wire:submit="import" class="space-y-4">
        <div>
            <input type="file" wire:model="file" class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2.5 file:px-4
                file:rounded-xl file:border-0
                file:text-sm file:font-medium
                file:bg-slate-50 file:text-slate-700
                hover:file:bg-slate-100 file:transition-colors file:cursor-pointer
                cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-1 bg-white dark:bg-slate-800
                dark:file:bg-slate-700 dark:file:text-slate-200 dark:hover:file:bg-slate-600">
            @error('file') <span class="text-sm text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <button type="submit" class="w-full px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 font-medium active:scale-[0.98] dark:bg-violet-600 dark:hover:bg-violet-500 flex justify-center items-center gap-2" wire:loading.attr="disabled">
                <svg wire:loading.remove wire:target="import" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span wire:loading.remove wire:target="import">Upload & Import</span>
                
                <svg wire:loading wire:target="import" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span wire:loading wire:target="import">Memproses file...</span>
            </button>
        </div>
    </form>
</div>