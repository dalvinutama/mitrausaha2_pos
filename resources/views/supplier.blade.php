<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-8 transition-colors duration-300">
                
                {{-- HEADER HALAMAN --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors"><i class="fas fa-home text-sm"></i></a> 
                             <span>{{ __('master_data') }}</span> <span>/</span> <span class="text-[#D00000] dark:text-red-400">{{ __('supplier_directory') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('partner_supplier') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('supplier_desc') }}</p>
                    </div>
                    
                    {{-- TOMBOL TAMBAH UNTUK OWNER, ADMIN, GUDANG --}}
                    @if(in_array(Auth::user()->role, ['owner', 'admin', 'gudang']))
                    <button onclick="openModal()" class="bg-[#D00000] hover:bg-red-800 dark:hover:bg-red-700 text-white px-5 py-3 rounded-2xl shadow-md dark:shadow-none font-bold flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1.5 card-shadow-red">
                        <i class="fas fa-truck-loading text-lg"></i> {{ __('add_new_partner') }}
                    </button>
                    @endif
                </div>

                {{-- WIDGET RINGKASAN --}}
                {{-- PERBAIKAN KONTRAS: border-gray-200 dan shadow-md --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1 card-shadow-blue cursor-default hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 hover:border-indigo-200 dark:hover:border-indigo-800/50 group">
                        <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ __('total_partners') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Total perusahaan mitra pemasok barang yang aktif terdaftar dalam sistem.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors">{{ $totalSupplier }} <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 group-hover:text-indigo-400">{{ __('factory') }}</span></h3>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1 card-shadow-orange cursor-default hover:bg-orange-50/50 dark:hover:bg-orange-900/20 hover:border-orange-200 dark:hover:border-orange-800/50 group">
                        <div class="w-14 h-14 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">{{ __('delivery_this_month') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-orange-600 dark:hover:text-orange-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Jumlah pesanan stok masuk yang berhasil diterima pada bulan berjalan ini.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white group-hover:text-orange-700 dark:group-hover:text-orange-300 transition-colors">{{ $pengirimanBulanIni }} <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 group-hover:text-orange-400">{{ __('order') }}</span></h3>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1 card-shadow-red cursor-default hover:bg-red-50/50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-800/50 group">
                        <div class="w-14 h-14 bg-red-50 dark:bg-red-900/30 text-[#D00000] dark:text-red-500 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">{{ __('estimated_debt') }}</p>
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition-colors text-[10px] text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Perkiraan jumlah hutang yang belum dibayarkan ke supplier (berdasarkan invoice PO).
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </div>
                            <h3 class="text-xl font-black text-gray-800 dark:text-white leading-tight group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">Rp {{ number_format($totalHutang, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                {{-- TABEL DATA --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-wider bg-gray-200/60 dark:bg-gray-700/50 border-b border-gray-300 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-5 py-4 w-16 text-center">{{ __('number_abbr') }}</th>
                                    <th class="px-5 py-4 min-w-[200px]">{{ __('company_details') }}</th>
                                    <th class="px-5 py-4">{{ __('pic_contact') }}</th>
                                    <th class="px-5 py-4">{{ __('main_supply') }}</th>
                                    <th class="px-5 py-4">{{ __('estimasi_termin') ?? 'Termin' }}</th>
                                    <th class="px-5 py-4 text-center">{{ __('status') }}</th>
                                    @if(in_array(Auth::user()->role, ['owner', 'admin', 'gudang']))
                                    <th class="px-5 py-4 text-center w-24">{{ __('action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $sup)
                                {{-- PERBAIKAN KONTRAS TABEL: border-gray-200 --}}
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-5 py-4 text-center font-medium text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-black text-gray-800 dark:text-gray-200 text-base uppercase tracking-tight">{{ $sup->nama_supplier }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 italic line-clamp-1"><i class="fas fa-location-dot mr-1 text-red-500 dark:text-red-500"></i>{{ $sup->alamat ?: __('address_not_set') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800 dark:text-gray-300">{{ $sup->nama_pic }}</span>
                                            <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold"><i class="fab fa-whatsapp mr-1"></i>{{ $sup->no_hp }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-black px-2.5 py-1 rounded-lg border border-indigo-200 dark:border-indigo-800/50 uppercase italic">
                                            {{ $sup->kategori_suplai ?: 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                            @if(strtolower($sup->termin_default) === 'cash' || empty($sup->termin_default))
                                                {{ __('cash') }}
                                            @else
                                                {{ $sup->termin_default }} {{ __('days') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-sm {{ $sup->status === 'Aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50' }}">
                                            {{ $sup->status }}
                                        </span>
                                    </td>
                                    @if(in_array(Auth::user()->role, ['owner', 'admin', 'gudang']))
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- TOMBOL EDIT --}}
                                            <button onclick="editSupplier({{ json_encode($sup) }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 w-8 h-8 rounded-lg transition-colors border border-blue-200 dark:border-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            {{-- TOMBOL HAPUS --}}
                                            <form action="{{ route('supplier.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus supplier ini? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 w-8 h-8 rounded-lg transition-colors border border-red-200 dark:border-red-800">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400 italic font-medium">{{ __('no_supplier_data') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH & EDIT (KHUSUS OWNER, ADMIN, GUDANG) --}}
    @if(in_array(Auth::user()->role, ['owner', 'admin', 'gudang']))
    <div id="modalSupplier" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col max-h-[90vh]">
            {{-- PERBAIKAN KONTRAS MODAL: bg-gray-100 --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 shrink-0 transition-colors">
                <h3 id="modalTitle" class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-truck-fast text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('supplier_registration') }}</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors border border-transparent hover:border-red-200 dark:hover:border-red-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="formSupplier" action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div id="methodContainer"></div> {{-- Tempat menaruh @method('PUT') secara dinamis via JS --}}

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5 bg-white dark:bg-gray-800 overflow-y-auto custom-scrollbar transition-colors">
                    {{-- Informasi Perusahaan --}}
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-[#D00000] dark:text-red-500 uppercase tracking-widest border-b border-gray-200 dark:border-gray-700 pb-2 transition-colors">{{ __('company_info') }}</h4>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('company_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_supplier" placeholder="{{ __('example_company') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all uppercase dark:placeholder-gray-500" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('main_supply_category') }}</label>
                            <input type="text" name="kategori_suplai" placeholder="{{ __('example_supply') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all uppercase dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('estimasi_termin') ?? 'Estimasi Termin' }}</label>
                            <div class="flex items-center w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-[#D00000] focus-within:ring-4 focus-within:ring-red-500/10 transition-all">
                                <select id="termin_type" name="termin_type" onchange="toggleTerminInput()" class="bg-gray-100 dark:bg-gray-800 border-none text-gray-800 dark:text-white text-sm py-3 pl-3 pr-6 focus:ring-0 cursor-pointer border-r border-gray-300 dark:border-gray-600 font-bold focus:outline-none shrink-0 max-w-[120px] sm:max-w-none">
                                    <option value="cash">{{ __('cash') }}</option>
                                    <option value="kredit">{{ __('credit') }}</option>
                                </select>
                                
                                <div id="termin_number_wrapper" class="flex-1 items-center hidden relative">
                                    <button type="button" onclick="adjustTermin(-1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors ml-1 sm:ml-2 shrink-0">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    
                                    <input type="number" id="termin_number" name="termin_number" placeholder="30" class="min-w-[40px] w-full text-center bg-transparent border-none text-gray-800 dark:text-white font-black text-base px-0 py-2 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" min="1">
                                    
                                    <button type="button" onclick="adjustTermin(1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors shrink-0">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>

                                    <div class="h-5 w-px bg-gray-300 dark:bg-gray-600 mx-1 sm:mx-2 shrink-0"></div>
                                    <span class="pr-3 text-gray-500 dark:text-gray-400 text-[10px] sm:text-xs font-black uppercase tracking-wider whitespace-nowrap shrink-0">{{ __('days') }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('full_address') }}</label>
                            <textarea name="alamat" rows="3" placeholder="{{ __('address_placeholder') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500"></textarea>
                        </div>
                    </div>

                    {{-- Kontak Person --}}
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-[#D00000] dark:text-red-500 uppercase tracking-widest border-b border-gray-200 dark:border-gray-700 pb-2 transition-colors">{{ __('pic_person') }}</h4>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('pic_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pic" placeholder="{{ __('example_pic') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">{{ __('whatsapp_number') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" placeholder="0812xxxx" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required>
                        </div>
                    </div>
                </div>
                
                {{-- PERBAIKAN KONTRAS MODAL: bg-gray-100 --}}
                <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 shrink-0 flex justify-end gap-3 rounded-b-3xl transition-colors">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl font-bold text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors text-sm">{{ __('cancel') }}</button>
                    <button type="submit" class="bg-[#D00000] hover:bg-red-800 dark:hover:bg-red-700 text-white px-8 py-3 rounded-xl shadow-md dark:shadow-none font-black flex items-center gap-2 transition-all text-sm uppercase hover:-translate-y-0.5">
                        <i class="fas fa-save"></i> {{ __('save_data') }}
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
        // FUNGSI HEADER DROPDOWN
        // (Fungsi UI Dropdown & Sidebar dikelola secara global di layouts.header)

        // FUNGSI MODAL & SIDEBAR
        function openModal() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-truck-fast text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('supplier_registration') }}';
            
            let form = document.getElementById('formSupplier');
            form.action = "{{ route('supplier.store') }}";
            form.reset();
            document.getElementById('methodContainer').innerHTML = ''; // Kosongkan method (kembali ke POST)

            // Reset termin
            document.getElementById('termin_type').value = 'cash';
            document.getElementById('termin_number').value = '';
            toggleTerminInput();

            document.getElementById('modalSupplier').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }

        // FUNGSI EDIT SUPPLIER
        function editSupplier(supplier) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit text-blue-600 dark:text-blue-500 mr-2"></i> {{ __('edit_supplier_data') }}';
            
            let form = document.getElementById('formSupplier');
            // Arahkan form action ke route update dengan ID supplier
            form.action = `{{ url('supplier') }}/${supplier.id}`; 
            
            // Tambahkan method spoofing PUT untuk update data di Laravel
            document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Isi form dengan data yang ada
            document.querySelector('input[name="nama_supplier"]').value = supplier.nama_supplier || '';
            document.querySelector('input[name="kategori_suplai"]').value = supplier.kategori_suplai || '';
            
            // Logika Termin
            if (supplier.termin_default && supplier.termin_default.toString().toLowerCase() !== 'cash') {
                document.getElementById('termin_type').value = 'kredit';
                document.getElementById('termin_number').value = supplier.termin_default;
            } else {
                document.getElementById('termin_type').value = 'cash';
                document.getElementById('termin_number').value = '';
            }
            toggleTerminInput();

            document.querySelector('textarea[name="alamat"]').value = supplier.alamat || '';
            document.querySelector('input[name="nama_pic"]').value = supplier.nama_pic || '';
            document.querySelector('input[name="no_hp"]').value = supplier.no_hp || '';

            document.getElementById('modalSupplier').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }

        function closeModal() {
            document.getElementById('modalSupplier').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function toggleTerminInput() {
            const type = document.getElementById('termin_type').value;
            const wrapper = document.getElementById('termin_number_wrapper');
            const numberInput = document.getElementById('termin_number');
            if(type === 'kredit') {
                wrapper.classList.remove('hidden');
                wrapper.classList.add('flex');
                numberInput.required = true;
                if(!numberInput.value) numberInput.value = 30; // Default angka jika kosong
            } else {
                wrapper.classList.add('hidden');
                wrapper.classList.remove('flex');
                numberInput.required = false;
            }
        }

        function adjustTermin(amount) {
            const input = document.getElementById('termin_number');
            let val = parseInt(input.value) || 0;
            val += amount;
            if (val < 1) val = 1;
            input.value = val;
        }

        // Sidebar dikelola di layouts.header
        
        @if(session('success'))
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#D00000',
                borderRadius: '1.5rem',
                background: bgPopup,
                color: colorText
            });
        @endif
    </script>
</x-app-layout>