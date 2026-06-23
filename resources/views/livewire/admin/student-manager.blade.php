<div class="flex h-full w-full flex-1 flex-col gap-8 py-8 px-4 md:px-8 max-w-[1400px] mx-auto">
    <div class="flex flex-col gap-1.5">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Data Siswa</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm">Kelola seluruh data siswa secara individu atau melalui fitur import.</p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-1 flex flex-col gap-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 p-6">
                <h3 class="font-semibold text-lg mb-4 text-slate-800 dark:text-white">{{ $editingId ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}</h3>
                <form wire:submit="saveStudent" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">NIS</label>
                        <input type="text" wire:model="nis" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Contoh: 12345">
                        @error('nis') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Siswa</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Contoh: Budi Santoso">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="pt-2 flex gap-2">
                        <button type="submit" class="flex-1 bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                            {{ $editingId ? 'Simpan' : 'Tambahkan' }}
                        </button>
                        @if($editingId)
                            <button type="button" wire:click="resetForm" class="bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-xl font-medium transition-colors">
                                Batal
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <livewire:admin.import-students />
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 flex flex-col">
                <div class="p-5 border-b border-slate-200/60 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col sm:flex-row justify-between gap-4">
                    <input type="text" wire:model.live="search" placeholder="Cari Nama atau NIS..." class="w-full sm:w-64 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-200 transition-colors text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200/60 dark:divide-slate-800">
                        <thead class="bg-slate-50/50 dark:bg-slate-950/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-24">NIS</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Penempatan</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800/50">
                            @foreach($students as $student)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $student->nis }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ $student->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($student->is_assigned)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 text-xs font-medium">Sudah Ditempatkan</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-xs font-medium">Belum Ditempatkan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button wire:click="editStudent({{ $student->id }})" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors px-3 py-1.5 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-transparent hover:border-blue-200 dark:hover:border-blue-800">
                                            Edit
                                        </button>
                                        <button wire:click="deleteStudent({{ $student->id }})" wire:confirm="Yakin ingin menghapus data siswa ini secara permanen?" class="text-sm font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors px-3 py-1.5 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 border border-transparent hover:border-red-200 dark:hover:border-red-800">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @if($students->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada data siswa yang sesuai.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($students->hasPages())
                    <div class="p-4 border-t border-slate-200/60 dark:border-slate-800">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
