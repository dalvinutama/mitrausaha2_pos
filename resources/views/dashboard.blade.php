<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
        @include('layouts.header')
            
            {{-- PERBAIKAN BACKGROUND: bg-gray-100 agar lebih kontras dengan card bg-white --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 text-gray-800 dark:text-gray-200 transition-colors duration-300">
                
                {{-- HEADER DASHBOARD & ACTION BUTTONS --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors" title="{{ __('to_dashboard') }}">
                                <i class="fas fa-home text-sm"></i>
                            </a>
                            <span class="text-[#D00000] dark:text-red-400">{{ __('control_center') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">Dashboard Operasional</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('dashboard_desc') }}</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ Route::has('stok_masuk') ? route('stok_masuk') : '#' }}" class="bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-500 hover:text-white dark:bg-emerald-900/30 dark:border-emerald-800/50 dark:text-emerald-400 dark:hover:bg-emerald-500 dark:hover:text-white font-bold py-3 px-5 rounded-2xl shadow-sm card-shadow-emerald transition-all duration-300 transform hover:-translate-y-1.5 flex items-center gap-2 text-sm">
                            <i class="fas fa-box-open text-lg transition-transform duration-300"></i>
                            <span class="hidden sm:inline">Stok Masuk</span>
                        </a>
                        
                        <a href="{{ Route::has('stok_keluar') ? route('stok_keluar') : '#' }}" class="bg-[#D00000]/10 text-[#D00000] border border-[#D00000]/20 hover:bg-[#D00000] hover:text-white dark:bg-red-900/30 dark:border-red-800/50 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white font-bold py-3 px-5 rounded-2xl shadow-sm card-shadow-red transition-all duration-300 transform hover:-translate-y-1.5 flex items-center gap-2 text-sm">
                            <i class="fas fa-truck-fast text-lg transition-transform duration-300"></i>
                            <span class="hidden sm:inline">Stok Keluar</span>
                        </a>
                    </div>
                </div>

                {{-- TAB NAVIGATION --}}
                <div class="flex items-center gap-1 bg-white dark:bg-gray-800 p-1.5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm w-full md:w-fit overflow-x-auto custom-scrollbar">
                    <button onclick="switchDashboardTab('operational')" id="tabOperational" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 transition-all shadow-sm">
                        <i class="fas fa-chart-pie"></i> {{ app()->getLocale() == 'en' ? 'Operational Summary' : 'Ringkasan Operasional' }}
                    </button>
                    <button onclick="switchDashboardTab('analytics')" id="tabAnalytics" style="display: none !important;" class="flex-1 md:flex-none items-center justify-center gap-2 px-6 py-2.5 rounded-lg text-sm font-bold text-gray-500 hover:text-indigo-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-brain"></i> {{ app()->getLocale() == 'en' ? 'AI Expert Analytics' : 'Analisis Pakar AI' }}
                    </button>
                </div>

                <div id="contentOperational" class="space-y-6 animate-fade-in">
                    {{-- NOTIFIKASI SUCCESS/ERROR --}}
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm font-bold text-sm transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm font-bold text-sm transition-colors">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                {{-- WIDGET 4 KOTAK METRIK UTAMA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    
                    <div onclick="openDetailModal('modalAset')" class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl border-0 shadow-lg shadow-emerald-500/30 text-white flex flex-col justify-between cursor-pointer hover:shadow-2xl hover:shadow-emerald-500/50 transition-all duration-300 transform hover:-translate-y-1.5 group relative h-36 z-10 hover:z-50">
                        <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                            <div class="absolute -right-4 -bottom-4 z-0 opacity-20 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none">
                                <i class="fas fa-vault text-[120px]"></i>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 z-20" onclick="event.stopPropagation()">
                            <i class="fas fa-question-circle text-white/70 hover:text-white cursor-pointer transition-colors text-base peer"></i>
                            <div class="absolute bottom-full left-0 sm:left-auto sm:right-0 mb-2 w-max max-w-[80vw] sm:max-w-[250px] sm:w-56 p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-lg font-medium leading-tight z-50">
                                Total nilai estimasi aset dari stok yang tersedia di gudang saat ini.
                                <div class="absolute top-full right-1 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                            </div>
                        </div>
                        <div class="relative z-10 mt-auto">
                            <p class="text-[11px] font-bold text-white/80 uppercase tracking-widest mb-1 group-hover:text-white transition-colors">{{ __('asset_value') }}</p>
                            <h3 class="text-3xl font-black text-white truncate drop-shadow-md">Rp {{ number_format($totalAset ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    
                    <div onclick="openDetailModal('modalHutang')" class="bg-gradient-to-br from-rose-500 to-red-600 p-6 rounded-2xl border-0 shadow-lg shadow-rose-500/30 text-white flex flex-col justify-between cursor-pointer hover:shadow-2xl hover:shadow-rose-500/50 transition-all duration-300 transform hover:-translate-y-1.5 group relative h-36 z-10 hover:z-50">
                        <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                            <div class="absolute -right-4 -bottom-4 z-0 opacity-20 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 pointer-events-none">
                                <i class="fas fa-file-invoice-dollar text-[120px]"></i>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 z-20" onclick="event.stopPropagation()">
                            <i class="fas fa-question-circle text-white/70 hover:text-white cursor-pointer transition-colors text-base peer"></i>
                            <div class="absolute bottom-full left-0 sm:left-auto sm:right-0 mb-2 w-max max-w-[80vw] sm:max-w-[250px] sm:w-56 p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-lg font-medium leading-tight z-50">
                                Total nilai tagihan dari pesanan ke supplier yang belum dilunasi.
                                <div class="absolute top-full right-1 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                            </div>
                        </div>
                        <div class="relative z-10 mt-auto">
                            <p class="text-[11px] font-bold text-white/80 uppercase tracking-widest mb-1 group-hover:text-white transition-colors">{{ __('vendor_debt') }}</p>
                            <h3 class="text-3xl font-black text-white truncate drop-shadow-md">Rp {{ number_format($hutangTempo ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    
                    <div onclick="openDetailModal('modalRusak')" class="bg-gradient-to-br from-amber-400 to-orange-500 p-6 rounded-2xl border-0 shadow-lg shadow-amber-500/30 text-white flex flex-col justify-between cursor-pointer hover:shadow-2xl hover:shadow-amber-500/50 transition-all duration-300 transform hover:-translate-y-1.5 group relative h-36 z-10 hover:z-50">
                        <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                            <div class="absolute -right-4 -bottom-4 z-0 opacity-20 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none">
                                <i class="fas fa-heart-crack text-[120px]"></i>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 z-20" onclick="event.stopPropagation()">
                            <i class="fas fa-question-circle text-white/70 hover:text-white cursor-pointer transition-colors text-base peer"></i>
                            <div class="absolute bottom-full left-0 sm:left-auto sm:right-0 mb-2 w-max max-w-[80vw] sm:max-w-[250px] sm:w-56 p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-lg font-medium leading-tight z-50">
                                Jumlah item material yang dikembalikan (retur) atau mengalami kerusakan fisik.
                                <div class="absolute top-full right-1 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                            </div>
                        </div>
                        <div class="relative z-10 mt-auto">
                            <p class="text-[11px] font-bold text-white/80 uppercase tracking-widest mb-1 group-hover:text-white transition-colors">{{ __('return_broken') }}</p>
                            <h3 class="text-3xl font-black text-white truncate drop-shadow-md">{{ $barangRusak ?? 0 }} <span class="text-sm font-semibold text-white/80">Item</span></h3>
                        </div>
                    </div>
                    
                    <div onclick="openDetailModal('modalPO')" class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-2xl border-0 shadow-lg shadow-blue-500/30 text-white flex flex-col justify-between cursor-pointer hover:shadow-2xl hover:shadow-blue-500/50 transition-all duration-300 transform hover:-translate-y-1.5 group relative h-36 z-10 hover:z-50">
                        <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                            <div class="absolute -right-4 -bottom-4 z-0 opacity-20 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 pointer-events-none">
                                <i class="fas fa-truck-ramp-box text-[120px]"></i>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 z-20" onclick="event.stopPropagation()">
                            <i class="fas fa-question-circle text-white/70 hover:text-white cursor-pointer transition-colors text-base peer"></i>
                            <div class="absolute bottom-full left-0 sm:left-auto sm:right-0 mb-2 w-max max-w-[80vw] sm:max-w-[250px] sm:w-56 p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-lg font-medium leading-tight z-50">
                                Jumlah pesanan aktif (Purchase Order) yang masih dalam proses pengiriman.
                                <div class="absolute top-full right-1 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                            </div>
                        </div>
                        <div class="relative z-10 mt-auto">
                            <p class="text-[11px] font-bold text-white/80 uppercase tracking-widest mb-1 group-hover:text-white transition-colors">{{ __('active_orders') }}</p>
                            <h3 class="text-3xl font-black text-white truncate drop-shadow-md">{{ $poAktif ?? 0 }} <span class="text-sm font-semibold text-white/80">PO</span></h3>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    
                    {{-- GRAFIK KIRI --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md xl:col-span-2 flex flex-col h-full min-h-[400px]">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-500"><i class="fas fa-chart-line text-lg"></i></div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                        {{ __('movement_trend') }}
                                        <div class="relative inline-block mt-0.5 group z-50">
                                            <i class="fas fa-question-circle text-gray-400 dark:text-gray-500 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer transition-colors text-xs peer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                                Grafik perbandingan jumlah barang yang masuk dan keluar berdasarkan periode waktu yang dipilih.
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                            </div>
                                        </div>
                                    </h3>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ __('movement_analysis') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                <select id="chartTimeRange" onchange="updateChartData()" class="flex-1 sm:flex-none text-xs font-bold text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 outline-none focus:border-indigo-500 cursor-pointer transition-colors">
                                    <option value="week">{{ __('this_week') }}</option>
                                    <option value="month">{{ __('this_month') }}</option>
                                    <option value="quarter">@if(app()->getLocale() == 'id') Triwulan (3 Bulan) @else Quarterly (3 Months) @endif</option>
                                    <option value="6months">@if(app()->getLocale() == 'id') 6 Bulan Terakhir @else Last 6 Months @endif</option>
                                    <option value="year">{{ __('this_year') }}</option>
                                    <option value="5years">@if(app()->getLocale() == 'id') 5 Tahun Terakhir @else Last 5 Years @endif</option>
                                    <option value="all">@if(app()->getLocale() == 'id') Semua Waktu @else All Time @endif</option>
                                </select>
                                
                                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-xl p-1 border border-gray-200 dark:border-gray-600">
                                    <button onclick="setChartType('line')" id="btnLineChart" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-500">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                    <button onclick="setChartType('bar')" id="btnBarChart" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent">
                                        <i class="fas fa-chart-bar"></i>
                                    </button>
                                </div>
                                
                                <button onclick="generateChartInsight()" class="hidden sm:flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-500 hover:text-white dark:bg-indigo-900/30 dark:border-indigo-800/50 dark:text-indigo-400 dark:hover:bg-indigo-500 dark:hover:text-white shadow-sm ml-1">
                                    <i class="fas fa-robot text-sm"></i> Analisis AI
                                </button>
                            </div>
                        </div>
                    
                        <div class="flex gap-4 text-xs font-bold mb-3 justify-end">
                            <span class="flex items-center gap-1.5 text-emerald-500"><i class="fas fa-square text-[10px]"></i> Barang Masuk</span>
                            <span class="flex items-center gap-1.5 text-[#D00000] dark:text-red-500"><i class="fas fa-square text-[10px]"></i> Barang Keluar</span>
                        </div>
                        <div class="flex-1 w-full relative min-h-0">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>

                    {{-- WIDGET KANAN --}}
                    <div class="space-y-6 flex flex-col h-full">
                        
                        {{-- Kategori Terlaris --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex-1 flex flex-col">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <i class="fas fa-fire text-orange-500"></i> {{ __('best_selling_category') }}
                                    <div class="relative inline-block mt-0.5 group z-50">
                                        <i class="fas fa-question-circle text-gray-400 dark:text-gray-500 hover:text-orange-500 dark:hover:text-orange-400 cursor-pointer transition-colors text-xs peer"></i>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                            Daftar kategori material yang paling banyak dikeluarkan dari gudang.
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                        </div>
                                    </div>
                                </h3>
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-2 py-1 rounded-md">{{ __('by_volume') }}</span>
                            </div>
                            
                            <div class="flex flex-col gap-4 flex-1 overflow-y-auto custom-scrollbar pr-2 min-h-0">
                                @forelse($kategoriTerlaris ?? [] as $index => $kat)
                                    <div onclick="openDrilldownModal('modalKategori-{{ $index }}')" class="group flex flex-col gap-2 cursor-pointer">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition-colors">{{ $kat->nama_kategori ?? $kat->kategori_keluar ?? 'Kategori' }}</span>
                                            <span class="text-xs font-black text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-2 py-0.5 rounded">{{ number_format($kat->total_qty_keluar ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden flex">
                                            @php
                                                $terjual = $kat->total_qty_keluar ?? 0;
                                                $rusak = $kat->total_qty_rusak ?? 0;
                                                $totalMax = max(1, $terjual + $rusak); 
                                                $pctTerjual = ($terjual / $totalMax) * 100;
                                                $pctRusak = ($rusak / $totalMax) * 100;
                                            @endphp
                                            <div class="bg-indigo-500 h-full" style="width: {{ $pctTerjual }}%"></div>
                                            <div class="bg-gray-400 dark:bg-gray-500 h-full" style="width: {{ $pctRusak }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Modal Drill-Down Kategori --}}
                                    <div id="modalKategori-{{ $index }}" class="fixed inset-0 bg-black/60 hidden z-[110] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
                                        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-xl flex flex-col max-h-[80vh]" onclick="event.stopPropagation()">
                                            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                                                <h3 class="font-black text-gray-800 dark:text-white text-lg"><i class="fas fa-layer-group text-indigo-500 mr-2"></i> {{ $kat->nama_kategori ?? $kat->kategori_keluar ?? 'Kategori' }}</h3>
                                                <button onclick="closeDrilldownModal('modalKategori-{{ $index }}'); event.stopPropagation();" class="text-gray-500 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
                                            </div>
                                            <div class="p-6 overflow-y-auto custom-scrollbar">
                                                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                                    <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                                        <tr>
                                                            <th class="px-4 py-3">Nama Barang</th>
                                                            <th class="px-4 py-3 text-center">Keluar</th>
                                                            <th class="px-4 py-3 text-center">Rusak</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($kat->items) && count($kat->items) > 0)
                                                            @foreach($kat->items as $itemDetail)
                                                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                                                <td class="px-4 py-3 font-bold text-gray-700 dark:text-gray-300">{{ $itemDetail->nama_barang }}</td>
                                                                <td class="px-4 py-3 text-center font-black text-emerald-600">{{ $itemDetail->qty_keluar }}</td>
                                                                <td class="px-4 py-3 text-center font-black {{ $itemDetail->qty_rusak > 0 ? 'text-red-500' : 'text-gray-500' }}">{{ $itemDetail->qty_rusak }}</td>
                                                            </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-4 text-center flex flex-col items-center justify-center h-full">
                                        <i class="fas fa-box-open text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500">Belum ada data keluar.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Dead Stock (> 6 Bulan) --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex-1 flex flex-col transition-colors relative">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-gray-700 pb-3 relative z-10">
                                <h3 class="text-sm font-bold text-[#D00000] dark:text-red-400 flex items-center gap-2">
                                    <i class="fas fa-snowflake"></i> {{ __('frozen_stock') }}
                                    <div class="relative inline-block mt-0.5 group z-50">
                                        <i class="fas fa-question-circle text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 cursor-pointer transition-colors text-xs peer"></i>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                            Daftar material yang tidak memiliki pergerakan (stok mati) selama lebih dari 6 bulan terakhir.
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                        </div>
                                    </div>
                                </h3>
                                <a href="{{ route('persediaan') }}" class="text-[10px] font-bold text-[#D00000] dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 px-3 py-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 card-shadow-red transition-all duration-300 transform hover:-translate-y-0.5">CEK GUDANG</a>
                            </div>
                            <div class="flex flex-col gap-3 flex-1 overflow-y-auto custom-scrollbar pr-2 relative z-10 min-h-0">
                                @forelse($deadStock ?? [] as $ds)
                                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                        <div class="overflow-hidden w-[75%]">
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-300 truncate" title="{{ $ds->nama_barang }}">{{ $ds->nama_barang }}</p>
                                            <p class="text-[10px] text-red-500 font-bold mt-1">Beku sejak: {{ \Carbon\Carbon::parse($ds->updated_at)->format('d M Y') }}</p>
                                        </div>
                                        <span class="text-xs font-black text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 px-2 py-1 rounded">{{ $ds->stok }} {{ $ds->satuan }}</span>
                                    </div>
                                @empty
                                    <div class="py-2 text-center flex flex-col items-center justify-center h-full">
                                        <i class="fas fa-check-circle text-3xl mb-2 text-emerald-500 dark:text-emerald-400"></i>
                                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Gudang sehat. Aliran lancar.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- WIDGET BARU: Stock Opname (Audit) --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex-1 flex flex-col transition-colors relative">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-gray-700 pb-3 relative z-10">
                                <h3 class="text-sm font-bold text-teal-600 dark:text-teal-400 flex items-center gap-2">
                                    <i class="fas fa-clipboard-check"></i> {{ __('audit_stock_opname') }}
                                    <div class="relative inline-block mt-0.5 group z-50">
                                        <i class="fas fa-question-circle text-gray-400 dark:text-gray-500 hover:text-teal-500 dark:hover:text-teal-400 cursor-pointer transition-colors text-xs peer"></i>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                            Riwayat pencatatan penyesuaian stok fisik dan sistem (Audit).
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                        </div>
                                    </div>
                                </h3>
                                <a href="{{ route('stock_opname') ?? '#' }}" class="text-[10px] font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800/50 px-3 py-1.5 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-900/50 card-shadow-teal transition-all duration-300 transform hover:-translate-y-0.5">RIWAYAT</a>
                            </div>
                            <div class="flex flex-col gap-3 flex-1 overflow-y-auto custom-scrollbar pr-2 relative z-10 min-h-0">
                                @forelse($stockOpnameList ?? [] as $opname)
                                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                        <div class="overflow-hidden w-[65%]">
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-300 truncate" title="{{ $opname->keterangan }}">{{ $opname->keterangan ?? 'Audit Rutin' }}</p>
                                            <p class="text-[10px] text-gray-500 font-bold mt-1">{{ \Carbon\Carbon::parse($opname->tanggal)->format('d M Y') }} &bull; {{ $opname->pembuat->name ?? 'Sistem' }}</p>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded border {{ $opname->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50' : ($opname->status == 'rejected' ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50' : 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800/50') }}">
                                            {{ $opname->status }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="py-2 text-center flex flex-col items-center justify-center h-full">
                                        <i class="fas fa-clipboard-list text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Belum ada riwayat audit fisik.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
                
                {{-- TABEL BAWAH: DATA LENGKAP --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    {{-- WIDGET: Transaksi Terbaru --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-[400px]">
                        <div class="flex justify-between items-center mb-5 shrink-0 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500"><i class="fas fa-history text-sm"></i></div>
                                {{ __('latest_transactions') }}
                                <div class="relative inline-block mt-0.5 group z-50">
                                    <i class="fas fa-question-circle text-gray-400 dark:text-gray-500 hover:text-blue-500 dark:hover:text-blue-400 cursor-pointer transition-colors text-xs peer"></i>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                        Daftar aktivitas transaksi barang masuk dan keluar terbaru di sistem.
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                    </div>
                                </div>
                            </h3>
                            <a href="{{ route('stok_keluar') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 px-3 py-1.5 rounded-lg card-shadow-blue transition-all duration-300 transform hover:-translate-y-0.5">{{ __('all_reports') }} &rarr;</a>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
                            <table class="w-full text-sm text-left relative">
                                <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider sticky top-0 bg-gray-100 dark:bg-gray-700/50 z-10 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 rounded-tl-lg">Tgl & Ref</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                        <th class="px-4 py-3 text-right rounded-tr-lg">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiTerbaru ?? [] as $trx)
                                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="px-4 py-3 align-top">
                                                <div class="font-bold text-xs text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}</div>
                                                <div class="text-[10px] font-mono text-gray-500 dark:text-gray-400 mt-1">{{ $trx->no_transaksi ?? '-' }}</div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center gap-2">
                                                    @if($trx->jenis_transaksi == 'masuk')
                                                        <span class="w-5 h-5 rounded flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 text-[10px]"><i class="fas fa-arrow-down"></i></span>
                                                    @else
                                                        <span class="w-5 h-5 rounded flex items-center justify-center bg-red-50 dark:bg-red-900/30 text-[#D00000] dark:text-red-500 text-[10px]"><i class="fas fa-arrow-up"></i></span>
                                                    @endif
                                                    <span class="font-bold text-xs text-gray-800 dark:text-gray-200 truncate max-w-[150px]">{{ $trx->tujuan ?? 'Internal' }}</span>
                                                </div>
                                                <div class="text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[200px] mt-1.5">{{ $trx->catatan ?? '-' }}</div>
                                            </td>
                                            <td class="px-4 py-3 align-top text-right">
                                                <div class="font-black text-gray-800 dark:text-gray-100 text-sm">Rp {{ number_format($trx->total_nilai, 0, ',', '.') }}</div>
                                                <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 mt-1">{{ $trx->items ? $trx->items->sum('qty') : 0 }} Item</div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400 italic">Belum ada aktivitas.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- WIDGET: Peringatan Stok --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-[400px]">
                        <div class="flex justify-between items-center mb-5 shrink-0 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-[#D00000] dark:text-red-500"><i class="fas fa-exclamation-triangle"></i></div>
                                {{ __('low_stock_warning') }}
                                <div class="relative inline-block mt-0.5 group z-50">
                                    <i class="fas fa-question-circle text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 cursor-pointer transition-colors text-xs peer"></i>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                        Daftar material yang jumlah stoknya sudah mendekati atau di bawah batas minimum.
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                    </div>
                                </div>
                            </h3>
                            <a href="{{ route('persediaan') }}" class="text-xs font-bold text-[#D00000] hover:text-red-800 dark:hover:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 px-3 py-1.5 rounded-lg card-shadow-red transition-all duration-300 transform hover:-translate-y-0.5">{{ __('master_data') }} &rarr;</a>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-3">
                            @forelse($stokMenipis ?? [] as $item)
                                <div class="flex justify-between items-center p-4 bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl hover:bg-red-100/50 dark:hover:bg-red-900/20 transition-colors">
                                    <div class="flex items-center gap-3 w-2/3">
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#D00000] dark:bg-red-500 animate-pulse shrink-0"></div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">{{ $item->nama_barang }}</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $item->sku ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black text-[#D00000] dark:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-900/50 px-2.5 py-1 rounded block shadow-sm">{{ $item->stok }} {{ $item->satuan }}</span>
                                        <p class="text-[9px] font-bold text-gray-500 dark:text-gray-400 mt-1.5">Batas ROP: {{ $item->reorder_point ?? 0 }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex-1 flex flex-col items-center justify-center py-6 h-full">
                                    <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 rounded-full flex items-center justify-center mb-3 shadow-sm">
                                        <i class="fas fa-shield-alt text-2xl text-emerald-500"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Stok Gudang Aman</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Seluruh material di atas batas ROP.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                </div> {{-- END contentOperational --}}

                {{-- KONTEN ANALISIS AI DIHAPUS UNTUK FOKUS SKRIPSI --}}

            </div>
        </div>
    </div>

    {{-- MODAL POP-UP DETAIL WIDGET --}}
    
    {{-- Modal Aset --}}
    <div id="modalAset" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-vault text-emerald-500 mr-2"></i> {{ __('asset_value_detail') }}</h3>
                <button onclick="closeDetailModal('modalAset')" class="text-gray-400 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3">{{ __('material_name') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('stock') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('capital_price') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailAset ?? [] as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-3 font-bold text-gray-800 dark:text-gray-200">{{ $item->nama_barang }}</td>
                            <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-400 font-medium">{{ $item->stok }} {{ $item->satuan }}</td>
                            <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($item->stok * $item->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 italic">{{ __('no_asset_details') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Hutang --}}
    <div id="modalHutang" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-file-invoice-dollar text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('vendor_debt_detail') }}</h3>
                <button onclick="closeDetailModal('modalHutang')" class="text-gray-400 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3">{{ __('invoice_due_date') }}</th>
                            <th class="px-5 py-3">{{ __('supplier_details') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('bill') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailHutang ?? [] as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 align-top transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700 px-2 py-1 rounded border border-gray-200 dark:border-gray-600">{{ $item->no_transaksi ?? '-' }}</span>
                                <div class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-500 dark:text-red-400">
                                    <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($item->tanggal_tempo)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $item->supplier->nama_supplier ?? 'Tanpa Nama Supplier' }}</p>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 space-y-0.5">
                                    @if($item->items)
                                        @foreach($item->items->take(2) as $det)
                                            <p>&bull; {{ $det->product->nama_barang ?? 'Barang' }} ({{ $det->qty }})</p>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right font-black text-[#D00000] dark:text-red-500 text-base">Rp {{ number_format($item->sisa_hutang ?? 0, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('hutang') }}" class="inline-flex items-center bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-500 text-emerald-600 dark:text-emerald-400 hover:text-white border border-emerald-200 dark:border-emerald-800 font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                    <i class="fas fa-external-link-alt mr-1.5"></i> Buka Menu
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 italic">{{ __('no_active_debt') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Rusak --}}
    <div id="modalRusak" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-heart-crack text-orange-500 mr-2"></i> {{ __('return_broken_detail') }}</h3>
                <button onclick="closeDetailModal('modalRusak')" class="text-gray-400 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3">{{ __('return_no_date') }}</th>
                            <th class="px-5 py-3">{{ __('supplier_details') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('total_items') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailRusak ?? [] as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 align-top transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-800 px-2.5 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">{{ $item->no_transaksi }}</span>
                                <div class="flex items-center gap-1.5 text-[11px] text-gray-500 dark:text-gray-400 mt-2.5 font-semibold bg-white dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 px-2 py-1 rounded w-fit">
                                    <i class="far fa-clock text-indigo-400"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-md bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center text-orange-500 dark:text-orange-400 shrink-0">
                                        <i class="fas fa-building text-[10px]"></i>
                                    </div>
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $item->tujuan ?? 'Supplier' }}</p>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 pl-8 space-y-1">
                                    @if($item->items)
                                        @foreach($item->items as $det)
                                            <p class="flex items-center gap-1.5">
                                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $det->product->nama_barang ?? 'Barang' }}</span> 
                                                <span class="text-gray-400">({{ $det->qty }})</span>
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center align-middle">
                                <div class="inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-xl bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-800/50 shadow-sm">
                                    <span class="font-black text-orange-600 dark:text-orange-400 text-base">{{ $item->items ? $item->items->sum('qty') : 0 }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-2 min-w-[140px] justify-center">
                                    <form action="{{ route('dashboard.terima_pengganti', $item->id ?? 0) }}" method="POST" onsubmit="confirmTerimaGanti(event, this)" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl border border-emerald-500/30 bg-emerald-50/80 px-4 py-2.5 text-xs font-bold text-emerald-700 transition-all hover:bg-emerald-500 hover:text-white hover:border-emerald-500 hover:shadow-[0_4px_15px_rgba(16,185,129,0.25)] dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400">
                                            <i class="fas fa-box-open transition-transform group-hover:-translate-y-0.5 group-hover:scale-110"></i> 
                                            <span>Terima Ganti</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.selesai_rusak', $item->id ?? 0) }}" method="POST" onsubmit="confirmSelesaiRusak(event, this)" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full group inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[10px] font-bold text-gray-500 transition-all hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-blue-500/50 dark:hover:bg-blue-900/30 dark:hover:text-blue-400">
                                            <i class="fas fa-check-double transition-transform group-hover:scale-110"></i> 
                                            <span>Selesaikan Saja</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 italic">{{ __('no_return_history') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal PO --}}
    <div id="modalPO" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-truck-ramp-box text-blue-500 mr-2"></i> {{ __('active_po_detail') }}</h3>
                <button onclick="closeDetailModal('modalPO')" class="text-gray-400 hover:text-red-500 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-red-50 hover:border-red-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3">{{ __('po_no') }}</th>
                            <th class="px-5 py-3">{{ __('target_supplier') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('order_date') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('total') }}</th>
                            <th class="px-5 py-3 text-center">{{ __('status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailPO ?? [] as $item)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-4 font-bold text-blue-600 dark:text-blue-400">{{ $item->no_transaksi ?? $item->no_po ?? '-' }}</td>
                            <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-200">{{ $item->tujuan ?? $item->supplier->nama_supplier ?? '-' }}</td>
                            <td class="px-5 py-4 text-center text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-center font-black text-gray-800 dark:text-gray-200">{{ $item->items ? $item->items->sum('qty') : 0 }} Item</td>
                            <td class="px-5 py-4 text-center">
                                <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase">{{ $item->status ?? 'PENDING' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 italic">{{ __('no_active_po') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>

    <script>
        const chartDataSets = {!! json_encode($chartDataSets ?? [
            'week' => ['labels'=>['Sen','Sel','Rab','Kam','Jum','Sab','Min'], 'masuk'=>[0,0,0,0,0,0,0], 'keluar'=>[0,0,0,0,0,0,0]],
            'month' => ['labels'=>[], 'masuk'=>[], 'keluar'=>[]],
            'quarter' => ['labels'=>[], 'masuk'=>[], 'keluar'=>[]],
            '6months' => ['labels'=>[], 'masuk'=>[], 'keluar'=>[]],
            'year' => ['labels'=>[], 'masuk'=>[], 'keluar'=>[]],
            '5years' => ['labels'=>[], 'masuk'=>[], 'keluar'=>[]],
            'all' => ['labels'=>[], 'masuk'=>[], 'keluar'=>[]]
        ]) !!};
        
        let activityChartInstance = null;
        let currentChartType = 'line';

        function setChartType(type) {
            currentChartType = type;
            
            document.getElementById('btnLineChart').className = type === 'line'
                ? 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-500'
                : 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent';
                
            document.getElementById('btnBarChart').className = type === 'bar'
                ? 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-500'
                : 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent';

            updateChartData();
        }

        function updateChartData() {
            const period = document.getElementById('chartTimeRange').value;
            const data = chartDataSets[period] || chartDataSets['week'];
            
            const isDark = document.documentElement.classList.contains('dark');
            
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.06)' : '#e5e7eb'; 
            const textColor = isDark ? '#6b7280' : '#6b7280'; 
            
            if (activityChartInstance) {
                activityChartInstance.destroy();
            }

            const ctx = document.getElementById('activityChart').getContext('2d');
            
            let datasetConfig = [];
            if (currentChartType === 'line') {
                datasetConfig = [
                    { label: 'Barang Masuk', data: data.masuk, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', borderWidth: 3, tension: 0.4, fill: true, pointRadius: 2, pointHoverRadius: 5 },
                    { label: 'Barang Keluar', data: data.keluar, borderColor: '#D00000', backgroundColor: 'transparent', borderWidth: 3, borderDash: [4, 4], tension: 0.4, pointRadius: 2, pointHoverRadius: 5 }
                ];
            } else {
                datasetConfig = [
                    { label: 'Barang Masuk', data: data.masuk, backgroundColor: '#10b981', borderRadius: 6 },
                    { label: 'Barang Keluar', data: data.keluar, backgroundColor: '#D00000', borderRadius: 6 }
                ];
            }

            Chart.defaults.font.family = "'Inter', 'SF Pro Display', sans-serif";
            Chart.defaults.color = textColor;

            activityChartInstance = new Chart(ctx, {
                type: currentChartType,
                data: {
                    labels: data.labels,
                    datasets: datasetConfig
                },
                options: {
                    responsive: true, maintainAspectRatio: false, 
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#f3f4f6' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#4b5563',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1, padding: 12, cornerRadius: 8,
                            boxPadding: 6, titleFont: { size: 12 }, bodyFont: { size: 12 }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { borderDash: [4, 4], color: gridColor, drawBorder: false, lineWidth: 1 }, 
                            border: {display: false}, 
                            ticks: { font: {size: 11, weight: '600'}, color: textColor } 
                        },
                        x: { 
                            grid: { display: false }, 
                            border: {display: false}, 
                            ticks: { font: {size: 11, weight: '600'}, color: textColor } 
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateChartData();
        });

        // FUNGSI ANALISIS AI GRAFIK
        function generateChartInsight() {
            const periodSelect = document.getElementById('chartTimeRange');
            const periodValue = periodSelect.value;
            const periodName = periodSelect.options[periodSelect.selectedIndex].text;
            
            const data = chartDataSets[periodValue] || chartDataSets['week'];
            
            const totalMasuk = data.masuk.reduce((a, b) => a + b, 0);
            const totalKeluar = data.keluar.reduce((a, b) => a + b, 0);
            const totalVolume = totalMasuk + totalKeluar;
            const selisih = Math.abs(totalMasuk - totalKeluar);
            
            let title = '';
            let icon = '';
            let message = '';
            
            if (totalVolume === 0 || (totalMasuk < 5 && totalKeluar < 5 && periodValue !== 'week')) {
                // Kondisi 5: Stagnan
                title = 'Stagnasi Operasional';
                icon = 'info';
                message = `<div class="text-left text-sm mt-3 space-y-3">
                    <p>Sistem mendeteksi <b>Stagnasi Operasional</b> pada periode <b>${periodName}</b>. Aktivitas keluar dan masuk barang berada di bawah rata-rata normal (Total volume hanya sebesar <b>${totalVolume} unit</b>).</p>
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
                        <p class="font-bold text-blue-700 dark:text-blue-400 text-xs mb-1"><i class="fas fa-lightbulb mr-1"></i> Rekomendasi Ahli:</p>
                        <p class="text-blue-600 dark:text-blue-300 text-xs">Periksa apakah terjadi kendala teknis pada sistem pencatatan, penurunan permintaan pasar yang drastis, atau keterlambatan pengiriman massal dari rantai pasok (supplier).</p>
                    </div>
                </div>`;
            } else if (selisih <= totalVolume * 0.02) {
                // Kondisi 3: Ekuilibrium
                title = 'Ekuilibrium Sempurna';
                icon = 'success';
                message = `<div class="text-left text-sm mt-3 space-y-3">
                    <p>Pada periode <b>${periodName}</b>, operasional berjalan sangat efisien di titik <b>Ekuilibrium Sempurna</b>. Jumlah barang masuk (<b>${totalMasuk}</b>) nyaris persis seimbang dengan barang keluar (<b>${totalKeluar}</b>).</p>
                    <div class="bg-emerald-50 dark:bg-emerald-900/30 p-3 rounded-lg border border-emerald-200 dark:border-emerald-800">
                        <p class="font-bold text-emerald-700 dark:text-emerald-400 text-xs mb-1"><i class="fas fa-lightbulb mr-1"></i> Kesimpulan Ahli:</p>
                        <p class="text-emerald-600 dark:text-emerald-300 text-xs">Perputaran inventaris (<i>inventory turnover</i>) berada pada tingkat performa yang optimal. Ini meminimalkan risiko penumpukan stok yang memakan ruang, maupun kelangkaan barang yang mengecewakan pelanggan.</p>
                    </div>
                </div>`;
            } else if (totalMasuk > totalKeluar) {
                // Kondisi 1: Surplus
                title = 'Penumpukan Stok (Surplus)';
                icon = 'warning';
                message = `<div class="text-left text-sm mt-3 space-y-3">
                    <p>Pada periode <b>${periodName}</b>, volume barang masuk (<b>${totalMasuk}</b>) lebih tinggi dibanding barang keluar (<b>${totalKeluar}</b>). Sistem mendeteksi terjadinya penumpukan stok dengan surplus sebesar <b>${selisih} unit</b>.</p>
                    <div class="bg-orange-50 dark:bg-orange-900/30 p-3 rounded-lg border border-orange-200 dark:border-orange-800">
                        <p class="font-bold text-orange-700 dark:text-orange-400 text-xs mb-1"><i class="fas fa-lightbulb mr-1"></i> Rekomendasi Ahli:</p>
                        <p class="text-orange-600 dark:text-orange-300 text-xs">Kurangi laju pengadaan barang (Purchase Order) sementara waktu atau tingkatkan strategi pemasaran untuk menghindari pembengkakan biaya pemeliharaan gudang (<i>holding cost</i>) dan depresiasi kualitas barang.</p>
                    </div>
                </div>`;
            } else {
                // Kondisi 2: Defisit
                title = 'Pengurasan Stok (Deplesi)';
                icon = 'error';
                message = `<div class="text-left text-sm mt-3 space-y-3">
                    <p>Perhatian! Pada periode <b>${periodName}</b>, permintaan pasar sangat tinggi menyebabkan barang keluar (<b>${totalKeluar}</b>) melebihi pasokan masuk (<b>${totalMasuk}</b>). Terjadi pengurasan stok dengan defisit sebesar <b>${selisih} unit</b>.</p>
                    <div class="bg-red-50 dark:bg-red-900/30 p-3 rounded-lg border border-red-200 dark:border-red-800">
                        <p class="font-bold text-red-700 dark:text-red-400 text-xs mb-1"><i class="fas fa-lightbulb mr-1"></i> Rekomendasi Ahli:</p>
                        <p class="text-red-600 dark:text-red-300 text-xs">Segera lakukan pemesanan ulang (<i>reorder</i>) massal ke pihak supplier untuk mencegah kondisi kehabisan stok total (<i>stockout</i>) yang dapat merugikan rantai pasok Anda di periode berikutnya.</p>
                    </div>
                </div>`;
            }
            
            // Check Kondisi 4: Lonjakan
            if (data.masuk.length >= 2) {
                const len = data.masuk.length;
                const lastMasuk = data.masuk[len-1];
                const prevMasuk = data.masuk[len-2];
                const lastKeluar = data.keluar[len-1];
                const prevKeluar = data.keluar[len-2];
                
                if (prevMasuk > 0 && prevKeluar > 0 && lastMasuk > prevMasuk * 1.5 && lastKeluar > prevKeluar * 1.5) {
                    const pct = Math.round(((lastMasuk + lastKeluar) - (prevMasuk + prevKeluar)) / (prevMasuk + prevKeluar) * 100);
                    title = 'Lonjakan Aktivitas (Spike)!';
                    icon = 'warning';
                    message = `<div class="text-left text-sm mt-3 space-y-3">
                        <p>Sistem mendeteksi <b>Lonjakan Aktivitas Tinggi</b> pada akhir periode grafik. Volume lalu lintas logistik melonjak drastis sebesar <b>${pct}%</b> dibandingkan titik waktu sebelumnya.</p>
                        <div class="bg-indigo-50 dark:bg-indigo-900/30 p-3 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <p class="font-bold text-indigo-700 dark:text-indigo-400 text-xs mb-1"><i class="fas fa-lightbulb mr-1"></i> Rekomendasi Ahli:</p>
                            <p class="text-indigo-600 dark:text-indigo-300 text-xs">Pastikan kapasitas ruang muat gudang dan jumlah staf (manpower) mencukupi untuk menangani skala lonjakan operasional logistik ini agar tidak terjadi bottleneck.</p>
                        </div>
                    </div>`;
                }
            }

            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: '<span class="text-xl font-black">' + title + '</span>',
                html: message,
                icon: icon,
                confirmButtonColor: '#4f46e5',
                confirmButtonText: '<i class="fas fa-check mr-1.5"></i> Mengerti',
                background: isDark ? '#1f2937' : '#fff', 
                color: isDark ? '#f3f4f6' : '#374151',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700',
                    confirmButton: 'rounded-xl px-5 py-2 font-bold text-sm shadow-sm'
                }
            });
        }

        // FUNGSI MODAL
        function openDetailModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeDetailModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function openDrilldownModal(modalId) { openDetailModal(modalId); }
        function closeDrilldownModal(modalId) { closeDetailModal(modalId); }

        // MANAJEMEN TAB DASHBOARD
        function switchDashboardTab(tabName) {
            localStorage.setItem('activeDashboardTab', tabName);
            const btnOp = document.getElementById('tabOperational');
            const btnAn = document.getElementById('tabAnalytics');
            const contentOp = document.getElementById('contentOperational');

            if (tabName === 'operational') {
                if (btnOp) btnOp.className = "flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 transition-all shadow-sm";
                if (btnAn) btnAn.className = "flex-1 md:flex-none items-center justify-center gap-2 px-6 py-2.5 rounded-lg text-sm font-bold text-gray-500 hover:text-indigo-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-gray-700/50 transition-all";
                if (contentOp) contentOp.classList.remove('hidden');
            }
        }


        // VARIABEL GLOBAL ANALISIS
        window.analyticsDetails = {};
        window.currentLang = "{{ app()->getLocale() }}";
        let predictiveChartInstance = null;



        // FUNGSI RENDER GRAFIK PREDIKTIF
        function renderPredictiveChart(chartData) {
            const ctx = document.getElementById('predictiveChart').getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            
            if (predictiveChartInstance) {
                predictiveChartInstance.destroy();
            }

            predictiveChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Riwayat Transaksi Keluar',
                            data: chartData.history,
                            borderColor: '#4f46e5', // indigo-600
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        },
                        {
                            label: '{{ app()->getLocale() == "en" ? "Linear Regression Forecast" : "Regresi Linear (Prediksi)" }}',
                            data: chartData.future,
                            borderColor: '#f97316', // orange-500
                            borderWidth: 3,
                            borderDash: [5, 5], // Garis putus-putus
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: isDark ? '#9ca3af' : '#4b5563',
                                font: { family: "'Nunito', sans-serif", weight: 'bold' }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDark ? 'rgba(31, 41, 55, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: isDark ? '#f3f4f6' : '#1f2937',
                            bodyColor: isDark ? '#d1d5db' : '#4b5563',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDark ? '#374151' : '#f3f4f6',
                                drawBorder: false
                            },
                            ticks: {
                                color: isDark ? '#9ca3af' : '#6b7280'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: isDark ? '#9ca3af' : '#6b7280',
                                maxTicksLimit: 15
                            }
                        }
                    }
                }
            });
        }

        // OBSERVER TEMA UNTUK CHART.JS
        const themeObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    const isDark = document.documentElement.classList.contains('dark');
                    
                    if (typeof predictiveChartInstance !== 'undefined' && predictiveChartInstance) {
                        predictiveChartInstance.options.scales.x.ticks.color = isDark ? '#9ca3af' : '#6b7280';
                        predictiveChartInstance.options.scales.y.ticks.color = isDark ? '#9ca3af' : '#6b7280';
                        predictiveChartInstance.options.scales.y.grid.color = isDark ? '#374151' : '#f3f4f6';
                        predictiveChartInstance.options.plugins.legend.labels.color = isDark ? '#9ca3af' : '#4b5563';
                        predictiveChartInstance.options.plugins.tooltip.backgroundColor = isDark ? 'rgba(31, 41, 55, 0.9)' : 'rgba(255, 255, 255, 0.9)';
                        predictiveChartInstance.options.plugins.tooltip.titleColor = isDark ? '#f3f4f6' : '#1f2937';
                        predictiveChartInstance.options.plugins.tooltip.bodyColor = isDark ? '#d1d5db' : '#4b5563';
                        predictiveChartInstance.options.plugins.tooltip.borderColor = isDark ? '#374151' : '#e5e7eb';
                        predictiveChartInstance.update();
                    }
                    
                    if (typeof activityChartInstance !== 'undefined' && activityChartInstance) {
                        activityChartInstance.options.scales.x.ticks.color = isDark ? '#9ca3af' : '#6b7280';
                        activityChartInstance.options.scales.y.ticks.color = isDark ? '#9ca3af' : '#6b7280';
                        activityChartInstance.options.scales.y.grid.color = isDark ? '#374151' : '#f3f4f6';
                        activityChartInstance.options.plugins.legend.labels.color = isDark ? '#9ca3af' : '#4b5563';
                        activityChartInstance.update();
                    }
                }
            });
        });
        themeObserver.observe(document.documentElement, { attributes: true });

        // FUNGSI POPUP DETAIL (SWEETALERT2)
        function showAnalyticDetail(key, title) {
            const detailText = window.analyticsDetails[key];
            if (!detailText) return;

            const isDark = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: `<h2 class="text-2xl font-black ${isDark ? 'text-white' : 'text-gray-800'}">${title}</h2>`,
                html: `<div class="text-left text-sm leading-relaxed ${isDark ? 'text-gray-300' : 'text-gray-600'}">${detailText}</div>`,
                icon: 'info',
                background: isDark ? '#1f2937' : '#ffffff',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: currentLang === 'en' ? 'Close & Understood' : 'Tutup & Paham',
                customClass: {
                    popup: `rounded-2xl border ${isDark ? 'border-gray-700' : 'border-gray-200'} shadow-2xl`,
                    confirmButton: 'rounded-lg px-6 font-bold shadow-md'
                }
            });
        }

        // FUNGSI ANALISIS GARIS GRAFIK PREDISKI
        function analyzeFutureGraph() {
            if(!predictiveChartInstance) {
                const warningMsg = currentLang === 'en' ? 'Run the simulation first.' : 'Jalankan simulasi terlebih dahulu.';
                const opsMsg = currentLang === 'en' ? 'Oops!' : 'Ops!';
                Swal.fire(opsMsg, warningMsg, 'warning');
                return;
            }
            
            const isDark = document.documentElement.classList.contains('dark');
            const dataFuture = predictiveChartInstance.data.datasets[1].data;
            // Get last valid value from future data
            const lastVal = dataFuture[dataFuture.length - 1];
            // Get first valid value from future data
            let firstVal = 0;
            for(let i=0; i<dataFuture.length; i++) {
                if(dataFuture[i] !== null) { firstVal = dataFuture[i]; break; }
            }

            let analysisText = "";
            let iconType = "success";
            
            if (lastVal > firstVal) {
                analysisText = currentLang === 'en' 
                    ? `The red dashed line shows a strong <b>Uptrend</b>. Demand is predicted to reach around <b>${lastVal} units</b> outgoing per day by the end of next month. Restock immediately to avoid Stockouts!` 
                    : `Garis merah putus-putus menunjukkan <b>Tren Naik (Uptrend)</b> yang kuat. Prediksi permintaan di akhir bulan depan akan mencapai sekitar <b>${lastVal} unit</b> transaksi keluar per hari. Segera tambah stok Anda agar tidak kehabisan (Stockout)!`;
                iconType = "warning";
            } else if (lastVal < firstVal) {
                analysisText = currentLang === 'en' 
                    ? `The red dashed line shows a <b>Downtrend</b>. Demand is predicted to slow down to around <b>${lastVal} units</b> per day by the end of the month. Delay new orders so goods do not pile up.` 
                    : `Garis merah putus-putus menunjukkan <b>Tren Turun (Downtrend)</b>. Permintaan diprediksi akan melesu menjadi sekitar <b>${lastVal} unit</b> per hari di akhir bulan. Tunda pesanan baru agar barang tidak menumpuk di gudang.`;
                iconType = "info";
            } else {
                analysisText = currentLang === 'en' 
                    ? `The red dashed line shows <b>Stagnation (Stable)</b>. Demand is predicted to remain constant around <b>${lastVal} units</b> per day. Material flow is very stable.` 
                    : `Garis merah putus-putus menunjukkan <b>Stagnasi (Stabil)</b>. Permintaan diprediksi akan konstan di sekitar <b>${lastVal} unit</b> per hari. Arus barang sangat stabil.`;
            }

            const titleEn = "Future Line Analysis";
            const titleId = "Analisis Garis Future";
            Swal.fire({
                title: `<h2 class="text-xl font-black ${isDark ? 'text-white' : 'text-gray-800'}"><i class="fas fa-magic text-indigo-500 mr-2"></i> ${currentLang === 'en' ? titleEn : titleId}</h2>`,
                html: `<div class="text-left text-sm leading-relaxed ${isDark ? 'text-gray-300' : 'text-gray-600'}">${analysisText}</div>`,
                icon: iconType,
                background: isDark ? '#1f2937' : '#ffffff',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: currentLang === 'en' ? 'Understood!' : 'Siap Laksanakan!',
                customClass: {
                    popup: `rounded-2xl border ${isDark ? 'border-gray-700' : 'border-gray-200'} shadow-xl`
                }
            });
        }

        window.addEventListener('click', function(e) {
            if (e.target.id && (e.target.id.startsWith('modalKategori-') || ['modalAset', 'modalHutang', 'modalRusak', 'modalPO'].includes(e.target.id))) {
                closeDetailModal(e.target.id);
            }
        });

        // SWEETALERT2
        function confirmLunas(event, formElement) {
            event.preventDefault();
            const isDark = document.documentElement.classList.contains('dark');
            @if(in_array(Auth::user()->role, ['owner', 'admin']))
                Swal.fire({
                    title: 'Tandai Lunas?',
                    text: "Apakah Anda yakin faktur hutang ini sudah dibayar lunas?",
                    icon: 'warning', showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Lunas!', cancelButtonText: 'Batal',
                    background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454'
                }).then((result) => { if (result.isConfirmed) formElement.submit(); });
            @else
                Swal.fire({ title: 'Akses Ditolak!', text: 'Hanya Owner dan Admin yang berhak menandai status lunas.', icon: 'error', confirmButtonColor: '#D00000', background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454' });
            @endif
        }

        function confirmSelesaiRusak(event, formElement) {
            event.preventDefault();
            const isDark = document.documentElement.classList.contains('dark');
            @if(in_array(Auth::user()->role, ['owner', 'admin']))
                Swal.fire({
                    title: 'Selesaikan (Tanpa Terima Fisik)?',
                    text: "Apakah Anda yakin Supplier memotong tagihan dan Anda TIDAK AKAN menerima barang pengganti dari mereka?",
                    icon: 'warning', showCancelButton: true, confirmButtonColor: '#3b82f6', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Selesaikan!', cancelButtonText: 'Batal',
                    background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454'
                }).then((result) => { if (result.isConfirmed) formElement.submit(); });
            @else
                Swal.fire({ title: 'Akses Ditolak!', text: 'Hanya Owner dan Admin yang berhak menyelesaikan status retur.', icon: 'error', confirmButtonColor: '#D00000', background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454' });
            @endif
        }

        function confirmTerimaGanti(event, formElement) {
            event.preventDefault();
            const isDark = document.documentElement.classList.contains('dark');
            @if(in_array(Auth::user()->role, ['owner', 'admin', 'gudang']))
                Swal.fire({
                    title: 'Terima Barang Pengganti?',
                    text: "Sistem akan OTOMATIS menambahkan barang secara fisik ke stok gudang Anda sekarang. Apakah barang sudah Anda terima dari Supplier?",
                    icon: 'question', showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Terima & Tambah Stok!', cancelButtonText: 'Batal',
                    background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454'
                }).then((result) => { if (result.isConfirmed) formElement.submit(); });
            @else
                Swal.fire({ title: 'Akses Ditolak!', text: 'Hanya bagian Gudang, Admin, atau Owner yang berhak menerima fisik.', icon: 'error', confirmButtonColor: '#D00000', background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454' });
            @endif
        }
        
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);

        // INIT SAAT HALAMAN DIMUAT (Tab Retention)
        document.addEventListener('DOMContentLoaded', function() {
            // Karena AI di-hide, kita paksa selalu ke operational
            localStorage.setItem('activeDashboardTab', 'operational');
            switchDashboardTab('operational');
        });


    </script>
</x-app-layout>