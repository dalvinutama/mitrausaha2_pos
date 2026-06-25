<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">
        
        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')
            
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 text-gray-800 dark:text-gray-200 transition-colors duration-300">
                
                {{-- HEADER PAGE --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white shadow-lg shadow-yellow-500/30">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-2xl text-gray-800 dark:text-gray-100 leading-tight tracking-wide">
                                {{ __('Daftar Hutang & Jatuh Tempo') }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-0.5">Kelola riwayat pembayaran dan cicilan tagihan ke supplier</p>
                        </div>
                    </div>
                </div>

                <div class="w-full space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 p-4 rounded-xl shadow-sm flex items-center gap-3 animate-[dropIn_0.3s_ease-out]">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div>
                        <p class="font-bold text-sm">Berhasil!</p>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm flex items-center gap-3 animate-[dropIn_0.3s_ease-out]">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <div>
                        <p class="font-bold text-sm">Gagal!</p>
                        <p class="text-xs">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

                {{-- TAB NAVIGATION --}}
                <div class="flex gap-2 mb-6 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700 w-fit shadow-sm relative z-10 transition-colors">
                    <button type="button" onclick="switchTab('aktif')" id="btnTab-aktif" class="px-5 py-2 rounded-lg text-sm font-bold transition-all bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm border border-blue-100 dark:border-blue-800/50">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Hutang Aktif
                    </button>
                    <button type="button" onclick="switchTab('lunas')" id="btnTab-lunas" class="px-5 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 border border-transparent">
                        <i class="fas fa-check-circle mr-1"></i> Riwayat Lunas
                    </button>
                </div>

                <div id="tab-aktif" class="tab-content block animate-[dropIn_0.3s_ease-out]">
                    {{-- TRANSAKSI BELUM LUNAS TABLE --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative overflow-hidden">
                        <div class="bg-gray-100/50 dark:bg-gray-800/80 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                <i class="fas fa-clock-rotate-left text-yellow-500"></i> Transaksi Jatuh Tempo
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left border-collapse">
                                <thead class="text-[10px] text-gray-500 dark:text-gray-400 uppercase bg-gray-100/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 sticky top-0 transition-colors">
                                    <tr>
                                        <th class="px-5 py-3 font-bold tracking-wider">No Transaksi</th>
                                        <th class="px-5 py-3 font-bold tracking-wider">Supplier</th>
                                        <th class="px-5 py-3 text-center font-bold tracking-wider">Dibuat / Tempo</th>
                                        <th class="px-5 py-3 text-right font-bold tracking-wider">Total Hutang</th>
                                        <th class="px-5 py-3 text-right font-bold tracking-wider">Sudah Dibayar</th>
                                        <th class="px-5 py-3 text-right font-bold tracking-wider">Sisa Hutang</th>
                                        <th class="px-5 py-3 text-center font-bold tracking-wider">Status</th>
                                        <th class="px-5 py-3 text-center font-bold tracking-wider w-32">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800 transition-colors">
                                    @forelse($hutangAktif as $h)
                                        @php
                                            $sudah_bayar = $h->payments->sum('nominal');
                                            $sisa_hutang = $h->total_nilai - $sudah_bayar;
                                            
                                            // Ekstrak tanggal tempo dari catatan
                                            $tgl_tempo = '-';
                                            if (preg_match('/Jatuh Tempo:\s*([\d\/]+)/i', $h->catatan, $matches)) {
                                                $tgl_tempo = $matches[1];
                                            }
                                        @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                        <td class="px-5 py-4">
                                            <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-800 px-2.5 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">{{ $h->no_transaksi }}</span>
                                        </td>
                                        <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-200">{{ $h->supplier->nama_supplier ?? '-' }}</td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-600 dark:text-gray-400">
                                                    <i class="fas fa-calendar-plus text-gray-400"></i> {{ $h->created_at->format('d M Y, H:i') }}
                                                </div>
                                                <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-red-500 dark:text-red-400 bg-white dark:bg-gray-800/50 border border-red-100 dark:border-red-800/50 px-2 py-0.5 rounded w-fit">
                                                    <i class="far fa-clock text-red-400"></i> {{ $tgl_tempo }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-right font-bold text-gray-700 dark:text-gray-300">Rp {{ number_format($h->total_nilai, 0, ',', '.') }}</td>
                                        <td class="px-5 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($sudah_bayar, 0, ',', '.') }}</td>
                                        <td class="px-5 py-4 text-right font-black text-[#D00000] dark:text-red-500 text-base">Rp {{ number_format($sisa_hutang, 0, ',', '.') }}</td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1.5 rounded-md border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50 shadow-sm"><i class="fas fa-exclamation-circle mr-1"></i>Belum Lunas</span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="openModalBayar('{{ $h->id }}', '{{ $h->no_transaksi }}', {{ $sisa_hutang }})" class="flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-500 text-emerald-600 dark:text-emerald-400 hover:text-white border border-emerald-200 dark:border-emerald-800 px-3 py-1.5 rounded-xl transition-all shadow-sm text-xs font-bold disabled:opacity-50 disabled:cursor-not-allowed" {{ $sisa_hutang <= 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-hand-holding-dollar"></i> Bayar
                                                </button>
                                                <button onclick="openModalRiwayat('riwayat-{{ $h->id }}')" class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 px-3 py-1.5 rounded-xl transition-all shadow-sm text-xs font-bold">
                                                    <i class="fas fa-history"></i> Riwayat
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-box-open text-3xl text-gray-400"></i>
                                                </div>
                                                <p class="font-medium text-gray-500 dark:text-gray-400">Tidak ada data transaksi jatuh tempo aktif.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="tab-lunas" class="tab-content hidden animate-[dropIn_0.3s_ease-out]">
                    {{-- TRANSAKSI LUNAS TABLE --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative overflow-hidden">
                        <div class="bg-gray-100/50 dark:bg-gray-800/80 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                <i class="fas fa-check-circle text-emerald-500"></i> Riwayat Transaksi Lunas
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left border-collapse">
                                <thead class="text-[10px] text-gray-500 dark:text-gray-400 uppercase bg-gray-100/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 sticky top-0 transition-colors">
                                    <tr>
                                        <th class="px-5 py-3 font-bold tracking-wider">No Transaksi</th>
                                        <th class="px-5 py-3 font-bold tracking-wider">Supplier</th>
                                        <th class="px-5 py-3 text-center font-bold tracking-wider">Dibuat / Tempo</th>
                                        <th class="px-5 py-3 text-right font-bold tracking-wider">Total Hutang</th>
                                        <th class="px-5 py-3 text-right font-bold tracking-wider">Sudah Dibayar</th>
                                        <th class="px-5 py-3 text-right font-bold tracking-wider">Sisa Hutang</th>
                                        <th class="px-5 py-3 text-center font-bold tracking-wider">Status</th>
                                        <th class="px-5 py-3 text-center font-bold tracking-wider w-32">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800 transition-colors">
                                    @forelse($hutangLunas as $h)
                                        @php
                                            $sudah_bayar = $h->payments->sum('nominal');
                                            $sisa_hutang = $h->total_nilai - $sudah_bayar;
                                            
                                            // Ekstrak tanggal tempo dari catatan
                                            $tgl_tempo = '-';
                                            if (preg_match('/Jatuh Tempo:\s*([\d\/]+)/i', $h->catatan, $matches)) {
                                                $tgl_tempo = $matches[1];
                                            }
                                        @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                        <td class="px-5 py-4">
                                            <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-800 px-2.5 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">{{ $h->no_transaksi }}</span>
                                        </td>
                                        <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-200">{{ $h->supplier->nama_supplier ?? '-' }}</td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-600 dark:text-gray-400">
                                                    <i class="fas fa-calendar-plus text-gray-400"></i> {{ $h->created_at->format('d M Y, H:i') }}
                                                </div>
                                                <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-500 dark:text-gray-500 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 px-2 py-0.5 rounded w-fit">
                                                    <i class="far fa-clock text-gray-400"></i> {{ $tgl_tempo }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-right font-bold text-gray-700 dark:text-gray-300">Rp {{ number_format($h->total_nilai, 0, ',', '.') }}</td>
                                        <td class="px-5 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($sudah_bayar, 0, ',', '.') }}</td>
                                        <td class="px-5 py-4 text-right font-black text-gray-400 dark:text-gray-600 text-base">Rp 0</td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-2.5 py-1.5 rounded-md border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50 shadow-sm"><i class="fas fa-check-circle mr-1"></i>Lunas</span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="openModalRiwayat('riwayat-{{ $h->id }}')" class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 px-3 py-1.5 rounded-xl transition-all shadow-sm text-xs font-bold">
                                                    <i class="fas fa-history"></i> Riwayat
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check-double text-3xl text-emerald-400"></i>
                                                </div>
                                                <p class="font-medium text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi lunas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

        </div>
    </div>

    {{-- MODAL RIWAYAT (Dipindah ke luar loop agar tidak terpotong (clipped) oleh tabel responsive) --}}
    @foreach($hutangAktif->concat($hutangLunas) as $h)
        <div id="riwayat-{{ $h->id }}" onclick="if(event.target === this) closeModalRiwayat('riwayat-{{ $h->id }}')" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
            <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh] text-left">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                    <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-history text-blue-500 mr-2"></i> Riwayat Pembayaran: {{ $h->no_transaksi }}</h3>
                    <button onclick="closeModalRiwayat('riwayat-{{ $h->id }}')" class="text-gray-400 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                    <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Metode</th>
                                <th class="px-5 py-3 text-right">Nominal</th>
                                <th class="px-5 py-3 text-center">Bukti</th>
                                @if(Auth::user()->role === 'owner')
                                <th class="px-5 py-3 text-center">Aksi (Void)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($h->payments as $p)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3 font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <span class="font-bold text-xs uppercase bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded">{{ $p->metode_pembayaran }}</span>
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($p->bukti_pembayaran)
                                            <a href="{{ asset($p->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-500 hover:text-blue-700 font-bold bg-blue-50 dark:bg-blue-900/20 px-3 py-1.5 rounded-lg transition-colors border border-blue-200 dark:border-blue-800/50">
                                                <i class="fas fa-image"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Tanpa Bukti</span>
                                        @endif
                                        <a href="{{ route('hutang.payment.print', $p->id) }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-lg transition-colors border border-emerald-200 dark:border-emerald-800/50">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    </div>
                                </td>
                                @if(Auth::user()->role === 'owner')
                                <td class="px-5 py-3 text-center">
                                    <form action="{{ route('hutang.batal', $p->id) }}" method="POST" onsubmit="return confirm('PERINGATAN!\nAnda yakin ingin menghapus/void riwayat pembayaran ini? Saldo dan status akan disesuaikan secara otomatis.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 w-8 h-8 rounded-full flex items-center justify-center transition-colors border border-transparent hover:border-red-200 dark:hover:border-red-800/50 mx-auto">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 italic">Belum ada riwayat pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL BAYAR CICILAN --}}
    <div id="modalBayar" onclick="if(event.target === this) closeModalBayar()" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-hand-holding-dollar text-blue-500 mr-2"></i> Pembayaran Hutang</h3>
                <button onclick="closeModalBayar()" class="text-gray-400 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            </div>
            
            <form id="formBayar" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div class="bg-red-50 dark:bg-red-900/20 p-5 rounded-2xl border border-red-200 dark:border-red-800/50 flex flex-col items-center justify-center shadow-sm">
                    <p class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-[0.2em] mb-1">Total Sisa Hutang</p>
                    <p class="text-3xl font-black text-red-600 dark:text-red-400" id="modalSisaHutangTxt">Rp 0</p>
                    <p class="text-xs text-red-500/80 mt-1 font-medium">Nota: <span id="modalNoTrans" class="font-mono"></span></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5 uppercase tracking-wide text-center">Nominal Bayar <span class="text-red-500">*</span></label>
                    <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 transition-all shadow-inner dark:shadow-none">
                        <button type="button" onclick="adjustNominal(-1000)" class="w-14 h-14 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus"></i></button>
                        <div class="relative flex-1 flex items-center h-full">
                            <span class="absolute left-4 font-bold text-gray-400">Rp</span>
                            <input type="hidden" name="nominal" id="modalNominalHidden">
                            <input type="text" id="modalNominalInput" onkeyup="formatRupiah(this)" class="w-full bg-transparent border-none text-blue-700 dark:text-blue-400 font-black text-xl text-center pl-12 pr-4 py-3 focus:ring-0 appearance-none" required>
                        </div>
                        <button type="button" onclick="adjustNominal(1000)" class="w-14 h-14 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5 uppercase tracking-wide">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-wallet"></i></span>
                        <select name="metode_pembayaran" id="modalMetode" class="w-full pl-10 pr-4 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white rounded-xl py-3 font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all appearance-none" onchange="toggleModalBukti()" required>
                            <option value="cash">Uang Fisik (Cash)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"><i class="fas fa-chevron-down text-xs"></i></span>
                    </div>
                </div>

                <div id="modalBuktiContainer" class="hidden animate-[dropIn_0.3s_ease-out]">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5 uppercase tracking-wide">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative cursor-pointer group min-h-[120px] flex items-center justify-center overflow-hidden">
                        <input type="file" name="bukti_pembayaran" id="modalBuktiInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewImage(this)">
                        
                        <div id="modalBuktiPlaceholder" class="flex flex-col items-center justify-center space-y-2 text-center pointer-events-none z-10 transition-opacity duration-300">
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-400 group-hover:text-blue-500 shadow-sm transition-colors">
                                <i class="fas fa-cloud-arrow-up"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400">Klik atau seret file ke sini</p>
                            <p class="text-[10px] text-gray-400">Format: JPG, PNG, JPEG (Maks. 10MB)</p>
                        </div>

                        <img id="modalBuktiPreview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-500/30">
                        <i class="fas fa-paper-plane"></i> Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalBayar(id, noTrans, sisa) {
            document.getElementById('modalNoTrans').innerText = noTrans;
            document.getElementById('modalSisaHutangTxt').innerText = 'Rp ' + sisa.toLocaleString('id-ID');
            document.getElementById('modalNominalHidden').value = sisa;
            document.getElementById('modalNominalInput').value = sisa.toLocaleString('id-ID');
            document.getElementById('modalNominalInput').setAttribute('data-max', sisa);
            document.getElementById('formBayar').action = '/hutang/' + id + '/bayar';
            
            // Reset Preview Image
            document.getElementById('modalBuktiInput').value = '';
            document.getElementById('modalBuktiPreview').src = '#';
            document.getElementById('modalBuktiPreview').classList.add('hidden');
            document.getElementById('modalBuktiPlaceholder').classList.remove('opacity-0');

            document.getElementById('modalBayar').classList.remove('hidden');
            toggleModalBukti();
        }

        function closeModalBayar() {
            document.getElementById('modalBayar').classList.add('hidden');
        }

        function toggleModalBukti() {
            const val = document.getElementById('modalMetode').value;
            const container = document.getElementById('modalBuktiContainer');
            const input = document.getElementById('modalBuktiInput');
            if(val === 'transfer') {
                container.classList.remove('hidden');
                input.required = true;
            } else {
                container.classList.add('hidden');
                input.required = false;
            }
        }

        function openModalRiwayat(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModalRiwayat(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function previewImage(input) {
            const preview = document.getElementById('modalBuktiPreview');
            const placeholder = document.getElementById('modalBuktiPlaceholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('opacity-0');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('opacity-0');
            }
        }

        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            input.value = rupiah;
            
            // Update hidden
            document.getElementById('modalNominalHidden').value = value ? value : 0;
            
            // Mencegah melebihi sisa hutang saat diketik manual
            let max = parseInt(input.getAttribute('data-max')) || Infinity;
            if (parseInt(value) > max) {
                input.value = max.toLocaleString('id-ID');
                document.getElementById('modalNominalHidden').value = max;
            }
        }

        function adjustNominal(delta) {
            const input = document.getElementById('modalNominalInput');
            const hidden = document.getElementById('modalNominalHidden');
            let val = parseInt(hidden.value) || 0;
            let max = parseInt(input.getAttribute('data-max')) || Infinity;
            
            val += delta;
            
            if (val < 0) val = 0;
            if (val > max) val = max;
            
            hidden.value = val;
            input.value = val.toLocaleString('id-ID');
        }
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');

            const btnAktif = document.getElementById('btnTab-aktif');
            const btnLunas = document.getElementById('btnTab-lunas');

            if (tab === 'aktif') {
                btnAktif.className = "px-5 py-2 rounded-lg text-sm font-bold transition-all bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm border border-blue-100 dark:border-blue-800/50";
                btnLunas.className = "px-5 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 border border-transparent";
            } else {
                btnLunas.className = "px-5 py-2 rounded-lg text-sm font-bold transition-all bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 shadow-sm border border-emerald-100 dark:border-emerald-800/50";
                btnAktif.className = "px-5 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 border border-transparent";
            }
        }
    </script>
            </div>
        </div>
    </div>
</x-app-layout>
