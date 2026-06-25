<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS: Menggunakan bg-gray-100 --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 text-gray-800 dark:text-gray-200 transition-colors duration-300">
                
                {{-- HEADER HALAMAN --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-2">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors"><i class="fas fa-home text-sm text-[#D00000] dark:text-red-400"></i></a> 
                            <span>/</span> 
                            <span>Sistem</span> 
                            <span>/</span> 
                            <span class="text-[#D00000] dark:text-red-400">{{ __('system_settings') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('system_settings') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('system_settings_desc') }}</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl mb-6 shadow-sm flex items-center transition-colors">
                        <i class="fas fa-check-circle text-xl mr-3"></i>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl mb-6 shadow-sm flex items-center transition-colors">
                        <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                        <p class="font-bold text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                {{-- ======================================================== --}}
                {{-- BAGIAN 1: IDENTITAS APLIKASI (UNTUK SIDEBAR) --}}
                {{-- ======================================================== --}}
                {{-- BAGIAN 1: IDENTITAS APLIKASI (UNTUK SIDEBAR) --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col mb-6">
                    <div class="flex items-center gap-3 mb-5 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-500">
                            <i class="fas fa-laptop-code text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">{{ __('app_identity_sidebar') }}</h3>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ __('app_identity_desc') ?? 'Atur nama dan logo aplikasi Anda' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        {{-- Form ini arahkan ke fungsi update pengaturan globalmu --}}
                        <form action="{{ route('pengaturan.aplikasi') ?? '#' }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                            @csrf 
                            
                            {{-- LOGO SIDEBAR --}}
                            <div class="md:col-span-3 flex flex-col items-center text-center gap-4">
                                <div class="relative w-28 h-28">
                                    <div class="relative w-full h-full rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center overflow-hidden group cursor-pointer" onclick="document.getElementById('inputLogoUtama').click()">
                                        <img id="previewLogoUtama" src="{{ asset('storage/logos/logo-utama.png') }}?v={{ time() }}" onerror="this.src='{{ asset('images/mu2.jpeg') }}'" alt="Logo Sidebar" class="w-full h-full object-cover">
                                        
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-300 backdrop-blur-[2px]">
                                            <i class="fas fa-camera text-white text-xl mb-1"></i>
                                            <span class="text-white text-[10px] font-bold">{{ __('change') ?? 'Ubah' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input type="file" id="inputLogoUtama" name="logo_utama" accept="image/*" class="hidden" onchange="previewUtama(this)">
                                    <button type="button" onclick="document.getElementById('inputLogoUtama').click()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800/50 px-3 py-1.5 rounded-lg transition-all shadow-sm">{{ __('choose_file') ?? 'Pilih Gambar' }}</button>
                                </div>
                            </div>

                            {{-- NAMA TOKO SIDEBAR --}}
                            <div class="md:col-span-9 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('app_name_in_sidebar') }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_aplikasi" value="{{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-bold rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:border-indigo-500 dark:focus:border-indigo-500 block p-2.5 transition-all outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('short_description_under_store_name') }}</label>
                                    <input type="text" name="tagline_aplikasi" value="{{ config('aplikasi.tagline_aplikasi', __('management_system')) }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:border-indigo-500 dark:focus:border-indigo-500 block p-2.5 transition-all outline-none">
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all duration-300 text-xs shadow-sm flex items-center gap-2">
                                        <i class="fas fa-save"></i> {{ __('save_changes') ?? 'Simpan Perubahan' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- BAGIAN 2: PROFIL CABANG (UNTUK KOP SURAT LAPORAN) --}}
                {{-- ======================================================== --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col">
                    <div class="flex items-center justify-between mb-5 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500">
                                <i class="fas fa-file-invoice text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">{{ __('letterhead_profile') }}</h3>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ __('letterhead_profile_desc') ?? 'Kelola profil cabang untuk cetak laporan PDF' }}</p>
                            </div>
                        </div>
                        <button onclick="openModal('add')" class="bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-500 hover:text-white dark:bg-emerald-900/30 dark:border-emerald-800/50 dark:text-emerald-400 dark:hover:bg-emerald-600 dark:hover:text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2 transition-all text-xs shadow-sm">
                            <i class="fas fa-plus"></i> {{ __('add_profile') }}
                        </button>
                    </div>

                    <div id="profilContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 relative">
                        @forelse($profiles ?? [] as $prof)
                            <div id="profile-card-{{ $prof->id }}" onclick="setActiveProfile({{ $prof->id }})" class="profile-card bg-gray-50 dark:bg-gray-700/30 rounded-2xl border {{ $prof->is_active ? 'border-emerald-500 ring-1 ring-emerald-500 shadow-md' : 'border-gray-200 dark:border-gray-700 shadow-sm' }} flex flex-col transition-all duration-300 hover:shadow-md cursor-pointer relative overflow-hidden group">
                                
                                {{-- INDIKATOR AKTIF --}}
                                <div class="absolute top-4 right-4 z-10">
                                    <div id="profile-check-{{ $prof->id }}" class="profile-check w-5 h-5 rounded border {{ $prof->is_active ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-600 text-transparent' }} flex items-center justify-center transition-colors shadow-sm">
                                        <i class="fas fa-check text-[10px]"></i>
                                    </div>
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="flex items-start gap-3 mb-4">
                                        @if($prof->logo)
                                            <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm">
                                                <img src="{{ asset('storage/logos/' . $prof->logo) }}?v={{ time() }}" alt="Logo" class="w-full h-full object-contain p-1">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-white dark:bg-gray-800 text-gray-400 border border-gray-200 dark:border-gray-600 shadow-sm">
                                                <i class="fas fa-building text-lg"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="pr-6">
                                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 leading-tight">{{ $prof->nama_toko }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $prof->tagline ?: __('no_tagline') }}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-1 mt-2 text-[11px] text-gray-600 dark:text-gray-400 flex-1">
                                        <p class="flex items-start gap-2"><i class="fas fa-map-marker-alt mt-0.5 w-3 text-center"></i> <span>{{ $prof->alamat }}</span></p>
                                        <p class="flex items-start gap-2"><i class="fas fa-phone-alt mt-0.5 w-3 text-center"></i> <span>{{ $prof->telepon ?: '-' }}</span></p>
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600/50 flex justify-between items-center">
                                        <div>
                                            <p class="text-[9px] font-bold text-gray-500 uppercase">{{ __('signature:') }}</p>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $prof->nama_kepala_gudang ?: '.....' }} <span class="text-gray-500 font-normal">({{ $prof->kota_ttd }})</span></p>
                                        </div>
                                        
                                        {{-- Aksi Edit & Delete --}}
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" onclick="event.stopPropagation(); openModal('edit', {{ json_encode($prof) }})" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white flex items-center justify-center transition-colors" title="{{ __('edit') }}">
                                                <i class="fas fa-edit text-[10px]"></i>
                                            </button>
                                            
                                            <form action="{{ route('pengaturan.destroy', $prof->id) }}" method="POST" class="m-0" onsubmit="return confirm('{{ __('sure_delete_letterhead_profile') }}');">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="event.stopPropagation();" class="w-7 h-7 rounded bg-red-50 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white flex items-center justify-center transition-colors" title="{{ __('delete') }}">
                                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 p-8 text-center transition-colors">
                                <i class="fas fa-file-invoice text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">{{ __('no_branch_profile_yet') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div> {{-- End Wrapper Kop Surat --}}

                {{-- ======================================================== --}}
                {{-- BAGIAN 3: PENGATURAN TEMPLATE EMAIL NOTIFIKASI           --}}
                {{-- ======================================================== --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col mt-6 mb-6">
                    <div class="flex items-center gap-3 mb-5 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center text-orange-500">
                            <i class="fas fa-envelope-open-text text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">Pengaturan Template Email</h3>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Atur visual dan konten teks untuk semua notifikasi sistem via Email</p>
                        </div>
                    </div>

                    <form action="{{ route('pengaturan.email') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- 1. GLOBAL SETTINGS --}}
                        <h4 class="text-xs font-black text-gray-800 dark:text-gray-200 uppercase mb-3 border-l-4 border-orange-500 pl-2">Pengaturan Visual (Global)</h4>
                        
                        <!-- Disclaimer Default Sidebar -->
                        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 text-[11px] p-3 rounded-lg flex gap-3 items-start">
                            <i class="fas fa-info-circle mt-0.5 text-sm"></i>
                            <p><strong>Info Default:</strong> Jika <em>Logo Kop Email</em> dan <em>Nama Toko</em> tidak diisi, maka sistem akan otomatis mengambil nilai dari <strong>Identitas Aplikasi (Sidebar)</strong> sebagai bawaan.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700/50">
                            <!-- Logo Email -->
                            <div class="flex flex-col justify-center">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Logo Kop Email <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded bg-white border border-gray-200 flex items-center justify-center overflow-hidden">
                                        @if($emailSetting?->logo)
                                            <img src="{{ asset('storage/logos/' . $emailSetting->logo) }}" class="w-full h-full object-contain p-1">
                                        @else
                                            <img src="{{ asset('storage/logos/logo-utama.png') }}" class="w-full h-full object-contain p-1 opacity-50 grayscale" title="Logo Bawaan Sidebar">
                                        @endif
                                    </div>
                                    <input type="file" name="logo_email" accept="image/*" class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                                </div>
                            </div>
                            
                            <!-- Nama Toko Email -->
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Nama Toko di Email <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <input type="text" name="nama_toko" value="{{ $emailSetting?->nama_toko }}" placeholder="{{ config('aplikasi.nama_aplikasi', 'TB. MITRA USAHA 2') }}" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block p-2.5 transition-all">
                            </div>

                            <!-- Primary Color -->
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Warna Aksen Utama (Tombol & Judul) <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="primary_color" value="{{ $emailSetting?->primary_color ?? '#ef4444' }}" class="w-10 h-10 p-0.5 bg-white border border-gray-300 rounded cursor-pointer" required>
                                    <span class="text-xs text-gray-500">Default: #ef4444 (Merah)</span>
                                </div>
                            </div>

                            <!-- Header Background Color -->
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Warna Latar Header <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="header_color" value="{{ $emailSetting?->header_color ?? '#fef2f2' }}" class="w-10 h-10 p-0.5 bg-white border border-gray-300 rounded cursor-pointer" required>
                                    <span class="text-xs text-gray-500">Default: #fef2f2 (Pink Muda)</span>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Teks Catatan Kaki (Footer)</label>
                                <textarea name="footer_text" rows="2" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block p-2.5 transition-all">{{ $emailSetting?->footer_text ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- 2. LOW STOCK --}}
                        <h4 class="text-xs font-black text-gray-800 dark:text-gray-200 uppercase mb-4 border-l-4 border-red-500 pl-2">Email Peringatan Stok Kritis</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border border-gray-200 dark:border-gray-700 p-5 rounded-xl bg-white dark:bg-gray-800">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Judul Email <span class="text-red-500">*</span></label>
                                <input type="text" name="low_stock_title" value="{{ $emailSetting?->low_stock_title ?? '🚨 PERINGATAN STOK KRITIS' }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-2.5 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Teks Tombol <span class="text-red-500">*</span></label>
                                <input type="text" name="low_stock_btn" value="{{ $emailSetting?->low_stock_btn ?? 'Buat PO Sekarang' }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-2.5 transition-all" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Pesan Pembuka</label>
                                <textarea name="low_stock_intro" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-2.5 transition-all">{{ $emailSetting?->low_stock_intro ?? '' }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Pesan Penutup</label>
                                <textarea name="low_stock_outro" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-2.5 transition-all">{{ $emailSetting?->low_stock_outro ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- 3. NEW PO --}}
                        <h4 class="text-xs font-black text-gray-800 dark:text-gray-200 uppercase mb-4 border-l-4 border-blue-500 pl-2">Email Notifikasi PO Baru</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border border-gray-200 dark:border-gray-700 p-5 rounded-xl bg-white dark:bg-gray-800">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Judul Email <span class="text-red-500">*</span></label>
                                <input type="text" name="po_new_title" value="{{ $emailSetting?->po_new_title ?? 'Pemberitahuan Purchase Order' }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Teks Tombol <span class="text-red-500">*</span></label>
                                <input type="text" name="po_new_btn" value="{{ $emailSetting?->po_new_btn ?? 'Buka Aplikasi Sekarang' }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Pesan Pembuka</label>
                                <textarea name="po_new_intro" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all">{{ $emailSetting?->po_new_intro ?? '' }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Pesan Penutup</label>
                                <textarea name="po_new_outro" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all">{{ $emailSetting?->po_new_outro ?? '' }}</textarea>
                            </div>
                        </div>

                        {{-- 4. PO DIGEST & HEADER NOTIF --}}
                        <h4 class="text-xs font-black text-gray-800 dark:text-gray-200 uppercase mb-4 border-l-4 border-indigo-500 pl-2">Email Lainnya</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border border-gray-200 dark:border-gray-700 p-5 rounded-xl bg-white dark:bg-gray-800">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Judul PO Digest <span class="text-red-500">*</span></label>
                                <input type="text" name="po_digest_title" value="{{ $emailSetting?->po_digest_title ?? 'Ringkasan PO Harian' }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Judul Header Notif <span class="text-red-500">*</span></label>
                                <input type="text" name="sys_notif_title" value="{{ $emailSetting?->sys_notif_title ?? 'Pemberitahuan Sistem Baru' }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Intro PO Digest</label>
                                <textarea name="po_digest_intro" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all">{{ $emailSetting?->po_digest_intro ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Intro Header Notif</label>
                                <textarea name="sys_notif_intro" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all">{{ $emailSetting?->sys_notif_intro ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-5 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 text-white px-8 py-2.5 rounded-xl shadow-md hover:shadow-lg font-black flex items-center gap-2 transition-all duration-300 text-sm">
                                <i class="fas fa-save"></i> Simpan Pengaturan Email
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH/EDIT PROFIL CABANG (Untuk Kop Surat) --}}
    <div id="modalProfil" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-[#151720] rounded-3xl w-full max-w-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] animate-[dropIn_0.3s_ease-out] flex flex-col max-h-[90vh] border border-gray-100 dark:border-gray-800">
            
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-[#1a1c26] shrink-0 relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9IiNlNWU3ZWIiIGZpbGwtb3BhY2l0eT0iLjQiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)] dark:opacity-20 pointer-events-none"></div>
                <h3 id="modalTitle" class="font-black text-gray-800 dark:text-white text-xl tracking-tight relative z-10"><i class="fas fa-file-invoice text-indigo-500 mr-2"></i> {{ __('add_letterhead_profile') }}</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 w-8 h-8 rounded-full flex items-center justify-center transition-colors relative z-10">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form id="formProfil" action="{{ route('pengaturan.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto custom-scrollbar">
                @csrf
                <div id="methodSpoofing"></div> 
                
                <div class="p-6 md:p-8 space-y-6 bg-white dark:bg-gray-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('branch_store_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="inp_nama" name="nama_toko" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('letterhead_slogan') }}</label>
                            <input type="text" id="inp_tagline" name="tagline" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('letterhead_logo_optional') }}</label>
                            <div class="flex items-center gap-4">
                                <div id="previewContainer" class="hidden w-14 h-14 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden bg-gray-50 dark:bg-gray-900 shrink-0 flex items-center justify-center shadow-sm">
                                    <img id="previewImage" src="" class="w-full h-full object-contain p-1">
                                </div>
                                <div class="relative w-full">
                                    <input type="file" id="inp_logo" name="logo" accept="image/jpeg, image/png, image/jpg" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-600 dark:file:bg-blue-700 file:text-white hover:file:bg-blue-800 dark:hover:file:bg-blue-600 transition-all cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('full_address') }} <span class="text-red-500">*</span></label>
                            <textarea id="inp_alamat" name="alamat" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('phone_number') }}</label>
                            <input type="text" id="inp_telepon" name="telepon" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('branch_email') }}</label>
                            <input type="email" id="inp_email" name="email" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500">
                        </div>
                    </div>

                    <h3 class="text-sm font-black text-gray-800 dark:text-gray-200 uppercase tracking-widest border-b border-gray-200 dark:border-gray-700 pb-2 pt-4"><i class="fas fa-file-signature text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('signature_column') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('city_print_letter') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="inp_kota" name="kota_ttd" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('signatory_name') }}</label>
                            <input type="text" id="inp_kepala" name="nama_kepala_gudang" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500">
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 shrink-0 flex justify-end gap-3 rounded-b-3xl relative z-10">
                    <button type="button" onclick="closeModal()" class="px-6 py-2.5 rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-800 transition-all duration-300 text-sm bg-transparent">{{ __('cancel') }}</button>
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white px-8 py-2.5 rounded-xl shadow-[0_4px_15px_rgba(99,102,241,0.3)] hover:shadow-[0_6px_20px_rgba(99,102,241,0.4)] hover:-translate-y-0.5 font-black flex items-center gap-2 transition-all duration-300 text-sm">
                        <i class="fas fa-save"></i> {{ __('save_letterhead') }}
                    </button>
                </div>
            </form>
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
        // Preview Foto Sidebar saat dipilih
        function previewUtama(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewLogoUtama').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Script Modal Kop Surat
        function openModal(mode, data = null) {
            const modal = document.getElementById('modalProfil');
            const form = document.getElementById('formProfil');
            const title = document.getElementById('modalTitle');
            const spoofing = document.getElementById('methodSpoofing');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            if(mode === 'add') {
                title.innerHTML = '<i class="fas fa-file-invoice text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('add_letterhead_profile') }}';
                form.action = "{{ route('pengaturan.store') }}";
                spoofing.innerHTML = '';
                form.reset();
                previewContainer.classList.add('hidden');
            } 
            else if(mode === 'edit' && data) {
                title.innerHTML = '<i class="fas fa-edit text-blue-600 dark:text-blue-500 mr-2"></i> {{ __('edit_letterhead') }}';
                form.action = `/pengaturan/${data.id}`;
                spoofing.innerHTML = '@method("PUT")';
                
                document.getElementById('inp_nama').value = data.nama_toko;
                document.getElementById('inp_tagline').value = data.tagline || '';
                document.getElementById('inp_alamat').value = data.alamat;
                document.getElementById('inp_telepon').value = data.telepon || '';
                document.getElementById('inp_email').value = data.email || '';
                document.getElementById('inp_kota').value = data.kota_ttd;
                document.getElementById('inp_kepala').value = data.nama_kepala_gudang || '';
                document.getElementById('inp_logo').value = ''; 

                if (data.logo) {
                    previewContainer.classList.remove('hidden');
                    previewImage.src = `/storage/logos/${data.logo}?v={{ time() }}`;
                } else {
                    previewContainer.classList.add('hidden');
                }
            }
        }

        function closeModal() {
            document.getElementById('modalProfil').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Fungsi untuk Set Aktif Profil Kop Surat
        function setActiveProfile(id) {
            fetch(`/pengaturan/${id}/set-active`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset semua card
                    document.querySelectorAll('.profile-card').forEach(card => {
                        card.classList.remove('border-emerald-500', 'ring-1', 'ring-emerald-500', 'shadow-md', 'border-[#D00000]', 'dark:border-red-500', 'ring-4', 'ring-red-500/20');
                        card.classList.add('border-gray-200', 'dark:border-gray-700', 'shadow-sm');
                    });
                    document.querySelectorAll('.profile-check').forEach(check => {
                        check.classList.remove('border-emerald-500', 'bg-emerald-500', 'text-white', 'border-[#D00000]', 'bg-[#D00000]', 'dark:border-red-500', 'dark:bg-red-500');
                        check.classList.add('border-gray-300', 'dark:border-gray-500', 'bg-white', 'dark:bg-gray-600', 'text-transparent');
                    });

                    // Set aktif pada card yang diklik
                    const activeCard = document.getElementById(`profile-card-${id}`);
                    activeCard.classList.remove('border-gray-200', 'dark:border-gray-700', 'shadow-sm');
                    activeCard.classList.add('border-emerald-500', 'ring-1', 'ring-emerald-500', 'shadow-md');

                    const activeCheck = document.getElementById(`profile-check-${id}`);
                    activeCheck.classList.remove('border-gray-300', 'dark:border-gray-500', 'bg-white', 'dark:bg-gray-600', 'text-transparent');
                    activeCheck.classList.add('border-emerald-500', 'bg-emerald-500', 'text-white');

                    // Opsional: Tampilkan notifikasi
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat mengatur profil aktif.', 'error');
            });
        }

        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);
    </script>
</x-app-layout>