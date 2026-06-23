<x-layouts::app :title="__('Dashboard Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-8 py-8 px-4 md:px-8 max-w-[1400px] mx-auto">
        <div class="flex flex-col gap-1.5">
            <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard Admin</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Kelola data siswa dan penempatan PKL secara terpusat.</p>
        </div>
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-1 flex flex-col gap-8">
                <livewire:admin.placement-form />
            </div>
            <div class="xl:col-span-2">
                <livewire:admin.dashboard />
            </div>
        </div>
    </div>
</x-layouts::app>
