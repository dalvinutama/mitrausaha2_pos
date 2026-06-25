<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS: Latar belakang menggunakan bg-gray-100 --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 text-gray-800 dark:text-gray-200 transition-colors duration-300">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors"><i class="fas fa-home text-sm"></i></a> 
                            <span>/</span> <span>{{ __('master_data') }}</span> <span>/</span> <span class="text-[#D00000] dark:text-red-400">{{ __('inventory') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('inventory_catalog') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('inventory_desc') }}</p>
                    </div>
                    
                    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <form action="{{ route('persediaan.recalculate') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl shadow-md font-bold flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1.5">
                                <i class="fas fa-sync-alt"></i> Hitung ROP & EOQ
                            </button>
                        </form>
                        <button onclick="openModal()" class="bg-[#D00000] hover:bg-red-800 dark:hover:bg-red-700 text-white px-5 py-3 rounded-2xl shadow-md dark:shadow-none font-bold flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1.5 card-shadow-red">
                            <i class="fas fa-plus-circle text-lg"></i> {{ __('add_material') }}
                        </button>
                    </div>
                    @endif
                </div>

                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm transition-colors">
                        <ul class="list-disc pl-5 text-sm font-bold">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm font-bold text-sm transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                {{-- WIDGET STATISTIK (PERBAIKAN KONTRAS: shadow-md & border-gray-200) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1 card-shadow-blue cursor-default hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 hover:border-indigo-200 dark:hover:border-indigo-800/50 group">
                        <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fas fa-cubes"></i></div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ __('total_sku_types') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Total jenis material (SKU) unik yang terdaftar dalam katalog persediaan.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors">{{ $totalProduk ?? (isset($products) ? $products->count() : 0) }} <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 group-hover:text-indigo-400">{{ __('material') }}</span></h3>
                        </div>
                    </div>
                    
                    {{-- WIDGET STOK MENIPIS (Menggunakan Reorder Point) --}}
                    <div onclick="openStokMenipisModal()" class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 cursor-pointer hover:bg-red-50/50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-900/50 transition-all duration-300 transform hover:-translate-y-1 card-shadow-red group">
                        <div class="w-14 h-14 bg-red-50 dark:bg-red-900/30 text-red-500 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-red-500 dark:group-hover:text-red-400 transition-colors">{{ __('low_stock') }} <i class="fas fa-external-link-alt text-[8px]"></i></p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Jumlah barang yang sisa stok fisiknya sudah mencapai atau berada di bawah batas minimum (Reorder Point).
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-2xl font-black text-[#D00000] dark:text-red-500">{{ isset($products) ? $products->filter(fn($p) => $p->stok <= $p->reorder_point)->count() : 0 }} <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('material') }}</span></h3>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1 card-shadow-emerald cursor-default hover:bg-emerald-50/50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800/50 group">
                        <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fas fa-vault"></i></div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ __('estimated_asset_value') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Perkiraan total nilai rupiah dari seluruh stok persediaan saat ini (dihitung dari harga beli modal).
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-xl font-black text-gray-800 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">Rp {{ isset($products) ? number_format($products->sum(fn($p) => $p->stok * $p->harga_beli), 0, ',', '.') : 0 }}</h3>
                        </div>
                    </div>
                </div>

                {{-- TABEL KATALOG --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-800/50 flex flex-col md:flex-row justify-between items-center gap-4 transition-colors">
                        
                        {{-- FILTER DAN PENCARIAN --}}
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <div class="relative w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-search text-gray-400 dark:text-gray-500"></i></div>
                                <input type="text" id="searchInput" placeholder="{{ __('search_name_barcode') }}" class="w-full pl-9 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-2.5 transition-all dark:placeholder-gray-400">
                            </div>
                            
                            <select id="categoryFilter" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-2.5 outline-none transition-all w-full sm:w-40">
                                <option value="">{{ __('all_categories') }}</option>
                                @forelse($kategoris ?? [] as $k)<option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>@empty @endforelse
                            </select>

                            <select id="sortFilter" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-2.5 outline-none transition-all w-full sm:w-44">
                                <option value="">{{ __('sort_stock') }}</option>
                                <option value="asc">{{ __('lowest_stock') }}</option>
                                <option value="desc">{{ __('highest_stock') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto min-h-[400px]">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-5 py-4 w-16 text-center">{{ __('number_abbr') }}</th>
                                    <th class="px-5 py-4">{{ __('code_barcode') }}</th>
                                    <th class="px-5 py-4 min-w-[200px]">{{ __('material_name') }}</th>
                                    <th class="px-5 py-4">{{ __('category') }}</th>
                                    <th class="px-5 py-4 text-center">{{ __('stock') }}</th>
                                    <th class="px-5 py-4 text-center" title="Reorder Point">ROP</th>
                                    <th class="px-5 py-4 text-center" title="Economic Order Quantity">EOQ</th>
                                    <th class="px-5 py-4 text-right">{{ __('capital_price') }}</th>
                                    <th class="px-5 py-4 text-right">{{ __('selling_price') }}</th>
                                    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
                                        <th class="px-5 py-4 text-center w-24">{{ __('action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($products ?? [] as $index => $item)
                                    {{-- PERBAIKAN KONTRAS: border-gray-200 agar baris terpisah jelas --}}
                                    <tr class="data-row border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $item->stok <= $item->reorder_point ? 'bg-red-50/20 hover:bg-red-50/40 dark:bg-red-900/10 dark:hover:bg-red-900/20' : '' }}" 
                                        data-category="{{ $item->kategori_id }}" 
                                        data-stok="{{ $item->stok }}">
                                        
                                        <td class="px-5 py-4 text-center font-medium text-gray-500 dark:text-gray-400 row-number">{{ $index + 1 }}</td>
                                        <td class="px-5 py-4">
                                            <div class="text-gray-800 dark:text-gray-200 font-mono text-xs font-bold">{{ $item->sku }}</div>
                                            @if($item->barcode)<div class="text-[10px] text-gray-500 dark:text-gray-500 mt-1"><i class="fas fa-barcode mr-1"></i> {{ $item->barcode }}</div>@endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="font-bold text-gray-800 dark:text-gray-200">{{ $item->nama_barang }}</div>
                                            @if($item->stok <= $item->reorder_point)<span class="inline-block mt-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-[9px] font-bold px-2 py-0.5 rounded border border-red-200 dark:border-red-900/50">{{ __('low_stock_badge') }}</span>@endif
                                        </td>
                                        <td class="px-5 py-4"><span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded border border-gray-200 dark:border-gray-600 text-[10px] font-bold block w-max uppercase tracking-wider">{{ $item->kategori->nama_kategori ?? __('no_category') }}</span></td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="font-black text-lg {{ $item->stok <= $item->reorder_point ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $item->stok }}</span>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-500 block font-bold uppercase">{{ $item->satuan }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="font-black text-gray-800 dark:text-gray-200">{{ $item->reorder_point }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="font-black text-blue-600 dark:text-blue-400">{{ $item->eoq ?? 0 }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-right text-gray-600 dark:text-gray-400 font-medium">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td class="px-5 py-4 text-right text-gray-800 dark:text-gray-200 font-black">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                        
                                        @if(in_array(Auth::user()->role, ['gudang', 'admin']))
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" onclick="editMaterial({{ json_encode($item) }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 w-8 h-8 rounded-lg transition-colors border border-blue-200 dark:border-blue-800" title="{{ __('edit_data') }}"><i class="fas fa-edit"></i></button>
                                                
                                                <form id="delete-form-{{ $item->id }}" action="{{ route('persediaan.destroy', $item->id ?? 0) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 w-8 h-8 rounded-lg transition-colors border border-red-200 dark:border-red-800" title="{{ __('delete') }}"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr id="emptyDataRow"><td colspan="{{ in_array(Auth::user()->role, ['gudang', 'admin']) ? '8' : '7' }}" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500 italic">{{ __('no_material_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH/EDIT BARANG --}}
    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
    <div id="modalBarang" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col my-auto max-h-full">
            {{-- PERBAIKAN KONTRAS: bg-gray-100 & border-gray-200 --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 shrink-0 transition-colors">
                <h3 id="modalTitle" class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-box-open text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('new_material_form') }}</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="formPersediaan" action="{{ route('persediaan.store') }}" method="POST" class="flex flex-col overflow-hidden h-full">
                @csrf
                <div id="methodContainer"></div>

                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800 transition-colors">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        {{-- Kiri: Info Dasar & Scanner --}}
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('packaging_barcode') }}</label>
                                <div class="relative flex">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-barcode text-gray-400 dark:text-gray-500"></i></div>
                                    <input type="text" name="barcode" id="barcodeInput" placeholder="{{ __('type_or_scan_barcode') }}" class="w-full pl-9 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white font-bold text-sm rounded-l-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all uppercase dark:placeholder-gray-500">
                                    <button type="button" onclick="startScanner()" class="bg-[#1e1e2d] dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white px-4 rounded-r-xl transition-colors flex items-center justify-center border border-transparent dark:border-gray-600" title="{{ __('open_camera') }}">
                                        <i class="fas fa-camera text-lg"></i>
                                    </button>
                                </div>
                                
                                <div id="readerWrapper" class="hidden mt-3 w-full bg-black rounded-xl overflow-hidden border-2 border-[#D00000] dark:border-red-500 relative">
                                    <div id="reader" width="100%"></div>
                                    <button type="button" onclick="stopScanner()" class="absolute bottom-2 right-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                                        <i class="fas fa-times mr-1"></i> {{ __('close') }}
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('material_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_barang" id="namaMaterialInput" placeholder="{{ __('example_material') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('item_category_label') }} <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <select name="kategori_id" id="kategoriSelect" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all" required>
                                        <option value="" disabled selected>{{ __('select_category') }}</option>
                                        @forelse($kategoris ?? [] as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }} ({{ $k->prefix_sku }})</option>
                                        @empty @endforelse
                                    </select>
                                    <button type="button" onclick="openKategoriModal()" class="w-12 h-[46px] shrink-0 bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800 rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors flex items-center justify-center font-bold" title="{{ __('manage_category') }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">{{ __('capital_price') }} <span class="text-red-500">*</span></label>
                                    <div class="flex items-center w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-[#D00000] focus-within:ring-4 focus-within:ring-red-500/10 transition-all">
                                        <div class="pl-3 pr-1 flex items-center pointer-events-none shrink-0"><span class="text-gray-500 dark:text-gray-400 font-bold text-sm">Rp</span></div>
                                        <button type="button" onclick="adjustPrice('beli', -1000)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                        <input type="text" id="display_harga_beli" oninput="formatPrice(this, 'harga_beli')" placeholder="0" class="min-w-[40px] w-full text-right bg-transparent border-none text-gray-800 dark:text-white font-black text-sm px-2 py-3 focus:ring-0" required>
                                        <input type="hidden" name="harga_beli" id="harga_beli">
                                        <button type="button" onclick="adjustPrice('beli', 1000)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors mr-1 shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-green-600 dark:text-green-500 uppercase mb-1.5">{{ __('selling_price') }} <span class="text-red-500">*</span></label>
                                    <div class="flex items-center w-full bg-green-50 dark:bg-green-900/10 border border-green-300 dark:border-green-900/30 rounded-xl overflow-hidden focus-within:border-green-500 focus-within:ring-4 focus-within:ring-green-500/20 transition-all">
                                        <div class="pl-3 pr-1 flex items-center pointer-events-none shrink-0"><span class="text-green-600 dark:text-green-500 font-bold text-sm">Rp</span></div>
                                        <button type="button" onclick="adjustPrice('jual', -1000)" class="w-8 h-8 flex items-center justify-center text-green-600 hover:text-green-800 hover:bg-green-200 dark:hover:bg-green-800/50 rounded-lg transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                        <input type="text" id="display_harga_jual" oninput="formatPrice(this, 'harga_jual')" placeholder="0" class="min-w-[40px] w-full text-right bg-transparent border-none text-green-800 dark:text-green-400 font-black text-sm px-2 py-3 focus:ring-0" required>
                                        <input type="hidden" name="harga_jual" id="harga_jual">
                                        <button type="button" onclick="adjustPrice('jual', 1000)" class="w-8 h-8 flex items-center justify-center text-green-600 hover:text-green-800 hover:bg-green-200 dark:hover:bg-green-800/50 rounded-lg transition-colors mr-1 shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Stok & Pengaturan ROP --}}
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('current_stock') }}</label>
                                    <input type="text" id="stokDisabledInput" value="{{ __('zero_automatic') }}" disabled class="w-full bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 font-bold text-center text-sm rounded-xl p-3 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('unit_uom') }} <span class="text-red-500">*</span></label>
                                    <div class="flex items-center gap-2">
                                        <input type="hidden" name="satuan" id="satuanActual">
                                        <select id="satuanSelect" onchange="document.getElementById('satuanActual').value = this.value;" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all" required>
                                            <option value="" disabled selected>{{ __('select_unit') }}</option>
                                            @forelse($satuans ?? [] as $s)
                                                <option value="{{ $s->nama_satuan }}">{{ $s->nama_satuan }}</option>
                                            @empty @endforelse
                                        </select>
                                        <button type="button" onclick="openSatuanModal()" class="w-12 h-[46px] shrink-0 bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800 rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors flex items-center justify-center font-bold" title="{{ __('manage_unit') }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- PENAMBAHAN FITUR HYBRID: REORDER POINT & SAFETY STOCK --}}
                            <div class="col-span-1 md:col-span-2 bg-blue-50/50 dark:bg-blue-900/10 p-4 rounded-2xl border border-blue-200 dark:border-blue-800/50 mt-2">
                                <h4 class="text-xs font-black text-blue-800 dark:text-blue-400 uppercase mb-3 border-b border-blue-200 dark:border-blue-800/50 pb-2 flex items-center">
                                    <i class="fas fa-calculator mr-2"></i> {{ __('min_stock_setting_rop') }}
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('lead_time') }} <span class="text-red-500">*</span></label>
                                        <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/20 transition-all">
                                            <button type="button" onclick="adjustNumber('leadTimeInput', -1)" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                            <input type="number" name="lead_time_hari" id="leadTimeInput" min="1" value="1" class="w-full bg-transparent border-none text-gray-800 dark:text-white text-sm text-center p-2.5 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" required>
                                            <button type="button" onclick="adjustNumber('leadTimeInput', 1)" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0 border-r border-gray-200 dark:border-gray-700"><i class="fas fa-plus text-xs"></i></button>
                                            <div class="px-3 flex items-center bg-gray-50 dark:bg-gray-700/50 h-full shrink-0"><span class="text-gray-500 dark:text-gray-400 text-[10px] font-bold">{{ __('days') }}</span></div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('safety_stock_method') }} <span class="text-red-500">*</span></label>
                                        <select name="tipe_safety_stock" id="tipeSafetyStock" onchange="toggleSafetyStock()" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:border-blue-500 block p-2.5 transition-all font-semibold" required>
                                            <option value="manual">{{ __('manual_fill') }}</option>
                                            <option value="otomatis">{{ __('automatic_system') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="md:col-span-2 transition-all duration-300" id="safetyStockContainer">
                                        <label class="block text-[10px] font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('safety_stock_amount') }} <span class="text-red-500">*</span></label>
                                        <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/20 transition-all">
                                            <button type="button" onclick="adjustNumber('safetyStockInput', -1)" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                            <input type="number" name="safety_stock" id="safetyStockInput" min="0" value="0" class="w-full bg-transparent border-none text-gray-800 dark:text-white text-sm text-center p-2.5 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" onclick="adjustNumber('safetyStockInput', 1)" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                        </div>
                                        <p class="text-[9px] text-gray-500 dark:text-gray-400 mt-1">{{ __('system_warning_desc') }} <strong class="text-blue-600 dark:text-blue-400">{{ __('rop_label') }}</strong>.</p>
                                    </div>

                                    <div class="md:col-span-2 hidden bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-xl border border-emerald-200 dark:border-emerald-800/50" id="safetyStockAutoInfo">
                                        <p class="text-[10px] text-emerald-700 dark:text-emerald-400 font-medium">
                                            <i class="fas fa-magic mr-1"></i> {{ __('system_analysis_desc') }} <strong>{{ __('average_sales') }}</strong> {{ __('and') }} <strong>Safety Stock</strong> {{ __('automatically') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
                <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 shrink-0 flex justify-end gap-3 rounded-b-3xl transition-colors">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl font-bold text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-sm">{{ __('cancel') }}</button>
                    <button type="submit" class="bg-[#D00000] hover:bg-red-800 dark:hover:bg-red-700 text-white px-8 py-3 rounded-xl shadow-md dark:shadow-none font-black flex items-center gap-2 transition-all text-sm hover:-translate-y-0.5">
                        <i class="fas fa-save"></i> {{ __('save_master_data') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL STOK MENIPIS DETAIL --}}
    <div id="modalStokMenipis" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-red-200 dark:border-gray-700 flex justify-between items-center bg-red-50 dark:bg-red-900/20">
                <h3 class="font-black text-red-800 dark:text-red-400 text-xl"><i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> {{ __('low_stock_details') }}</h3>
                <button onclick="closeStokMenipisModal()" class="text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-900/50 hover:bg-red-100 dark:hover:bg-red-900/30 w-8 h-8 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800">
                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="text-[10px] font-black text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 uppercase border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3">{{ __('material_name') }}</th>
                            <th class="px-4 py-3 text-center" title="{{ __('reorder_point') }}">{{ __('reorder_point') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('remaining_stock') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('shortage') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse(isset($products) ? $products->filter(fn($p) => $p->stok <= $p->reorder_point) : [] as $item)
                        <tr class="hover:bg-red-50/50 dark:hover:bg-red-900/10 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $item->nama_barang }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $item->sku }}</p>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400 font-bold">{{ $item->reorder_point }} {{ $item->satuan }}</td>
                            <td class="px-4 py-3 text-center font-black text-red-600 dark:text-red-400">{{ $item->stok }} {{ $item->satuan }}</td>
                            <td class="px-4 py-3 text-center font-bold text-orange-600 dark:text-orange-400">
                                -{{ max(0, $item->reorder_point - $item->stok) }} {{ $item->satuan }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 italic font-medium">{{ __('all_stock_safe') }}</td></tr>
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
        @keyframes dropIn { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    </style>

    <script>
        // ==========================================
        // MENCEGAH FORM SUBMIT SAAT SCANNER FISIK MENEKAN 'ENTER'
        // ==========================================
        document.getElementById('barcodeInput')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault(); 
                document.getElementById('namaMaterialInput').focus();
            }
        });

        // ==========================================
        // 1. FUNGSI FILTER & SORT TABEL
        // ==========================================
        function filterTable() {
            let searchKeyword = document.getElementById('searchInput')?.value.toLowerCase() || '';
            let categoryId = document.getElementById('categoryFilter')?.value || '';
            let sortBy = document.getElementById('sortFilter')?.value || '';
            
            let tbody = document.getElementById('tableBody');
            if(!tbody) return;
            
            let rows = Array.from(tbody.querySelectorAll('.data-row'));
            let visibleCount = 0;

            if (sortBy === 'asc') {
                rows.sort((a, b) => parseInt(a.dataset.stok) - parseInt(b.dataset.stok));
            } else if (sortBy === 'desc') {
                rows.sort((a, b) => parseInt(b.dataset.stok) - parseInt(a.dataset.stok));
            }
            
            rows.forEach(row => tbody.appendChild(row));

            rows.forEach((row, index) => {
                let textContent = row.innerText.toLowerCase();
                let rowCategoryId = row.dataset.category;
                
                let matchesSearch = textContent.includes(searchKeyword);
                let matchesCategory = categoryId === "" || rowCategoryId === categoryId;

                if (matchesSearch && matchesCategory) {
                    row.style.display = '';
                    visibleCount++;
                    let numberCell = row.querySelector('.row-number');
                    if(numberCell) numberCell.innerText = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            let searchEmptyRow = document.getElementById('searchEmptyRow');
            let colCount = '{{ in_array(Auth::user()->role, ['gudang', 'admin']) ? "8" : "7" }}';

            if (visibleCount === 0 && rows.length > 0) {
                if (!searchEmptyRow) {
                    tbody.insertAdjacentHTML('beforeend', `<tr id="searchEmptyRow"><td colspan="${colCount}" class="px-5 py-12 text-center text-gray-500 italic font-medium">{{ __('data_not_found') }}</td></tr>`);
                } else {
                    searchEmptyRow.style.display = '';
                }
            } else if (searchEmptyRow) {
                searchEmptyRow.style.display = 'none';
            }
        }

        document.getElementById('searchInput')?.addEventListener('input', filterTable);
        document.getElementById('categoryFilter')?.addEventListener('change', filterTable);
        document.getElementById('sortFilter')?.addEventListener('change', filterTable);

        // ==========================================
        // FITUR BARU: TOGGLE SAFETY STOCK (MANUAL/OTOMATIS)
        // ==========================================
        function toggleSafetyStock() {
            const tipe = document.getElementById('tipeSafetyStock')?.value;
            const container = document.getElementById('safetyStockContainer');
            const info = document.getElementById('safetyStockAutoInfo');
            const input = document.getElementById('safetyStockInput');
            
            if(!container) return;

            if (tipe === 'otomatis') {
                container.classList.add('hidden');
                info.classList.remove('hidden');
                input.required = false;
            } else {
                container.classList.remove('hidden');
                info.classList.add('hidden');
                input.required = true;
            }
        }

        // ==========================================
        // 2. FUNGSI MODAL TAMBAH & EDIT BARANG
        // ==========================================
        function openModal() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('new_material_form') }}';
            let form = document.getElementById('formPersediaan');
            form.action = "{{ route('persediaan.store') }}";
            form.reset();
            
            document.getElementById('methodContainer').innerHTML = '';
            document.getElementById('stokDisabledInput').value = '{{ __('zero_automatic') }}';
            
            if(document.getElementById('tipeSafetyStock')) {
                document.getElementById('tipeSafetyStock').value = 'manual';
                toggleSafetyStock();
            }
            
            document.getElementById('display_harga_beli').value = '';
            document.getElementById('harga_beli').value = '';
            document.getElementById('display_harga_jual').value = '';
            document.getElementById('harga_jual').value = '';

            document.getElementById('modalBarang').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
            
            setTimeout(() => { document.getElementById('barcodeInput')?.focus(); }, 100);
        }

        function editMaterial(item) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit text-blue-600 dark:text-blue-500 mr-2"></i> {{ __('edit_master_material') }}';
            
            let form = document.getElementById('formPersediaan');
            form.action = `{{ url('persediaan') }}/${item.id}`; 
            
            document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.querySelector('input[name="nama_barang"]').value = item.nama_barang;
            document.querySelector('input[name="barcode"]').value = item.barcode || '';
            document.querySelector('select[name="kategori_id"]').value = item.kategori_id;
            
            if(document.querySelector('input[name="lead_time_hari"]')) {
                document.querySelector('input[name="lead_time_hari"]').value = item.lead_time_hari || 1;
                document.querySelector('select[name="tipe_safety_stock"]').value = item.tipe_safety_stock || 'manual';
                document.querySelector('input[name="safety_stock"]').value = item.safety_stock || 0;
                toggleSafetyStock();
            }

            document.getElementById('harga_beli').value = item.harga_beli;
            document.getElementById('display_harga_beli').value = parseInt(item.harga_beli || 0).toLocaleString('id-ID');
            
            document.getElementById('harga_jual').value = item.harga_jual;
            document.getElementById('display_harga_jual').value = parseInt(item.harga_jual || 0).toLocaleString('id-ID');
            
            document.getElementById('stokDisabledInput').value = item.stok + ' ' + '{{ __('zero_automatic') }}'.substring(2);

            let selectSatuan = document.getElementById('satuanSelect');
            let actualSatuan = document.getElementById('satuanActual');
            
            // Periksa apakah opsi satuan sudah ada di select
            let isStandard = Array.from(selectSatuan.options).some(opt => opt.value === item.satuan);
            
            if (!isStandard && item.satuan) {
                // Tambahkan opsi secara dinamis jika tidak ada
                let newOption = new Option(item.satuan, item.satuan);
                selectSatuan.add(newOption);
            }
            
            selectSatuan.value = item.satuan;
            actualSatuan.value = item.satuan;

            document.getElementById('modalBarang').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }

        function closeModal() {
            document.getElementById('modalBarang')?.classList.add('hidden');
            document.body.style.overflow = 'auto';
            stopScanner(); 
        }

        // ==========================================
        // 3. FUNGSI MODAL STOK MENIPIS
        // ==========================================
        function openStokMenipisModal() {
            document.getElementById('modalStokMenipis').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeStokMenipisModal() {
            document.getElementById('modalStokMenipis').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // ==========================================
        // UI LAINNYA
        // ==========================================
        // Note: handleSatuanChange logic removed since custom text input is replaced by the management modal.

        // ==========================================
        // FORMAT HARGA RUPIAH
        // ==========================================
        function formatPrice(input, hiddenId) {
            let value = input.value.replace(/[^0-9]/g, '');
            document.getElementById(hiddenId).value = value;
            input.value = value ? parseInt(value, 10).toLocaleString('id-ID') : '';
        }

        function adjustPrice(type, amount) {
            const hiddenInput = document.getElementById(type === 'beli' ? 'harga_beli' : 'harga_jual');
            const displayInput = document.getElementById(type === 'beli' ? 'display_harga_beli' : 'display_harga_jual');
            let val = parseInt(hiddenInput.value || '0', 10);
            val += amount;
            if (val < 0) val = 0;
            hiddenInput.value = val;
            displayInput.value = val.toLocaleString('id-ID');
        }

        function adjustNumber(id, amount) {
            const input = document.getElementById(id);
            if(input) {
                let val = parseInt(input.value || '0', 10);
                val += amount;
                const min = parseInt(input.getAttribute('min') || '0', 10);
                if (val < min) val = min;
                input.value = val;
            }
        }

        // ==========================================
        // SWEETALERT CONFIRMATION UNTUK TAMBAH/EDIT & HAPUS BARANG (UTAMA)
        // ==========================================
        document.getElementById('formPersediaan')?.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Data?',
                text: "Pastikan data material yang dimasukkan sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#D00000',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#545454'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Material?',
                text: "Data material yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#545454'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);

        // ==========================================
        // LOGIKA SCANNER KAMERA
        // ==========================================
        let html5QrcodeScanner = null;
        function startScanner() {
            document.getElementById('readerWrapper').classList.remove('hidden');
            if(!html5QrcodeScanner) { 
                html5QrcodeScanner = new Html5Qrcode("reader"); 
            }
            
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 150 }, 
                aspectRatio: 1.0,
                formatsToSupport: [ 
                    Html5QrcodeSupportedFormats.QR_CODE,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.EAN_8,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.UPC_E 
                ]
            };

            html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
            .catch((err) => {
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({ 
                    icon: 'error', 
                    title: '{{ __('camera_failed') }}', 
                    text: '{{ __('camera_permission_msg') }}', 
                    confirmButtonColor: '#D00000',
                    background: isDark ? '#1f2937' : '#fff',
                    color: isDark ? '#f3f4f6' : '#545454'
                });
                stopScanner();
            });
        }
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('barcodeInput').value = decodedText;
            stopScanner();
            document.getElementById('namaMaterialInput').focus();
            
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({ 
                icon: 'success', 
                title: '{{ __('barcode_read') }}', 
                text: decodedText, 
                timer: 1500, 
                showConfirmButton: false,
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f3f4f6' : '#545454'
            });
        }
        function onScanFailure(error) {}
        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => { document.getElementById('readerWrapper').classList.add('hidden'); }).catch((err) => {});
            } else { document.getElementById('readerWrapper').classList.add('hidden'); }
        }

        // ==========================================
        // KELOLA SATUAN & KATEGORI (AJAX)
        // ==========================================
        function openKategoriModal() { document.getElementById('modalKelolaKategori').classList.remove('hidden'); }
        function closeKategoriModal() { document.getElementById('modalKelolaKategori').classList.add('hidden'); }
        function openSatuanModal() { document.getElementById('modalKelolaSatuan').classList.remove('hidden'); }
        function closeSatuanModal() { document.getElementById('modalKelolaSatuan').classList.add('hidden'); }

        async function submitKategoriAjax(e) {
            e.preventDefault();
            const prefix = document.getElementById('kategoriPrefix').value;
            const nama = document.getElementById('kategoriNama').value;
            
            try {
                const res = await fetch('{{ route("kategori.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ prefix_sku: prefix, nama_kategori: nama })
                });
                const data = await res.json();
                if(data.success) {
                    // Update table
                    document.getElementById('tableKategoriAjax').insertAdjacentHTML('beforeend', `
                        <tr id="kat-row-${data.data.id}" class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-2 font-mono font-bold">${data.data.prefix_sku}</td>
                            <td class="px-4 py-2">${data.data.nama_kategori}</td>
                            <td class="px-4 py-2 text-right">
                                <button type="button" onclick="deleteKategoriAjax(${data.data.id})" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                    // Update select
                    document.getElementById('kategoriSelect').insertAdjacentHTML('beforeend', `<option value="${data.data.id}">${data.data.nama_kategori} (${data.data.prefix_sku})</option>`);
                    document.getElementById('kategoriSelect').value = data.data.id;
                    document.getElementById('formKategoriAjax').reset();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Kategori berhasil ditambahkan!', timer: 1500, showConfirmButton: false });
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal menyimpan kategori' }); }
            } catch (err) { Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Terjadi kesalahan jaringan.' }); }
        }

        async function deleteKategoriAjax(id) {
            const result = await Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });
            
            if(!result.isConfirmed) return;
            
            try {
                const res = await fetch(`{{ url('kategori') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if(data.success) {
                    document.getElementById(`kat-row-${id}`).remove();
                    let select = document.getElementById('kategoriSelect');
                    for (let i=0; i<select.options.length; i++) {
                        if (select.options[i].value == id) select.remove(i);
                    }
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Kategori berhasil dihapus!', timer: 1500, showConfirmButton: false });
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal menghapus' }); }
            } catch(err) { Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Terjadi kesalahan jaringan.' }); }
        }

        async function submitSatuanAjax(e) {
            e.preventDefault();
            const nama = document.getElementById('satuanNama').value;
            
            try {
                const res = await fetch('{{ route("satuan.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nama_satuan: nama })
                });
                const data = await res.json();
                if(data.success) {
                    document.getElementById('tableSatuanAjax').insertAdjacentHTML('beforeend', `
                        <tr id="sat-row-${data.data.id}" class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-2 font-bold">${data.data.nama_satuan}</td>
                            <td class="px-4 py-2 text-right">
                                <button type="button" onclick="deleteSatuanAjax(${data.data.id})" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `);
                    document.getElementById('satuanSelect').insertAdjacentHTML('beforeend', `<option value="${data.data.nama_satuan}">${data.data.nama_satuan}</option>`);
                    document.getElementById('satuanSelect').value = data.data.nama_satuan;
                    document.getElementById('satuanActual').value = data.data.nama_satuan;
                    document.getElementById('formSatuanAjax').reset();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Satuan berhasil ditambahkan!', timer: 1500, showConfirmButton: false });
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal menyimpan satuan' }); }
            } catch (err) { Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Terjadi kesalahan jaringan.' }); }
        }

        async function deleteSatuanAjax(id) {
            const result = await Swal.fire({
                title: 'Hapus Satuan?',
                text: "Satuan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });
            
            if(!result.isConfirmed) return;
            
            try {
                const res = await fetch(`{{ url('satuan') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if(data.success) {
                    let deletedName = document.getElementById(`sat-row-${id}`).querySelector('td').innerText;
                    document.getElementById(`sat-row-${id}`).remove();
                    let select = document.getElementById('satuanSelect');
                    for (let i=0; i<select.options.length; i++) {
                        if (select.options[i].value == deletedName) select.remove(i);
                    }
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Satuan berhasil dihapus!', timer: 1500, showConfirmButton: false });
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal menghapus' }); }
            } catch(err) { Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Terjadi kesalahan jaringan.' }); }
        }
    </script>

    {{-- MODAL KELOLA KATEGORI (AJAX) --}}
    <div id="modalKelolaKategori" class="fixed inset-0 bg-black/60 hidden z-[110] flex items-center justify-center backdrop-blur-sm p-4 transition-all overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-2xl shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900 rounded-t-3xl">
                <h3 class="font-black text-gray-800 dark:text-white text-lg"><i class="fas fa-tags text-blue-600 dark:text-blue-500 mr-2"></i> Kelola Kategori</h3>
                <button type="button" onclick="closeKategoriModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5 flex flex-col gap-4">
                <form id="formKategoriAjax" onsubmit="submitKategoriAjax(event)" class="flex gap-2">
                    <input type="text" id="kategoriPrefix" placeholder="Prefix (cth: ELK)" class="w-1/4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500" required>
                    <input type="text" id="kategoriNama" placeholder="Nama Kategori Baru" class="w-2/4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500" required>
                    <button type="submit" class="w-1/4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-500/30">Tambah</button>
                </form>
                <div class="overflow-y-auto max-h-60 border border-gray-200 dark:border-gray-700 rounded-xl custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 text-[10px] uppercase font-black text-gray-500">
                            <tr><th class="px-4 py-2">Prefix</th><th class="px-4 py-2">Nama Kategori</th><th class="px-4 py-2 text-right">Aksi</th></tr>
                        </thead>
                        <tbody id="tableKategoriAjax">
                            @foreach($kategoris ?? [] as $k)
                            <tr id="kat-row-{{ $k->id }}" class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-2 font-mono font-bold">{{ $k->prefix_sku }}</td>
                                <td class="px-4 py-2">{{ $k->nama_kategori }}</td>
                                <td class="px-4 py-2 text-right">
                                    <button type="button" onclick="deleteKategoriAjax({{ $k->id }})" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KELOLA SATUAN (AJAX) --}}
    <div id="modalKelolaSatuan" class="fixed inset-0 bg-black/60 hidden z-[110] flex items-center justify-center backdrop-blur-sm p-4 transition-all overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-xl shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900 rounded-t-3xl">
                <h3 class="font-black text-gray-800 dark:text-white text-lg"><i class="fas fa-balance-scale text-blue-600 dark:text-blue-500 mr-2"></i> Kelola Satuan (UoM)</h3>
                <button type="button" onclick="closeSatuanModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5 flex flex-col gap-4">
                <form id="formSatuanAjax" onsubmit="submitSatuanAjax(event)" class="flex gap-2">
                    <input type="text" id="satuanNama" placeholder="Nama Satuan Baru (cth: Kodi)" class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500" required>
                    <button type="submit" class="w-32 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-blue-500/30">Tambah</button>
                </form>
                <div class="overflow-y-auto max-h-60 border border-gray-200 dark:border-gray-700 rounded-xl custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 text-[10px] uppercase font-black text-gray-500">
                            <tr><th class="px-4 py-2">Nama Satuan</th><th class="px-4 py-2 text-right">Aksi</th></tr>
                        </thead>
                        <tbody id="tableSatuanAjax">
                            @foreach($satuans ?? [] as $s)
                            <tr id="sat-row-{{ $s->id }}" class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-2 font-bold">{{ $s->nama_satuan }}</td>
                                <td class="px-4 py-2 text-right">
                                    <button type="button" onclick="deleteSatuanAjax({{ $s->id }})" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>