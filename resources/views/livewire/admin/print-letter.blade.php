<x-layouts::app :title="__('Cetak Surat Pengantar')">
    <div class="flex h-full w-full flex-1 flex-col gap-8 py-8 px-4 md:px-8 max-w-[1400px] mx-auto print:hidden">
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Cetak Surat Pengantar</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pengaturan format surat dan daftar kelompok untuk dicetak.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Settings Section -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 p-6">
                        <h3 class="font-semibold text-lg mb-4 text-slate-800 dark:text-white">Pengaturan Surat</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kepala Sekolah</label>
                                <input type="text" wire:model.live="headmasterName" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Nama Kepala Sekolah">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">NIP Kepala Sekolah</label>
                                <input type="text" wire:model.live="headmasterNip" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="NIP">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai PKL</label>
                                <input type="date" wire:model.live="startDate" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai PKL</label>
                                <input type="date" wire:model.live="endDate" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-4 leading-relaxed">Pengaturan ini disimpan otomatis sementara di browser Anda, tidak perlu menyimpannya.</p>
                    </div>
                </div>

                <!-- Placements List -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-800 overflow-hidden">
                        <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-800 dark:text-slate-200 uppercase text-xs font-semibold border-b border-slate-200/60 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-4">Instansi Tempat PKL</th>
                                    <th class="px-6 py-4">Guru Pembimbing</th>
                                    <th class="px-6 py-4 text-center">Jml Siswa</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @forelse($placements as $placement)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $placement->company_name }}</td>
                                        <td class="px-6 py-4">
                                            @if($placement->teacher)
                                                <span class="text-violet-600 dark:text-violet-400 font-medium">{{ $placement->teacher->name }}</span>
                                            @else
                                                <span class="text-red-500 text-xs font-medium bg-red-50 dark:bg-red-500/10 px-2.5 py-1 rounded-md">Belum Ditugaskan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300">
                                                {{ $placement->students->count() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-3">
                                            @if($placement->students->count() > 0 && $placement->teacher)
                                                <button wire:click="selectPlacementToPrint({{ $placement->id }})" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium transition-colors border border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 dark:hover:bg-indigo-500/20">
                                                    Lihat & Cetak
                                                </button>
                                            @else
                                                <span class="text-xs text-slate-400 italic font-medium">Data belum lengkap</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada data tempat PKL.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Preview Modal / Fullscreen -->
    @if($selectedPlacement)
        <div class="fixed inset-0 z-[100] bg-slate-100 dark:bg-slate-900 overflow-y-auto print:bg-white print:static print:overflow-visible flex flex-col">
            <div class="sticky top-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 p-4 flex justify-between items-center shadow-sm print:hidden z-10">
                <div class="flex items-center gap-4">
                    <button wire:click="closePrintPreview" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <h3 class="font-bold text-slate-800 dark:text-white">Pratinjau Surat: {{ $selectedPlacement->company_name }}</h3>
                </div>
                <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm transition-colors font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Surat
                </button>
            </div>

            <div class="flex-1 py-8 print:py-0">
                <!-- A4 Paper Container -->
                <div class="bg-white w-full max-w-[210mm] min-h-[297mm] mx-auto p-[20mm] shadow-lg print:shadow-none print:p-0 text-black font-serif relative">
                    <!-- KOP SURAT -->
                    <div class="flex border-b-[3px] border-black pb-4 mb-1">
                        <div class="w-24 shrink-0 flex items-center justify-center">
                            <!-- Placeholder logo (can be customized by user later) -->
                            <div class="w-20 h-20 bg-slate-200 rounded-full flex items-center justify-center border border-slate-300 print:border-black">
                                <span class="text-[10px] font-sans text-center text-slate-500 leading-tight">LOGO<br>SEKOLAH</span>
                            </div>
                        </div>
                        <div class="flex-1 text-center pr-24 flex flex-col justify-center">
                            <h2 class="text-lg font-bold leading-tight">YAYASAN PENDIDIKAN AL MABRUR</h2>
                            <h1 class="text-2xl font-bold leading-tight uppercase tracking-wider">SMK AL MABRUR PEJAWARAN</h1>
                            <p class="text-[13px] mt-1.5">Jl. Raya Pejawaran Km. 05, Banjarnegara, Jawa Tengah</p>
                            <p class="text-[12px]">Email: smkalmabrur@example.com | Telp: (0286) 123456</p>
                        </div>
                    </div>
                    <div class="border-b border-black mb-8"></div>

                    <!-- Nomor Surat dll -->
                    <div class="flex justify-between text-[15px] mb-10">
                        <div>
                            <table class="border-none">
                                <tr><td class="pr-6 py-0.5 align-top">Nomor</td><td class="align-top">:</td><td class="pl-2">... /SMK.AL/PKL/{{ date('Y') }}</td></tr>
                                <tr><td class="pr-6 py-0.5 align-top">Lampiran</td><td class="align-top">:</td><td class="pl-2">1 (Satu) Berkas</td></tr>
                                <tr><td class="pr-6 py-0.5 align-top">Hal</td><td class="align-top">:</td><td class="pl-2"><b>Permohonan Praktik Kerja Lapangan (PKL)</b></td></tr>
                            </table>
                        </div>
                        <div>
                            <p>Pejawaran, {{ date('d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Tujuan Surat -->
                    <div class="text-[15px] mb-8 leading-relaxed">
                        <p>Kepada Yth.</p>
                        <p><b>Pimpinan {{ $selectedPlacement->company_name }}</b></p>
                        <p>di Tempat</p>
                    </div>

                    <!-- Isi Surat -->
                    <div class="text-[15px] text-justify mb-6 space-y-3 leading-relaxed">
                        <p>Dengan hormat,</p>
                        <p class="indent-10">Dalam rangka pelaksanaan kurikulum Sekolah Menengah Kejuruan (SMK) Al Mabrur Pejawaran, di mana setiap siswa diwajibkan untuk melaksanakan Praktik Kerja Lapangan (PKL) guna mengaplikasikan teori yang telah dipelajari di sekolah ke dalam dunia usaha/dunia industri secara langsung.</p>
                        <p class="indent-10">Sehubungan dengan hal tersebut, kami memohon kesediaan Bapak/Ibu untuk dapat menerima siswa/siswi kami melaksanakan kegiatan PKL di instansi/perusahaan yang Bapak/Ibu pimpin. Adapun kegiatan PKL ini direncanakan akan berlangsung pada:</p>
                        
                        <table class="ml-10 mb-4 mt-2">
                            <tr>
                                <td class="py-1.5 w-36">Tanggal Mulai</td>
                                <td class="py-1.5 px-3">:</td>
                                <td class="py-1.5 font-bold">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 w-36">Tanggal Selesai</td>
                                <td class="py-1.5 px-3">:</td>
                                <td class="py-1.5 font-bold">{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </table>

                        <p class="indent-10">Adapun daftar nama siswa peserta PKL dan guru pembimbing yang ditugaskan pada instansi Bapak/Ibu adalah sebagai berikut:</p>
                    </div>

                    <!-- Tabel Siswa -->
                    <div class="mb-8 pl-10 pr-4">
                        <table class="w-full border-collapse border border-black text-[15px] text-left">
                            <thead>
                                <tr class="bg-gray-100 print:bg-transparent">
                                    <th class="border border-black px-4 py-2 w-12 text-center">No</th>
                                    <th class="border border-black px-4 py-2">Nama Siswa</th>
                                    <th class="border border-black px-4 py-2 w-48">NIS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedPlacement->students as $index => $student)
                                    <tr>
                                        <td class="border border-black px-4 py-2 text-center">{{ $index + 1 }}</td>
                                        <td class="border border-black px-4 py-2 font-bold">{{ $student->name }}</td>
                                        <td class="border border-black px-4 py-2 font-mono text-sm">{{ $student->nis }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-[15px] mb-12">
                        <p class="mb-2"><b>Guru Pembimbing:</b></p>
                        <p class="ml-10 flex items-center gap-3">
                            <span class="w-16">Nama</span> <span>:</span> <span class="font-bold">{{ $selectedPlacement->teacher->name }}</span>
                        </p>
                        <p class="ml-10 flex items-center gap-3 mt-1">
                            <span class="w-16">NIP</span> <span>:</span> <span>{{ $selectedPlacement->teacher->nip ?: '-' }}</span>
                        </p>
                    </div>

                    <div class="text-[15px] text-justify mb-16 leading-relaxed">
                        <p class="indent-10">Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerja sama yang baik dari Bapak/Ibu, kami ucapkan terima kasih.</p>
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="flex justify-end text-[15px] text-center mt-12 pr-12">
                        <div>
                            <p class="mb-24">Kepala SMK Al Mabrur Pejawaran,</p>
                            <p class="font-bold underline uppercase tracking-wide">{{ $headmasterName ?: '..........................................' }}</p>
                            <p class="mt-1">NIP. {{ $headmasterNip ?: '................................' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts::app>
