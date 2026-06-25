<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS: bg-gray-100 agar terpisah jelas dengan card putih --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 transition-colors duration-300">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors" title="{{ __('to_dashboard') }}">
                                <i class="fas fa-home text-sm"></i>
                            </a> 
                            <span>/</span> 
                            <span>{{ __('master_data') }}</span> 
                            <span>/</span> 
                            <span class="text-[#D00000] dark:text-red-400">{{ __('item_category') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('category_classification') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('category_desc') }}</p>
                    </div>
                    
                    {{-- TOMBOL UNTUK GUDANG & ADMIN --}}
                    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
                    <button onclick="openModal()" class="bg-[#D00000] hover:bg-red-800 dark:hover:bg-red-700 text-white px-5 py-3 rounded-2xl shadow-lg shadow-red-900/20 dark:shadow-none font-bold flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1.5 card-shadow-red">
                        <i class="fas fa-layer-group text-lg"></i> {{ __('add_category') }}
                    </button>
                    @endif
                </div>

                {{-- NOTIFIKASI SUCCESS & ERROR --}}
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm flex items-center transition-colors">
                        <i class="fas fa-check-circle text-xl mr-3"></i> <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm transition-colors">
                        <ul class="list-disc pl-5 text-sm font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- WIDGET STATISTIK --}}
                {{-- PERBAIKAN KONTRAS: border-gray-200 dan shadow-md --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1 card-shadow-teal cursor-default hover:bg-teal-50/50 dark:hover:bg-teal-900/20 hover:border-teal-200 dark:hover:border-teal-800/50 group">
                        <div class="w-14 h-14 bg-teal-50 dark:bg-teal-900/30 text-teal-500 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">{{ __('total_category') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-teal-600 dark:hover:text-teal-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Total grup kategori klasifikasi material yang tersedia.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white group-hover:text-teal-700 dark:group-hover:text-teal-300 transition-colors">{{ $totalKategori ?? 0 }} <span class="text-sm font-semibold text-gray-500 group-hover:text-teal-500">{{ __('group') }}</span></h3>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 opacity-50 transition-all duration-300 transform hover:-translate-y-1 hover:opacity-100 card-shadow-blue cursor-default hover:bg-blue-50/50 dark:hover:bg-blue-900/20 hover:border-blue-200 dark:hover:border-blue-800/50 group">
                        <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-blue-500 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ __('most_crowded_group') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Kategori yang memiliki jumlah material terdaftar paling banyak.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-lg font-black text-gray-800 dark:text-gray-300 leading-tight group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">- <span class="text-xs font-semibold text-blue-500 dark:text-blue-400 block">{{ __('no_item_data') }}</span></h3>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 opacity-50 transition-all duration-300 transform hover:-translate-y-1 hover:opacity-100 card-shadow-gray cursor-default hover:bg-gray-50/80 dark:hover:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600 group">
                        <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors">{{ __('empty_category') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Kategori yang belum memiliki material terdaftar di dalamnya.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">- <span class="text-sm font-semibold text-gray-500 group-hover:text-gray-600">{{ __('group') }}</span></h3>
                        </div>
                    </div>
                </div>

                {{-- TABEL KATEGORI --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-800/50 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <input type="text" id="searchInput" placeholder="{{ __('search_category') }}" class="w-full pl-9 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-[#D00000] dark:focus:border-red-500 block p-2.5 transition-all dark:placeholder-gray-400">
                        </div>
                    </div>

                    <div class="overflow-x-auto min-h-[300px]">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-5 py-4 w-16 text-center">{{ __('number_abbr') }}</th>
                                    <th class="px-5 py-4">{{ __('sku_prefix') }}</th>
                                    <th class="px-5 py-4 min-w-[200px]">{{ __('category_name') }}</th>
                                    <th class="px-5 py-4 w-1/3">{{ __('description') }}</th>
                                    <th class="px-5 py-4 text-center">{{ __('total_material') }}</th>
                                    
                                    {{-- KOLOM AKSI HANYA UNTUK GUDANG & ADMIN --}}
                                    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
                                        <th class="px-5 py-4 text-center w-24">{{ __('action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="kategoriTableBody">
                                @forelse($kategoris ?? [] as $index => $kat)
                                    {{-- PERBAIKAN KONTRAS: border-gray-100 agar garis antar baris terlihat --}}
                                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors data-row">
                                        <td class="px-5 py-4 text-center font-medium text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-5 py-4">
                                            <span class="bg-[#1e1e2d] dark:bg-black text-white font-mono text-xs px-2.5 py-1 rounded font-bold tracking-widest">{{ $kat->prefix_sku }}</span>
                                        </td>
                                        <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-200 text-base">{{ $kat->nama_kategori }}</td>
                                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400 text-xs">{{ $kat->deskripsi ?: '-' }}</td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 font-black px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-600">0</span>
                                        </td>
                                        
                                        {{-- TOMBOL AKSI HANYA UNTUK GUDANG & ADMIN --}}
                                        @if(in_array(Auth::user()->role, ['gudang', 'admin']))
                                            <td class="px-5 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button" onclick="openEditModal({{ $kat->id }}, '{{ addslashes($kat->nama_kategori) }}', '{{ addslashes($kat->prefix_sku) }}', '{{ addslashes($kat->deskripsi) }}')" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 w-8 h-8 rounded-lg transition-colors border border-blue-100 dark:border-blue-800"><i class="fas fa-edit"></i></button>
                                                    <form action="{{ route('kategori.destroy', $kat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 w-8 h-8 rounded-lg transition-colors border border-red-100 dark:border-red-800"><i class="fas fa-trash-alt"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr id="emptyDataRow">
                                        <td colspan="6" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500 italic">{{ __('no_category_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH (HANYA UNTUK GUDANG & ADMIN) --}}
    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
    <div id="modalKategori" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 shrink-0">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-layer-group text-[#D00000] mr-2"></i> {{ __('add_category') }}</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('kategori.store') ?? '#' }}" method="POST">
                @csrf
                <div class="p-6 space-y-5 bg-white dark:bg-gray-800">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('category_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kategori" placeholder="Cth: Alat Teknik & Listrik" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] block p-3 transition-all dark:placeholder-gray-400" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('sku_prefix_label') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="prefix_sku" maxlength="5" placeholder="{{ __('max_5_letters') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white font-mono text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] block p-3 transition-all uppercase dark:placeholder-gray-400" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">{{ __('example_alk') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('short_description') }}</label>
                        <textarea name="deskripsi" rows="3" placeholder="{{ __('category_desc_placeholder') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] block p-3 transition-all dark:placeholder-gray-400"></textarea>
                    </div>
                </div>
                
                <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 shrink-0 flex justify-end gap-3 rounded-b-3xl">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-sm">{{ __('cancel') }}</button>
                    <button type="submit" class="bg-[#D00000] hover:bg-red-800 dark:hover:bg-red-700 text-white px-8 py-3 rounded-xl shadow-lg shadow-red-900/30 dark:shadow-none font-black flex items-center gap-2 transition-all text-sm">
                        <i class="fas fa-save"></i> {{ __('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL EDIT (HANYA UNTUK GUDANG & ADMIN) --}}
    @if(in_array(Auth::user()->role, ['gudang', 'admin']))
    <div id="modalEditKategori" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 shrink-0">
                <h3 class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-edit text-blue-500 mr-2"></i> {{ __('edit_category') ?? 'Edit Kategori' }}</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="formEditKategori" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5 bg-white dark:bg-gray-800">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('category_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nama_kategori" name="nama_kategori" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-blue-500 block p-3 transition-all dark:placeholder-gray-400" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('sku_prefix_label') ?? 'Prefix SKU' }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="edit_prefix_sku" name="prefix_sku" maxlength="5" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white font-mono text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-blue-500 block p-3 transition-all uppercase dark:placeholder-gray-400" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('short_description') }}</label>
                        <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-blue-500 block p-3 transition-all dark:placeholder-gray-400"></textarea>
                    </div>
                </div>
                
                <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 shrink-0 flex justify-end gap-3 rounded-b-3xl">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-sm">{{ __('cancel') }}</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-8 py-3 rounded-xl shadow-lg shadow-blue-900/30 dark:shadow-none font-black flex items-center gap-2 transition-all text-sm">
                        <i class="fas fa-save"></i> {{ __('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

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
        // FUNGSI LIVE SEARCH PADA TABEL
        document.getElementById('searchInput')?.addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#kategoriTableBody .data-row');
            let visibleCount = 0;

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            let tbody = document.getElementById('kategoriTableBody');
            let searchEmptyRow = document.getElementById('searchEmptyRow');

            if (visibleCount === 0 && rows.length > 0) {
                if (!searchEmptyRow) {
                    tbody.insertAdjacentHTML('beforeend', `<tr id="searchEmptyRow"><td colspan="6" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500 italic font-medium">Pencarian "${this.value}" tidak ditemukan.</td></tr>`);
                } else {
                    searchEmptyRow.style.display = '';
                    searchEmptyRow.innerHTML = `<td colspan="6" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500 italic font-medium">Pencarian "${this.value}" tidak ditemukan.</td>`;
                }
            } else if (searchEmptyRow) {
                searchEmptyRow.style.display = 'none';
            }
        });

        // (Fungsi UI Dropdown & Sidebar dikelola secara global di layouts.header)

        // FUNGSI MODAL TAMBAH KATEGORI (TIDAK DIUBAH)
        function openModal() {
            document.getElementById('modalKategori').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }
        function closeModal() {
            document.getElementById('modalKategori').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // FUNGSI MODAL EDIT KATEGORI
        function openEditModal(id, nama, prefix, deskripsi) {
            document.getElementById('modalEditKategori').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
            
            document.getElementById('formEditKategori').action = `/kategori/${id}`;
            document.getElementById('edit_nama_kategori').value = nama;
            document.getElementById('edit_prefix_sku').value = prefix;
            document.getElementById('edit_deskripsi').value = deskripsi === '-' ? '' : deskripsi;
        }
        function closeEditModal() {
            document.getElementById('modalEditKategori').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>