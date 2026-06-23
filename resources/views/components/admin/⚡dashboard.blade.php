<?php

use Livewire\Component;
use App\Models\Placement;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

new class extends Component {
    #[On('placementAdded')]
    public function refreshPlacements() {}

    public function editPlacement($id)
    {
        $this->dispatch('editPlacement', id: $id);
    }

    public function deletePlacement($id)
    {
        DB::transaction(function () use ($id) {
            $placement = Placement::find($id);
            if ($placement) {
                $studentIds = $placement->students()->pluck('students.id')->toArray();
                if (!empty($studentIds)) {
                    Student::whereIn('id', $studentIds)->update(['is_assigned' => false]);
                }
                $placement->students()->detach();
                $placement->delete();
                $this->dispatch('placementAdded');
            }
        });
    }

    public function updateQuota($placementId, $newQuota)
    {
        $placement = Placement::find($placementId);
        if ($placement) {
            $quota = $newQuota === '' ? null : (int) $newQuota;
            $placement->update(['quota' => $quota]);
        }
    }

    public function assignTeacher($placementId, $teacherId)
    {
        $placement = Placement::find($placementId);
        if ($placement) {
            $teacherId = $teacherId === '' ? null : (int) $teacherId;
            $placement->update(['teacher_id' => $teacherId]);
        }
    }

    public function removeStudent($studentId, $placementId)
    {
        DB::transaction(function () use ($studentId, $placementId) {
            $placement = Placement::find($placementId);
            $student = Student::find($studentId);
            
            if ($placement && $student) {
                $placement->students()->detach($student->id);
                $student->update(['is_assigned' => false]);
            }
        });
    }

    public function exportExcel()
    {
        return redirect()->route('admin.export');
    }

    public function with()
    {
        return [
            'placements' => Placement::with(['students', 'teacher'])->orderBy('company_name')->get(),
            'teachers' => Teacher::orderBy('name')->get(),
        ];
    }
}; ?>
<div class="flex flex-col gap-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-slate-800 flex items-center gap-5">
            <div class="p-3.5 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl text-indigo-600 dark:text-indigo-400">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Kelompok</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $placements->count() }}</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-slate-800 flex items-center gap-5">
            <div class="p-3.5 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl text-emerald-600 dark:text-emerald-400">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Siswa Terdaftar</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ \App\Models\Student::where('is_assigned', true)->count() }} <span class="text-lg font-semibold text-slate-400">/ {{ \App\Models\Student::count() }}</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 overflow-hidden flex flex-col">
    <div class="p-5 border-b border-slate-200/60 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
        <h3 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Daftar Tempat PKL</h3>
        <button wire:click="exportExcel" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl shadow-sm hover:bg-slate-50 transition-all duration-200 font-medium text-sm active:scale-[0.98] flex items-center gap-2 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel
        </button>
    </div>
    
    <div class="overflow-x-auto flex-1">
        <table class="min-w-full divide-y divide-slate-200/60 dark:divide-slate-800">
            <thead class="bg-slate-50/50 dark:bg-slate-950/50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Instansi & Kuota</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa Terdaftar</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800/50">
                @foreach($placements as $placement)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="px-6 py-5 align-top">
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                {{ $placement->company_name }}
                            </div>
                            <div class="flex flex-col gap-2 mt-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-500 w-24">Batas Kuota:</span>
                                    <input type="number" 
                                        value="{{ $placement->quota }}"
                                        wire:change="updateQuota({{ $placement->id }}, $event.target.value)"
                                        placeholder="Tak Terbatas"
                                        class="flex-1 px-2.5 py-1 text-xs rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-200 transition-colors">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-500 w-24">Guru Pembimbing:</span>
                                    <select 
                                        wire:change="assignTeacher({{ $placement->id }}, $event.target.value)"
                                        class="flex-1 px-2.5 py-1 text-xs rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-200 transition-colors">
                                        <option value="">-- Belum Ditugaskan --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $placement->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($placement->students->isEmpty())
                                <span class="text-sm italic text-slate-400">Belum ada siswa terdaftar</span>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach($placement->students as $student)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-300">
                                            <span>{{ $student->name }}</span>
                                            <button onclick="confirm('Yakin ingin mengeluarkan siswa ini dari kelompok?') || event.stopImmediatePropagation()" wire:click="removeStudent({{ $student->id }}, {{ $placement->id }})" class="text-slate-400 hover:text-red-500 transition-colors p-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 align-top text-right whitespace-nowrap">
                            <div class="flex flex-col items-end gap-2">
                                <button wire:click="editPlacement({{ $placement->id }})" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                    Edit
                                </button>
                                <button wire:click="deletePlacement({{ $placement->id }})" wire:confirm="Yakin menghapus penempatan ini? Seluruh siswanya akan kembali menjadi belum terdaftar." class="text-sm font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if($placements->isEmpty())
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-3 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada data penempatan tempat PKL.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>