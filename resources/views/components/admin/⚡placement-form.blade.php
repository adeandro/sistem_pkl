<?php

use Livewire\Component;
use App\Models\Placement;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

new class extends Component {
    public $editId = null;
    public $companyName = '';
    public $selectedStudents = [];
    
    #[On('editPlacement')]
    public function loadEdit($id)
    {
        $this->resetValidation();
        $placement = Placement::with('students')->find($id);
        if ($placement) {
            $this->editId = $placement->id;
            $this->companyName = $placement->company_name;
            $this->selectedStudents = $placement->students->pluck('id')->toArray();
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editId', 'companyName', 'selectedStudents']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->companyName = trim($this->companyName);
        
        if (empty($this->companyName)) {
            $this->addError('companyName', 'Nama instansi tidak boleh kosong.');
            return;
        }

        if (empty($this->selectedStudents)) {
            $this->addError('selectedStudents', 'Pilih minimal 1 siswa.');
            return;
        }

        DB::beginTransaction();

        try {
            if ($this->editId) {
                // UPDATE MODE
                $placement = Placement::findOrFail($this->editId);
                $quota = $placement->quota;
                $oldStudentIds = $placement->students->pluck('id')->toArray();
                $newStudentIds = $this->selectedStudents;

                if (!is_null($quota) && count($newStudentIds) > $quota) {
                    $this->addError('selectedStudents', 'Jumlah siswa melebihi kuota instansi.');
                    DB::rollBack();
                    return;
                }
                
                $toDetach = array_diff($oldStudentIds, $newStudentIds);
                $toAttach = array_diff($newStudentIds, $oldStudentIds);

                $alreadyAssigned = Student::whereIn('id', $toAttach)->where('is_assigned', true)->exists();
                if ($alreadyAssigned) {
                    $this->addError('selectedStudents', 'Beberapa siswa yang baru ditambahkan sudah terdaftar di tempat lain.');
                    DB::rollBack();
                    return;
                }

                $placement->update([
                    'company_name' => $this->companyName,
                ]);

                $placement->students()->sync($newStudentIds);
                
                if (!empty($toDetach)) {
                    Student::whereIn('id', $toDetach)->update(['is_assigned' => false]);
                }
                if (!empty($toAttach)) {
                    Student::whereIn('id', $toAttach)->update(['is_assigned' => true]);
                }

                DB::commit();
                session()->flash('message', 'Perubahan berhasil disimpan!');
                $this->cancelEdit();
                $this->dispatch('placementAdded');

            } else {
                // CREATE / MERGE MODE
                $placement = Placement::whereRaw('LOWER(trim(company_name)) = ?', [strtolower($this->companyName)])->first();
                $numStudents = count($this->selectedStudents);

                if ($placement) {
                    if (!is_null($placement->quota)) {
                        $currentStudentsCount = $placement->students()->count();
                        $available = $placement->quota - $currentStudentsCount;
                        if ($numStudents > $available) {
                            $this->addError('selectedStudents', 'Kuota instansi ini tidak mencukupi. (Sisa kuota: ' . $available . ')');
                            DB::rollBack();
                            return;
                        }
                    }
                } else {
                    $placement = Placement::create([
                        'company_name' => $this->companyName,
                        'quota' => 3,
                    ]);
                }

                $validStudents = Student::whereIn('id', $this->selectedStudents)
                    ->where('is_assigned', false)
                    ->pluck('id')
                    ->toArray();
                    
                if (count($validStudents) !== count($this->selectedStudents)) {
                    $this->addError('selectedStudents', 'Beberapa siswa sudah terdaftar di tempat lain saat Anda memproses.');
                    DB::rollBack();
                    return;
                }

                $placement->students()->attach($this->selectedStudents);
                Student::whereIn('id', $this->selectedStudents)->update(['is_assigned' => true]);

                DB::commit();
                session()->flash('message', 'Penempatan berhasil disimpan!');
                $this->reset(['companyName', 'selectedStudents']);
                $this->dispatch('placementAdded');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('companyName', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function with()
    {
        $students = Student::where('is_assigned', false);
        if ($this->editId) {
            $students = $students->orWhereHas('placements', function($q) {
                $q->where('placements.id', $this->editId);
            });
        }
        
        return [
            'availableStudents' => $students->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 p-6 flex flex-col h-full">
    <div class="flex justify-between items-center mb-5">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">
                {{ $editId ? 'Edit Penempatan' : 'Form Penempatan' }}
            </h3>
        </div>
        @if($editId)
            <button type="button" wire:click="cancelEdit" class="text-xs font-semibold px-2.5 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors dark:bg-red-500/10 dark:text-red-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Batal Edit
            </button>
        @endif
    </div>
    
    @if (session()->has('message'))
        <div class="p-3 mb-5 text-sm text-emerald-700 rounded-xl bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-5 flex-1 flex flex-col">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Instansi / Perusahaan</label>
            <input type="text" wire:model.live="companyName" placeholder="Contoh: PT Teknologi Terdepan" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white sm:text-sm transition-all duration-200 px-4 py-2.5">
            @if(!$editId)
                <p class="text-[13px] text-slate-500 dark:text-slate-400 mt-2 flex items-start gap-1.5">
                    <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sistem otomatis menggabungkan siswa ke instansi yang sama jika namanya cocok.
                </p>
            @endif
            @error('companyName') <span class="text-sm text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        @if(strlen(trim($companyName)) > 0)
        <div class="flex-1 flex flex-col">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2 flex justify-between items-end">
                <span>Pilih Siswa</span>
                <span class="text-xs font-normal text-slate-500">{{ count($selectedStudents) }} terpilih</span>
            </label>
            <div class="flex-1 min-h-[200px] max-h-[300px] overflow-y-auto border border-slate-200 dark:border-slate-800 rounded-xl p-2 bg-slate-50/50 dark:bg-slate-950/30">
                @if($availableStudents->isEmpty())
                    <div class="text-sm text-slate-500 italic p-4 text-center">Semua siswa sudah mendapat tempat atau belum ada data siswa yang di-import.</div>
                @else
                    <div class="grid grid-cols-1 gap-1">
                        @foreach($availableStudents as $student)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors {{ in_array($student->id, $selectedStudents) ? 'bg-violet-50 border-violet-200 dark:bg-violet-500/10 dark:border-violet-500/30' : '' }}">
                                <input type="checkbox" wire:model="selectedStudents" value="{{ $student->id }}" class="w-4 h-4 rounded border-slate-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:border-slate-600 dark:bg-slate-900">
                                <div class="ml-3 flex flex-col">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $student->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $student->nis }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
            @error('selectedStudents') <span class="text-sm text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full px-6 py-2.5 bg-violet-600 text-white rounded-xl shadow-sm hover:bg-violet-700 hover:shadow transition-all duration-200 font-medium active:scale-[0.98] flex justify-center items-center gap-2" wire:loading.attr="disabled">
                <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span wire:loading.remove wire:target="save">
                    {{ $editId ? 'Simpan Perubahan' : 'Simpan Penempatan' }}
                </span>
                
                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
        @endif
    </form>
</div>
