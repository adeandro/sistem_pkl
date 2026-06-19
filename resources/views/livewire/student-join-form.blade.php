<div>
    @if($showModal)
        <div style="background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg border border-slate-200/60 dark:border-slate-800 flex flex-col relative my-auto">
                <button wire:click="closeModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors bg-slate-50 dark:bg-slate-800 rounded-full p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/60 text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Gabung Kelompok PKL</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Bergabung dengan instansi: <span class="font-bold text-violet-600 dark:text-violet-400">{{ $placementName }}</span></p>
                </div>
                <div class="p-6">
                    <form wire:submit="submit" class="space-y-6">
                        @if($errors->has('general'))
                            <div class="p-3 bg-red-50 text-red-600 border border-red-200 rounded-xl text-sm font-medium">
                                {{ $errors->first('general') }}
                            </div>
                        @endif

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
                            <div class="p-4 bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20 rounded-xl flex items-center gap-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                                <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/50 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-violet-900 dark:text-violet-100">{{ $student->name }}</p>
                                    <p class="text-[11px] text-violet-600/80 dark:text-violet-400/80 font-medium">Data Ditemukan</p>
                                </div>
                            </div>

                            <div class="pt-4 flex gap-3 border-t border-slate-100 dark:border-slate-800/60 animate-in fade-in slide-in-from-bottom-4 duration-300">
                                <button type="button" wire:click="closeModal" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">
                                    Batal
                                </button>
                                <button type="submit" style="background-color: #7c3aed; color: #ffffff;" class="flex-1 px-5 py-3 rounded-xl shadow-sm hover:opacity-90 hover:shadow-md transition-all duration-200 font-semibold active:scale-[0.98] flex justify-center items-center gap-2" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submit">Gabung Kelompok Ini</span>
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
