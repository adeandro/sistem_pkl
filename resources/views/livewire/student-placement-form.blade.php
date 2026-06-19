<div>
    @if($showModal)
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
            <!-- Modal Content -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg border border-slate-200/60 dark:border-slate-800 flex flex-col relative my-auto">
                
                <!-- Close Button -->
                <button wire:click="closeModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors bg-slate-50 dark:bg-slate-800 rounded-full p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <!-- Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/60 text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Daftar PKL Mandiri</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Silakan lengkapi data penempatan Anda</p>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <form wire:submit="submit" class="space-y-6">
                        <!-- NIS Input -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nomor Induk Siswa (NIS)</label>
                            <input type="text" wire:model.live.debounce.500ms="nis" placeholder="Ketik NIS Anda..." class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white sm:text-sm transition-all duration-200 px-4 py-3" autofocus>
                            @if($nisError)
                                <span class="text-sm text-red-500 mt-2 block font-medium">{{ $nisError }}</span>
                            @endif
                            <div wire:loading wire:target="nis" class="mt-2 text-sm text-slate-500 font-medium flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memeriksa NIS...
                            </div>
                        </div>

                        @if($student)
                            <!-- Identitas -->
                            <div class="p-4 bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20 rounded-xl flex items-center gap-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                                <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/50 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-violet-900 dark:text-violet-100">{{ $student->name }}</p>
                                    <p class="text-[11px] text-violet-600/80 dark:text-violet-400/80 font-medium">Data Ditemukan</p>
                                </div>
                            </div>

                            <!-- Input Company Name -->
                            <div class="animate-in fade-in slide-in-from-top-4 duration-300">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Instansi / Perusahaan PKL</label>
                                <input type="text" wire:model.live.debounce.500ms="companyName" placeholder="Contoh: PT Semesta Ilmu" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white sm:text-sm transition-all duration-200 px-4 py-3">
                                @error('companyName') <span class="text-sm text-red-500 mt-2 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($student && strlen(trim($companyName)) >= 3)
                            <!-- Select Students -->
                            <div class="animate-in fade-in slide-in-from-top-4 duration-300">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2 flex justify-between items-end">
                                    <span>Pilih Anggota Kelompok</span>
                                    <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-slate-600 dark:text-slate-400">{{ count($selectedStudents) }} terpilih</span>
                                </label>
                                <!-- Height optimized to prevent overflowing modal -->
                                <div class="h-48 overflow-y-auto border border-slate-200 dark:border-slate-800 rounded-xl p-2 bg-slate-50/50 dark:bg-slate-950/30 relative">
                                    @if($availableStudents->isEmpty())
                                        <div class="text-sm text-slate-500 italic p-4 text-center">Semua siswa lain sudah mendapatkan kelompok.</div>
                                    @else
                                        <div class="grid grid-cols-1 gap-1.5">
                                            @foreach($availableStudents as $availStudent)
                                                <label class="flex items-center p-3 hover:bg-white dark:hover:bg-slate-800 rounded-xl cursor-pointer transition-all duration-200 border border-transparent hover:border-slate-200 hover:shadow-sm dark:hover:border-slate-700 {{ in_array($availStudent->id, $selectedStudents) ? 'bg-white shadow-sm border-violet-200 dark:bg-slate-800 dark:border-violet-700/50' : '' }}">
                                                    <input type="checkbox" wire:model="selectedStudents" value="{{ $availStudent->id }}" 
                                                        class="w-4 h-4 rounded border-slate-300 text-violet-600 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:border-slate-600 dark:bg-slate-900 disabled:opacity-50"
                                                        @if($availStudent->id === $student->id) checked disabled @endif>
                                                    <div class="ml-3 flex flex-col flex-1">
                                                        <span class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $availStudent->name }}</span>
                                                        <span class="text-[11px] text-slate-500 font-mono">{{ $availStudent->nis }}</span>
                                                    </div>
                                                    @if($availStudent->id === $student->id)
                                                        <span class="text-[10px] font-bold text-violet-600 dark:text-violet-400 bg-violet-100 dark:bg-violet-900/50 px-2 py-1 rounded">ANDA</span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @error('selectedStudents') <span class="text-sm text-red-500 mt-2 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800/60 animate-in fade-in slide-in-from-bottom-4 duration-300">
                                <button type="submit" class="w-full px-5 py-3 bg-violet-600 text-white rounded-xl shadow-sm hover:bg-violet-700 hover:shadow-md transition-all duration-200 font-semibold active:scale-[0.98] flex justify-center items-center gap-2" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submit">Simpan & Daftarkan Kelompok</span>
                                    <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span wire:loading wire:target="submit">Memproses...</span>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
