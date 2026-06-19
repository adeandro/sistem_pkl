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
        </div>
        
        <livewire:pkl-board />
    </div>
</x-layouts::student>
