<div>
    <div class="flex h-full w-full flex-1 flex-col gap-8 py-8 px-4 md:px-8 max-w-[1400px] mx-auto print:hidden">
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Cetak Surat PKL</h2>
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
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Logo Kop Surat (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    @if($logoPath)
                                        <img src="{{ Storage::url($logoPath) }}" class="h-12 w-12 object-contain bg-white rounded border border-slate-200">
                                        <button wire:click="removeLogo" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                    @else
                                        <input type="file" wire:model="logoUpload" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/30 dark:file:text-violet-400">
                                    @endif
                                </div>
                                <div wire:loading wire:target="logoUpload" class="text-xs text-violet-600 mt-1">Mengunggah...</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jenis Surat</label>
                                <select wire:model.live="letterType" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                                    <option value="permohonan">Surat Permohonan Izin PKL</option>
                                    <option value="pengantar">Surat Pengantar / Penyerahan Siswa</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Surat</label>
                                    <input type="text" wire:model.live="letterNumber" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Nomor Surat">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Lampiran</label>
                                    <input type="text" wire:model.live="attachment" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Lampiran">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kepala Sekolah</label>
                                <input type="text" wire:model.live="headmasterName" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="Nama Kepala Sekolah">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">NIP Kepala Sekolah</label>
                                <input type="text" wire:model.live="headmasterNip" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring focus:ring-violet-500/20 dark:bg-slate-950 dark:border-slate-800 dark:text-white" placeholder="NIP">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanda Tangan Kepala Sekolah (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    @if($signaturePath)
                                        <img src="{{ Storage::url($signaturePath) }}" class="h-12 object-contain bg-white rounded border border-slate-200">
                                        <button wire:click="removeSignature" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                    @else
                                        <input type="file" wire:model="signatureUpload" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/30 dark:file:text-violet-400">
                                    @endif
                                </div>
                                <div wire:loading wire:target="signatureUpload" class="text-xs text-violet-600 mt-1">Mengunggah...</div>
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
                        <div class="p-4 border-b border-slate-200/60 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                            <span class="text-xs font-semibold text-slate-500 uppercase">Daftar Tempat PKL & Kelompok</span>
                            @if($placements->filter(function($p) use ($letterType) { return $p->students->count() > 0 && ($letterType === 'permohonan' || $p->teacher); })->count() > 0)
                                <button wire:click="selectAllToPrint" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-2 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Semua Kelompok (Batch Print)
                                </button>
                            @endif
                        </div>
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
                                            @if($placement->students->count() > 0 && ($letterType === 'permohonan' || $placement->teacher))
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
    @if(count($placementsToPrint) > 0)
        <style>
            @media print {
                body * { visibility: hidden; }
                .print-wrapper, .print-wrapper * { visibility: visible; }
                .print-wrapper {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
                @page { size: A4 portrait; margin: 0; }
            }
        </style>
        <div class="fixed inset-0 z-[100] bg-slate-100 dark:bg-slate-900 overflow-y-auto print:bg-white print:static print:overflow-visible flex flex-col print-wrapper">
            <div class="sticky top-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 p-4 flex justify-between items-center shadow-sm print:hidden z-10">
                <div class="flex items-center gap-4">
                    <button wire:click="closePrintPreview" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors bg-slate-100 dark:bg-slate-800 p-2 rounded-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <h3 class="font-bold text-slate-800 dark:text-white">Pratinjau Surat: {{ count($placementsToPrint) > 1 ? count($placementsToPrint).' Surat Sekaligus (Batch)' : $placementsToPrint->first()->company_name }}</h3>
                </div>
                <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 font-semibold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Surat
                </button>
            </div>

            <div class="flex-1 py-8 print:py-0">
                @foreach($placementsToPrint as $selectedPlacement)
                    <!-- A4 Paper Container -->
                    <div style="font-family: 'Times New Roman', Times, serif;" class="bg-white w-full max-w-[210mm] min-h-[297mm] mx-auto pt-[10mm] pr-[10mm] pb-[10mm] pl-[20mm] shadow-lg print:shadow-none text-black relative box-border print:break-after-page mb-8 print:mb-0">
                        <!-- KOP SURAT -->
                        <div class="flex pb-2 mb-1">
                            <div class="w-28 shrink-0 flex items-center justify-center">
                                @if($logoPath)
                                    <img src="{{ Storage::url($logoPath) }}" class="w-20 h-20 object-contain">
                                @else
                                    <div class="w-20 h-20 bg-slate-200 flex items-center justify-center border border-slate-300 print:border-black">
                                        <span class="text-[10px] text-center text-slate-500 leading-tight">LOGO<br>SEKOLAH</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center pr-6 flex flex-col justify-center">
                                <h2 class="text-[14px] leading-tight mb-0.5 text-black">YAYASAN PERSAUDARAAN HAJI AL MABRUR (YPHA)</h2>
                                <h1 class="text-[22px] font-bold leading-tight uppercase tracking-wide text-black">SMK AL MABRUR PEJAWARAN</h1>
                                <p class="text-[13px] mt-1 text-black">Pon Pes Al Mabrur Rt 13 Rw 03 Gembol, Pejawaran, Banjarnegara 53454</p>
                                <p class="text-[13px] text-black mt-0.5">Website: <span class="text-blue-700 underline">https://smkalmabrur.sch.id/</span> | email: smkalmabrur@gmail.com</p>
                            </div>
                        </div>
                        <div class="border-b-[4px] border-black mb-1"></div>
                        <div class="border-b border-black mb-4"></div>

                        <!-- Nomor Surat dll -->
                        <div class="flex justify-between text-[14px] mb-4">
                            <div>
                                <table class="border-none">
                                    <tr><td class="pr-4 py-0.5 align-top">Nomor</td><td class="align-top">:</td><td class="pl-2">{{ $letterNumber }}</td></tr>
                                    <tr><td class="pr-4 py-0.5 align-top">Lampiran</td><td class="align-top">:</td><td class="pl-2">{{ $attachment }}</td></tr>
                                    <tr><td class="pr-4 py-0.5 align-top">Hal</td><td class="align-top">:</td><td class="pl-2"><b>{{ $letterType === 'permohonan' ? 'Permohonan Praktik Kerja Lapangan (PKL)' : 'Penyerahan Siswa Praktik Kerja Lapangan (PKL)' }}</b></td></tr>
                                </table>
                            </div>
                            <div>
                                <p>Pejawaran, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <!-- Tujuan Surat -->
                        <div class="text-[14px] mb-4 leading-relaxed">
                            <p>Kepada Yth.</p>
                            <p><b>Pimpinan {{ $selectedPlacement->company_name }}</b></p>
                            <p>di Tempat</p>
                        </div>

                        <!-- Isi Surat -->
                        <div class="text-[14px] text-justify mb-3 space-y-1.5 leading-relaxed">
                            <p>Dengan hormat,</p>
                            <p class="indent-8">Dalam rangka pelaksanaan kurikulum Sekolah Menengah Kejuruan (SMK) Al Mabrur Pejawaran, di mana setiap siswa diwajibkan untuk melaksanakan Praktik Kerja Lapangan (PKL) guna mengaplikasikan teori yang telah dipelajari di sekolah ke dalam dunia usaha/dunia industri secara langsung.</p>
                            @if($letterType === 'permohonan')
                                <p class="indent-8">Sehubungan dengan hal tersebut, kami memohon kesediaan Bapak/Ibu untuk dapat menerima siswa/siswi kami melaksanakan kegiatan PKL di instansi/perusahaan yang Bapak/Ibu pimpin. Adapun kegiatan PKL ini direncanakan akan berlangsung pada:</p>
                            @else
                                <p class="indent-8">Sehubungan dengan hal tersebut, bersama surat ini kami mengantarkan dan menyerahkan siswa/siswi kami untuk melaksanakan kegiatan PKL di instansi/perusahaan yang Bapak/Ibu pimpin. Adapun kegiatan PKL ini dilaksanakan pada:</p>
                            @endif
                            
                            <table class="ml-8 mb-1.5 mt-1">
                                <tr>
                                    <td class="py-0.5 w-32">Tanggal Mulai</td>
                                    <td class="py-0.5 px-2">:</td>
                                    <td class="py-0.5 font-bold">{{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 w-32">Tanggal Selesai</td>
                                    <td class="py-0.5 px-2">:</td>
                                    <td class="py-0.5 font-bold">{{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</td>
                                </tr>
                            </table>

                            @if($letterType === 'permohonan')
                                <p class="indent-8">Adapun daftar nama siswa calon peserta PKL pada instansi Bapak/Ibu adalah sebagai berikut:</p>
                            @else
                                <p class="indent-8">Adapun daftar nama siswa peserta PKL dan guru pembimbing yang ditugaskan pada instansi Bapak/Ibu adalah sebagai berikut:</p>
                            @endif
                        </div>

                        <!-- Tabel Siswa -->
                        <div class="mb-4 pl-8 pr-2">
                            <table class="w-full border-collapse border border-black text-[14px] text-left">
                                <thead>
                                    <tr class="bg-gray-100 print:bg-transparent">
                                        <th class="border border-black px-3 py-1.5 w-10 text-center">No</th>
                                        <th class="border border-black px-3 py-1.5">Nama Siswa</th>
                                        <th class="border border-black px-3 py-1.5 w-40">NIS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedPlacement->students as $index => $student)
                                        <tr>
                                            <td class="border border-black px-3 py-1 text-center">{{ $index + 1 }}</td>
                                            <td class="border border-black px-3 py-1 font-bold">{{ $student->name }}</td>
                                            <td class="border border-black px-3 py-1 font-mono text-sm">{{ $student->nis }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($letterType === 'pengantar')
                            <div class="text-[14px] mb-4">
                                <p class="mb-1"><b>Guru Pembimbing:</b></p>
                                <p class="ml-8 flex items-center gap-2">
                                    <span class="w-14">Nama</span> <span>:</span> <span class="font-bold">{{ $selectedPlacement->teacher->name }}</span>
                                </p>
                                <p class="ml-8 flex items-center gap-2 mt-0.5">
                                    <span class="w-14">NIP</span> <span>:</span> <span>{{ $selectedPlacement->teacher->nip ?: '-' }}</span>
                                </p>
                            </div>
                        @endif

                        <div class="text-[14px] text-justify mb-6 leading-relaxed">
                            @if($letterType === 'permohonan')
                                <p class="indent-8">Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerja sama yang baik dari Bapak/Ibu, kami ucapkan terima kasih.</p>
                            @else
                                <p class="indent-8">Demikian surat pengantar/penyerahan ini kami sampaikan. Atas perhatian dan kerja sama yang baik dari Bapak/Ibu, kami ucapkan terima kasih.</p>
                            @endif
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="flex justify-end text-[14px] text-center mt-6 pr-8">
                            <div class="flex flex-col items-center">
                                <p class="mb-1">Kepala SMK Al Mabrur Pejawaran,</p>
                                @if($signaturePath)
                                    <img src="{{ Storage::url($signaturePath) }}" class="h-16 object-contain mix-blend-darken my-2">
                                @else
                                    <div class="h-20"></div>
                                @endif
                                <p class="font-bold underline uppercase tracking-wide">{{ $headmasterName ?: '..........................................' }}</p>
                                <p class="mt-1">NIP. {{ $headmasterNip ?: '................................' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
