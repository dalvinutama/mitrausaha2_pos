<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS Latar Belakang --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 transition-colors duration-300">
                
                {{-- HEADER HALAMAN --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors"><i class="fas fa-home text-sm"></i></a> 
                            <span>/</span> <span>{{ __('system') }}</span> <span>/</span> <span class="text-[#D00000] dark:text-red-400">{{ __('report_audit_center') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('audit_mutation_system') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('sync_physical_stok_with_system') }}</p>
                    </div>
                    
                    <div class="flex gap-2">
                        @if(in_array(Auth::user()->role, ['owner', 'gudang', 'admin']))
                        <button onclick="toggleMode('form')" id="btnTambah" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-blue-900/20 font-bold flex items-center gap-2 transition-all hover:-translate-y-1 text-sm">
                            <i class="fas fa-clipboard-list"></i> {{ __('start_new_opname') }}
                        </button>
                        @endif
                        <button onclick="toggleMode('tabel')" id="btnKembali" class="hidden bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-gray-900/20 font-bold flex items-center gap-2 transition-all hover:-translate-y-1 text-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('back_to_history') }}
                        </button>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- TAB NAVIGASI INTEGRASI: MUTASI VS OPNAME --}}
                {{-- ========================================================= --}}
                <div class="flex border-b border-gray-300 dark:border-gray-700">
                    <a href="{{ route('laporan') }}" class="py-3 px-6 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <i class="fas fa-file-invoice mr-2"></i> {{ __('stock_mutation_report_system') }}
                    </a>
                    <a href="{{ route('stock_opname') }}" class="py-3 px-6 text-sm font-bold border-b-2 border-[#D00000] text-[#D00000] dark:border-red-500 dark:text-red-500 transition-colors">
                        <i class="fas fa-clipboard-check mr-2"></i> {{ __('audit_stock_opname_physical') }}
                    </a>
                </div>

                {{-- NOTIFIKASI --}}
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm font-bold transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm font-bold transition-colors">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                {{-- ======================================================== --}}
                {{-- MODE 1: FORM INPUT OPNAME BARU (KHUSUS OWNER/GUDANG/ADMIN) --}}
                {{-- ======================================================== --}}
                @if(in_array(Auth::user()->role, ['owner', 'gudang', 'admin']))
                <div id="formMode" class="hidden space-y-6">
                    <form action="{{ route('stock_opname.store') }}" method="POST" id="opnameForm" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors">
                        @csrf
                        
                        <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-800/50 flex flex-col md:flex-row justify-between items-center gap-4 transition-colors">
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('audit_period') }} <span class="text-red-500">*</span></label>
                                <input type="month" name="periode" value="{{ date('Y-m') }}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-500/10 dark:focus:ring-blue-500/20 focus:border-blue-500 block p-2 transition-all dark:[color-scheme:dark]" required>
                            </div>
                            <div class="text-xs font-semibold text-orange-500 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 px-3 py-1.5 rounded-lg border border-orange-100 dark:border-orange-800/30 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> {{ __('empty_physical_stock_if_not_audited') }}
                            </div>
                        </div>

                        {{-- FILTER DAN PENCARIAN --}}
                        <div class="p-4 bg-gray-50/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row gap-4 justify-between items-center transition-colors">
                            <div class="relative w-full sm:w-1/2 md:w-1/3">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" id="searchProduct" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition-colors" placeholder="{{ __('search_material_name_sku') }}">
                            </div>
                            <div class="w-full sm:w-auto min-w-[200px]">
                                <select id="filterCategory" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors">
                                    <option value="">-- {{ __('all_categories') }} --</option>
                                    @foreach($categories ?? [] as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="overflow-x-auto min-h-[400px]">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100/80 dark:bg-gray-700/50 border-b border-gray-300 dark:border-gray-700 transition-colors sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 w-12 text-center">{{ __('no') }}</th>
                                        <th class="px-4 py-3 min-w-[200px]">{{ __('material_sku') }}</th>
                                        <th class="px-4 py-3 text-center w-32">{{ __('system_stock') }}</th>
                                        <th class="px-4 py-3 text-center w-36 bg-blue-50/50 dark:bg-blue-900/10 text-blue-700 dark:text-blue-400">{{ __('physical_stock') }} <span class="text-red-500">*</span></th>
                                        <th class="px-4 py-3 text-center w-32">{{ __('difference') }}</th>
                                        <th class="px-4 py-3 min-w-[250px]">{{ __('difference_description') }}</th>
                                    </tr>
                                </thead>
                                {{-- PERBAIKAN KONTRAS: divide-gray-200 --}}
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="productTableBody">
                                    @forelse($products as $index => $p)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors product-row" data-kategori="{{ $p->kategori_id }}" data-nama="{{ strtolower($p->nama_barang . ' ' . $p->sku) }}">
                                        <td class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-800 dark:text-gray-200">{{ $p->nama_barang }}</div>
                                            <div class="text-[10px] text-gray-500 dark:text-gray-500 font-mono mt-0.5">{{ $p->sku }}</div>
                                            {{-- NOTE: name array menggunakan [] di akhir --}}
                                            <input type="hidden" name="product_id[{{ $index }}]" value="{{ $p->id }}" disabled class="row-activator">
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-gray-600 dark:text-gray-400">
                                            {{ $p->stok }} <span class="text-[10px] uppercase font-normal text-gray-400">{{ $p->satuan }}</span>
                                        </td>
                                        <td class="px-4 py-3 bg-blue-50/20 dark:bg-blue-900/5">
                                            <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-800/50 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all shadow-inner dark:shadow-none">
                                                <button type="button" onclick="adjustOpname('stok_fisik_{{ $index }}', -1)" class="w-8 h-8 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-[10px]"></i></button>
                                                <input type="number" id="stok_fisik_{{ $index }}" name="stok_fisik[{{ $index }}]" min="0" data-sistem="{{ $p->stok }}" data-index="{{ $index }}" placeholder="{{ __('type_stock') }}" class="stok-fisik w-full bg-transparent border-none text-blue-800 dark:text-blue-300 text-sm font-black p-2 text-center focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none dark:placeholder-gray-500">
                                                <button type="button" onclick="adjustOpname('stok_fisik_{{ $index }}', 1)" class="w-8 h-8 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus text-[10px]"></i></button>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center font-black text-lg selisih-display text-gray-300 dark:text-gray-600">-</td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="keterangan[{{ $index }}]" placeholder="{{ __('reason_for_difference') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2 transition-all dark:placeholder-gray-500" disabled>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="p-8 text-center text-gray-400 italic">{{ __('empty_material_master_data') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-900 flex justify-end transition-colors">
                            <button type="submit" id="btnSubmitForm" onclick="return confirmSubmit()" class="bg-[#1e1e2d] dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white px-8 py-3.5 rounded-xl shadow-md dark:shadow-none font-black flex items-center gap-2 transition-all uppercase hover:-translate-y-0.5">
                                <i class="fas fa-paper-plane"></i> {{ __('submit_draft_opname') }}
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- ======================================================== --}}
                {{-- MODE 2: RIWAYAT OPNAME (TAMPILAN KARTU) --}}
                {{-- ======================================================== --}}
                <div id="tabelMode">
                    @if(count($stockOpnames ?? []) > 0)
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($stockOpnames as $opname)
                                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors hover:shadow-lg">
                                    {{-- HEADER KARTU --}}
                                    <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-200 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-800/30">
                                        <div class="flex items-center gap-4 w-full sm:w-auto">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl font-bold shrink-0 {{ $opname->status == 'approved' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : ($opname->status == 'rejected' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                                <i class="fas {{ $opname->status == 'approved' ? 'fa-check-double' : ($opname->status == 'rejected' ? 'fa-times-circle' : 'fa-clock') }}"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <h3 class="font-black text-gray-800 dark:text-gray-200 text-base tracking-wide">{{ $opname->no_opname }}</h3>
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider {{ $opname->status == 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($opname->status == 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                                        {{ $opname->status == 'pending_approval' ? __('waiting_for_review') : ($opname->status == 'approved' ? __('done') : __('rejected')) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400"><i class="far fa-calendar-alt mr-1"></i> {{ __('period:') }} <b class="text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($opname->periode)->translatedFormat('F Y') }}</b> &bull; {{ __('submitted_at:') }} {{ \Carbon\Carbon::parse($opname->tanggal)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-4 w-full sm:w-auto mt-2 sm:mt-0 justify-between sm:justify-end">
                                            <div class="text-left sm:text-right">
                                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-500 uppercase tracking-widest">{{ __('created_by') }}</p>
                                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300"><i class="fas fa-user-circle mr-1"></i> {{ $opname->pembuat->name ?? 'Gudang' }}</p>
                                            </div>
                                            <button onclick="toggleDetails('detail-{{ $opname->id }}')" class="flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 px-3 py-1.5 rounded-lg transition-colors group">
                                                <span>{{ __('view_detail') }}</span>
                                                <i class="fas fa-chevron-down transition-transform duration-300" id="icon-detail-{{ $opname->id }}"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ISI KARTU (DETAIL ITEM) --}}
                                    <div id="detail-{{ $opname->id }}" class="hidden transition-all duration-300">
                                        <div class="p-5 overflow-x-auto custom-scrollbar bg-white dark:bg-gray-800">
                                            <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                                <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                                    <tr>
                                                        <th class="px-4 py-3">{{ __('material_name') }}</th>
                                                        <th class="px-4 py-3 text-center">{{ __('system_stock') }}</th>
                                                        <th class="px-4 py-3 text-center">{{ __('physical_stock') }}</th>
                                                        <th class="px-4 py-3 text-center">{{ __('qty_difference') }}</th>
                                                        <th class="px-4 py-3 text-right">{{ __('valuation_value_rp') }}</th>
                                                        <th class="px-4 py-3">{{ __('description') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($opname->details as $d)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                                        <td class="px-4 py-3">
                                                            <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">{{ $d->product->nama_barang ?? __('deleted_material') }}</p>
                                                            <p class="text-[10px] text-gray-500 font-mono">{{ $d->product->sku ?? '-' }}</p>
                                                        </td>
                                                        <td class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">{{ $d->stok_sistem }}</td>
                                                        <td class="px-4 py-3 text-center font-black text-blue-700 dark:text-blue-400">{{ $d->stok_fisik }}</td>
                                                        <td class="px-4 py-3 text-center font-black">
                                                            @if($d->selisih < 0)
                                                                <span class="text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded border border-red-100 dark:border-red-800">{{ $d->selisih }}</span>
                                                            @elseif($d->selisih > 0)
                                                                <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded border border-emerald-100 dark:border-emerald-800">+{{ $d->selisih }}</span>
                                                            @else
                                                                <span class="text-gray-400 dark:text-gray-500">0</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 text-right font-bold text-gray-700 dark:text-gray-300">
                                                            {{ $d->selisih != 0 ? number_format($d->nilai_selisih, 0, ',', '.') : '-' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-[10px] text-gray-500 dark:text-gray-400 italic">
                                                            {{ $d->keterangan ?: __('match_no_description') }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- FOOTER KARTU (TOMBOL AKSI) --}}
                                        <div class="bg-gray-50/80 dark:bg-gray-900/50 p-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                                            <div>
                                                @php
                                                    $totalItem = $opname->details->count();
                                                    $itemSelisih = $opname->details->where('selisih', '!=', 0)->count();
                                                @endphp
                                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest"><i class="fas fa-chart-pie mr-1"></i> {{ __('audit_summary') }}</p>
                                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-1">{{ __('total') }} <b class="text-gray-900 dark:text-white">{{ $totalItem }}</b> {{ __('materials_checked') }} <b class="text-[#D00000] dark:text-red-400">{{ $itemSelisih }}</b> {{ __('materials_with_difference') }}</p>
                                            </div>

                                            @if($opname->status == 'pending_approval' && in_array(Auth::user()->role, ['owner', 'admin']))
                                                <div class="flex gap-2">
                                                    <form action="{{ route('stock_opname.reject', $opname->id) }}" method="POST" onsubmit="return confirm('{{ __('reject_and_cancel_opname_report') }}');">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="bg-white dark:bg-gray-800 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 px-4 py-2 rounded-lg font-bold text-xs transition-colors shadow-sm">
                                                            <i class="fas fa-times mr-1"></i> {{ __('reject') }}
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('stock_opname.approve', $opname->id) }}" method="POST" onsubmit="return confirm('{{ __('warning_approve_opname') }}');">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="bg-green-600 dark:bg-green-700 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-bold text-xs shadow-md transition-colors flex items-center gap-2">
                                                            <i class="fas fa-check-double"></i> {{ __('approve_and_sync') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($opname->status == 'approved')
                                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 px-3 py-1.5 rounded-lg flex items-center gap-2">
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                    <span class="text-xs font-bold text-green-700 dark:text-green-400">{{ __('approved_and_synced') }}</span>
                                                </div>
                                            @elseif($opname->status == 'rejected')
                                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 px-3 py-1.5 rounded-lg flex items-center gap-2">
                                                    <i class="fas fa-times-circle text-red-500"></i>
                                                    <span class="text-xs font-bold text-red-700 dark:text-red-400">{{ __('submission_rejected') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md p-16 text-center text-gray-400 dark:text-gray-500 flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 border border-gray-200 dark:border-gray-600">
                                <i class="fas fa-clipboard-check text-4xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <h3 class="text-lg font-black text-gray-600 dark:text-gray-300">{{ __('no_opname_history_yet') }}</h3>
                            <p class="text-sm mt-1 max-w-md">{{ __('physical_calculation_history_displayed_here') }}</p>
                            @if(in_array(Auth::user()->role, ['owner', 'gudang', 'admin']))
                                <button onclick="toggleMode('form')" class="mt-6 text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('start_new_opname_now') }}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

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
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);
// LOGIKA TAB & ACCORDION
        function toggleMode(mode) {
            if(mode === 'form') {
                document.getElementById('tabelMode').classList.add('hidden');
                document.getElementById('btnTambah').classList.add('hidden');
                document.getElementById('formMode').classList.remove('hidden');
                document.getElementById('btnKembali').classList.remove('hidden');
            } else {
                document.getElementById('formMode').classList.add('hidden');
                document.getElementById('btnKembali').classList.add('hidden');
                document.getElementById('tabelMode').classList.remove('hidden');
                document.getElementById('btnTambah').classList.remove('hidden');
            }
        }

        function toggleDetails(id) {
            const el = document.getElementById(id);
            const icon = document.getElementById('icon-' + id.replace('detail-', ''));
            if(el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                if(icon) icon.classList.add('rotate-180');
            } else {
                el.classList.add('hidden');
                if(icon) icon.classList.remove('rotate-180');
            }
        }

        // LOGIKA KALKULASI FORM INPUT STOK
        const isDark = document.documentElement.classList.contains('dark');
        const cRed = isDark ? 'text-red-400' : 'text-red-600';
        const cGreen = isDark ? 'text-emerald-400' : 'text-emerald-600';
        const cGray = isDark ? 'text-gray-600' : 'text-gray-400';
        
        document.querySelectorAll('.stok-fisik').forEach(input => {
            input.addEventListener('input', function() {
                const tr = this.closest('tr');
                const sistem = parseInt(this.getAttribute('data-sistem'));
                const fisik = parseInt(this.value);
                const displaySelisih = tr.querySelector('.selisih-display');
                
                const hiddenProductId = tr.querySelector('.row-activator');
                const ketInput = tr.querySelector('input[name="keterangan[]"]');

                if(isNaN(fisik)) {
                    displaySelisih.innerText = '-';
                    displaySelisih.className = `px-4 py-3 text-center font-black text-lg selisih-display ${cGray}`;
                    
                    hiddenProductId.disabled = true;
                    ketInput.disabled = true;
                    ketInput.required = false;
                } else {
                    const selisih = fisik - sistem;
                    displaySelisih.innerText = selisih > 0 ? '+' + selisih : selisih;
                    
                    if(selisih < 0) {
                        displaySelisih.className = `px-4 py-3 text-center font-black text-lg selisih-display ${cRed}`;
                    } else if(selisih > 0) {
                        displaySelisih.className = `px-4 py-3 text-center font-black text-lg selisih-display ${cGreen}`;
                    } else {
                        displaySelisih.className = `px-4 py-3 text-center font-black text-lg selisih-display text-blue-500`;
                    }

                    hiddenProductId.disabled = false;
                    this.disabled = false; 
                    ketInput.disabled = false;
                    ketInput.required = (selisih !== 0); 
                }
            });
            
            input.disabled = false; 
        });

        function adjustOpname(id, amount) {
            const input = document.getElementById(id);
            if(input) {
                let val = parseInt(input.value || '0', 10);
                val += amount;
                const min = parseInt(input.getAttribute('min') || '0', 10);
                if (val < min) val = min;
                input.value = val;
                input.dispatchEvent(new Event('input'));
            }
        }

        // FUNGSI PENCARIAN & FILTER KATEGORI
        const searchInput = document.getElementById('searchProduct');
        const categoryFilter = document.getElementById('filterCategory');
        
        function filterProducts() {
            if(!searchInput || !categoryFilter) return;
            
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value;
            const rows = document.querySelectorAll('.product-row');
            
            rows.forEach(row => {
                const rowName = row.getAttribute('data-nama');
                const rowCategory = row.getAttribute('data-kategori');
                
                const matchSearch = rowName.includes(searchTerm);
                const matchCategory = selectedCategory === "" || rowCategory === selectedCategory;
                
                if(matchSearch && matchCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if(searchInput) searchInput.addEventListener('input', filterProducts);
        if(categoryFilter) categoryFilter.addEventListener('change', filterProducts);

        function confirmSubmit() {
            let count = 0;
            document.querySelectorAll('.row-activator').forEach(el => {
                if(!el.disabled) count++;
            });

            if(count === 0) {
                const bgPopup = isDark ? '#1f2937' : '#fff';
                const colorText = isDark ? '#f3f4f6' : '#545454';
                Swal.fire({ 
                    icon: 'error', 
                    title: '{{ __('empty_form') }}', 
                    text: '{{ __('please_fill_physical_stock_for_1_material') }}', 
                    confirmButtonColor: '#D00000',
                    background: bgPopup,
                    color: colorText
                });
                return false;
            }
            return true;
        }
    </script>
</x-app-layout>