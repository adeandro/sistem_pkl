<?php

use Livewire\Component;
use App\Models\Placement;
use Livewire\Attributes\On;

new class extends Component {
    
    #[On('placementCreated')]
    public function refreshBoard()
    {
        // automatically re-renders
    }

    public function with()
    {
        return [
            'placements' => Placement::with('students')->orderBy('company_name')->get(),
        ];
    }
}; ?>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach($placements as $placement)
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 flex flex-col hover:shadow-lg hover:border-violet-200 dark:hover:border-violet-800/50 hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800/60 flex flex-col gap-3">
                <div class="flex justify-between items-start gap-4">
                    <h3 class="font-semibold text-lg text-slate-900 dark:text-white leading-tight group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                        {{ $placement->company_name }}
                    </h3>
                    @php
                        $isUnlimited = is_null($placement->quota);
                        $count = $placement->students->count();
                        $isFull = !$isUnlimited && $count >= $placement->quota;
                        
                        if ($isUnlimited) {
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/50 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20';
                            $badgeText = 'Tak Terbatas';
                        } elseif ($isFull) {
                            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200/50 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20';
                            $badgeText = 'Penuh ('.$count.'/'.$placement->quota.')';
                        } else {
                            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200/50 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20';
                            $badgeText = 'Sisa '.($placement->quota - $count).' ('.$count.'/'.$placement->quota.')';
                        }
                    @endphp
                    <div class="shrink-0 text-[11px] font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap {{ $badgeClass }}">
                        {{ $badgeText }}
                    </div>
                </div>
            </div>
            
            <div class="p-5 flex-1 bg-slate-50/30 dark:bg-slate-900/30 flex flex-col justify-between">
                <div>
                    @if($placement->students->isEmpty())
                        <div class="h-full flex items-center justify-center py-6 text-slate-400 dark:text-slate-500 italic text-sm">
                            Belum ada siswa terdaftar
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-2.5">
                            @foreach($placement->students as $student)
                                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 shadow-sm hover:border-violet-200 dark:hover:border-violet-700/50 transition-colors group/item cursor-default">
                                    <div class="w-8 h-8 rounded-full bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0 group-hover/item:scale-110 transition-transform duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div class="flex flex-col overflow-hidden">
                                        <span class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate group-hover/item:text-violet-600 dark:group-hover/item:text-violet-400 transition-colors">{{ $student->name }}</span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ $student->nis }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if(!$isFull)
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800/60">
                    <button onclick="Livewire.dispatch('openStudentJoinModal', { placementId: {{ $placement->id }}, placementName: '{{ addslashes($placement->company_name) }}' })" style="color: #7c3aed; border-color: #c4b5fd; background-color: #f5f3ff;" class="w-full py-2.5 border rounded-xl shadow-sm hover:opacity-80 transition-colors font-medium text-sm flex items-center justify-center gap-2 dark:bg-violet-900/20 dark:border-violet-800/50 dark:text-violet-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Bergabung ke Kelompok
                    </button>
                </div>
                @endif
            </div>
        </div>
    @endforeach

    @if($placements->isEmpty())
        <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 text-slate-400 dark:text-slate-500">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Belum Ada Penempatan</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 max-w-sm text-center">Admin belum merilis daftar instansi tempat PKL satupun ke sistem.</p>
        </div>
    @endif
</div>