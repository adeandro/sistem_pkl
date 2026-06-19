<x-layouts::student title="Papan Informasi PKL">
    <div class="flex h-full w-full flex-1 flex-col gap-8 py-12 px-4 md:px-8 max-w-[1400px] mx-auto">
        <div class="mb-10 text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-4 leading-tight">
                Daftar Penempatan <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600 dark:from-violet-400 dark:to-fuchsia-400">PKL</span>
                <br class="hidden sm:block" /> SMK Al Mabrur Pejawaran
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg md:text-xl">
                Papan informasi resmi penempatan Praktik Kerja Lapangan. Seluruh data diatur secara terpusat oleh Admin.
            </p>

            <div class="mt-8">
                <button onclick="Livewire.dispatch('openStudentPlacementModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 font-semibold dark:bg-violet-600 dark:hover:bg-violet-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Input Lokasi PKL Mandiri
                </button>
            </div>
            
            @if (session()->has('message'))
                <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/50 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20 text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('message') }}
                </div>
            @endif
        </div>
        
        <livewire:pkl-board />
        <livewire:student-placement-form />
    </div>
</x-layouts::student>
