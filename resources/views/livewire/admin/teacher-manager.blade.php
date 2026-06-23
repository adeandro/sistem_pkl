<div class="flex h-full w-full flex-1 flex-col gap-8 py-8 px-4 md:px-8 max-w-[1400px] mx-auto">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Guru Pembimbing</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola data guru pembimbing PKL.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-slate-800 dark:text-white">{{ $editingId ? 'Edit Data Guru' : 'Tambah Guru Baru' }}</h3>
                    <form wire:submit="saveTeacher" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap (Beserta Gelar)</label>
                            <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Contoh: Budi Santoso, S.Kom">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jenis & Nomor Induk (Opsional)</label>
                            <div class="flex gap-2">
                                <select wire:model="id_type" class="w-24 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                                    <option value="NIP">NIP</option>
                                    <option value="NIY">NIY</option>
                                </select>
                                <input type="text" wire:model="nip" class="flex-1 rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="199xxx">
                            </div>
                            @error('nip') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                
                <div class="mt-6">
                    <livewire:admin.import-teachers />
                </div>
            </div>

            <!-- Table Section -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 overflow-hidden">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-800 dark:text-slate-200 uppercase text-xs font-semibold border-b border-slate-200/60 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-4">Nama Guru</th>
                                <th class="px-6 py-4">Nomor Induk</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @forelse($teachers as $teacher)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $teacher->name }}</td>
                                    <td class="px-6 py-4">{{ $teacher->nip ? $teacher->id_type . '. ' . $teacher->nip : '-' }}</td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <button wire:click="editTeacher({{ $teacher->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Edit</button>
                                        <button wire:click="deleteTeacher({{ $teacher->id }})" onclick="confirm('Yakin ingin menghapus guru ini?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">Belum ada data guru pembimbing.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
