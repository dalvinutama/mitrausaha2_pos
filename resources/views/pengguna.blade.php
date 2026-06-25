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
                
                {{-- HEADER & TOMBOL TAMBAH --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors"><i class="fas fa-home text-sm"></i></a> 
                            <span>/</span> <span>Sistem</span> <span>/</span> <span class="text-[#D00000] dark:text-red-400">{{ __('staff_management') }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('employee_access') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ __('employee_management_desc') }}</p>
                    </div>
                    
                    <button onclick="openModal('add')" class="bg-[#1e1e2d] dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white px-6 py-3.5 rounded-2xl shadow-md dark:shadow-none font-black tracking-wide flex items-center justify-center gap-3 transition-all duration-300 transform hover:-translate-y-1.5 card-shadow-gray border border-gray-800 dark:border-gray-600 w-full md:w-auto">
                        <i class="fas fa-user-plus text-lg"></i> {{ __('add_new_employee') }}
                    </button>
                </div>

                {{-- NOTIFIKASI --}}
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm font-bold transition-colors flex items-center gap-3">
                        <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm font-bold transition-colors flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-xl"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm transition-colors">
                        <ul class="list-disc pl-5 text-sm font-bold">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                {{-- CARD GRID AREA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    
                    @foreach($users as $user)
                        @php
                            // Menentukan warna gradasi background berdasarkan role
                            $roleColors = [
                                'owner' => 'from-red-500 to-rose-600',
                                'admin' => 'from-blue-500 to-indigo-600',
                                'penjualan' => 'from-amber-400 to-orange-500',
                                'gudang' => 'from-emerald-400 to-green-600',
                                'kasir' => 'from-purple-400 to-fuchsia-500',
                                'pengiriman' => 'from-cyan-400 to-teal-500',
                            ];
                            $bgClass = $roleColors[strtolower($user->role)] ?? 'from-gray-400 to-gray-500';
                            
                            $roleShadows = [
                                'owner' => 'card-shadow-red',
                                'admin' => 'card-shadow-blue',
                                'penjualan' => 'card-shadow-orange',
                                'gudang' => 'card-shadow-emerald',
                                'kasir' => 'card-shadow-purple',
                                'pengiriman' => 'card-shadow-teal',
                            ];
                            $shadowClass = $roleShadows[strtolower($user->role)] ?? 'card-shadow-gray';
                            
                            $roleHover = [
                                'owner' => 'hover:bg-red-50/50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-800/50',
                                'admin' => 'hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 hover:border-indigo-200 dark:hover:border-indigo-800/50',
                                'penjualan' => 'hover:bg-orange-50/50 dark:hover:bg-orange-900/20 hover:border-orange-200 dark:hover:border-orange-800/50',
                                'gudang' => 'hover:bg-emerald-50/50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800/50',
                                'kasir' => 'hover:bg-purple-50/50 dark:hover:bg-purple-900/20 hover:border-purple-200 dark:hover:border-purple-800/50',
                                'pengiriman' => 'hover:bg-teal-50/50 dark:hover:bg-teal-900/20 hover:border-teal-200 dark:hover:border-teal-800/50',
                            ];
                            $hoverClass = $roleHover[strtolower($user->role)] ?? 'hover:bg-gray-50/80 dark:hover:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600';

                            $roleTextHover = [
                                'owner' => 'group-hover:text-red-700 dark:group-hover:text-red-400',
                                'admin' => 'group-hover:text-indigo-700 dark:group-hover:text-indigo-400',
                                'penjualan' => 'group-hover:text-orange-700 dark:group-hover:text-orange-400',
                                'gudang' => 'group-hover:text-emerald-700 dark:group-hover:text-emerald-400',
                                'kasir' => 'group-hover:text-purple-700 dark:group-hover:text-purple-400',
                                'pengiriman' => 'group-hover:text-teal-700 dark:group-hover:text-teal-400',
                            ];
                            $textHoverClass = $roleTextHover[strtolower($user->role)] ?? 'group-hover:text-gray-900 dark:group-hover:text-gray-300';
                            
                            // API Generate Avatar Karakter dari Nama
                            $avatarUrl = 'https://api.dicebear.com/9.x/avataaars/svg?seed=' . urlencode($user->name) . '&backgroundColor=transparent';
                        @endphp

                        {{-- KARTU PEGAWAI (PERBAIKAN KONTRAS: border-gray-200, shadow-md hover:shadow-lg) --}}
                        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-200 dark:border-gray-700 shadow-md {{ $shadowClass }} dark:shadow-none transition-all duration-300 transform hover:-translate-y-1.5 relative group cursor-pointer {{ $hoverClass }}" onclick="openProfileModal({{ json_encode($user) }}, '{{ $bgClass }}', '{{ $avatarUrl }}')">
                            
                            {{-- Tombol Aksi (Edit/Hapus) di pojok kanan atas --}}
                            <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity" onclick="event.stopPropagation();">
                                @php
                                    $isRestricted = (Auth::user()->role === 'admin' && strtolower($user->role) === 'owner');
                                @endphp

                                @if(!$isRestricted)
                                    <button onclick="openModal('edit', {{ json_encode($user) }})" class="w-8 h-8 rounded-full bg-white dark:bg-gray-700 text-blue-500 shadow-sm border border-gray-200 dark:border-gray-600 flex items-center justify-center hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors" title="{{ __('edit_data') }}">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    @if(auth()->id() !== $user->id)
                                        <button type="button" onclick="openDeleteModal('{{ route('pengguna.destroy', $user->id) }}', '{{ addslashes($user->name) }}')" class="w-8 h-8 rounded-full bg-white dark:bg-gray-700 text-red-500 shadow-sm border border-gray-200 dark:border-gray-600 flex items-center justify-center hover:bg-red-50 dark:hover:bg-gray-600 transition-colors" title="{{ __('delete_employee') }}">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 border border-gray-200 dark:border-gray-600 flex items-center justify-center shadow-sm cursor-not-allowed" title="{{ __('access_denied_only_owner') }}">
                                        <i class="fas fa-lock text-xs"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-5">
                                {{-- Avatar Pegawai (Otomatis Karakter) --}}
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $bgClass }} flex items-end justify-center shadow-md shrink-0 overflow-hidden relative group-hover:scale-110 transition-transform duration-300">
                                    <img src="{{ $avatarUrl }}" alt="Avatar {{ $user->name }}" class="w-[90%] h-[90%] object-contain relative z-10 drop-shadow-md transform translate-y-1">
                                </div>
                                
                                {{-- Info Pegawai --}}
                                <div class="overflow-hidden">
                                    <h3 class="font-black text-gray-800 dark:text-gray-100 text-lg truncate pr-6 {{ $textHoverClass }} transition-colors" title="{{ $user->name }}">{{ $user->name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mb-1" title="{{ $user->email }}">{{ $user->email }}</p>
                                    <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-black uppercase tracking-widest rounded-lg border border-gray-200 dark:border-gray-600">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </div>
                            </div>

                            {{-- PERBAIKAN KONTRAS: border-gray-200 --}}
                            <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center text-xs">
                                <div class="text-gray-500 dark:text-gray-400">
                                    {{ __('joined:') }} <span class="font-bold text-gray-700 dark:text-gray-300">{{ $user->created_at->format('M Y') }}</span>
                                </div>
                                @if(auth()->id() === $user->id)
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)] animate-pulse" title="{{ __('this_is_your_account_online') }}"></span>
                                @else
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600" title="{{ __('offline') }}"></span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH / EDIT KARYAWAN (Form Input Lengkap) --}}
    <div id="userModal" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col max-h-[90vh]">
            {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 shrink-0">
                <h3 id="modalTitle" class="font-black text-gray-800 dark:text-white text-xl"><i class="fas fa-user-shield text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('add_employee') }}</h3>
                <button type="button" onclick="closeModal('userModal')" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="userForm" action="{{ route('pengguna.store') }}" method="POST" class="flex flex-col overflow-hidden">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="p-6 overflow-y-auto bg-white dark:bg-gray-800 space-y-5 custom-scrollbar">
                    
                    {{-- Live Preview Avatar Berdasarkan Nama --}}
                    <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-600 flex items-center justify-center shadow-inner border border-gray-300 dark:border-gray-500">
                            <img id="liveAvatarPreview" src="https://api.dicebear.com/9.x/avataaars/svg?seed=Karyawan&backgroundColor=transparent" alt="Avatar Preview" class="w-[90%] h-[90%] object-contain">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('employee_avatar_preview') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ __('avatar_auto_change_desc') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('full_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" onkeyup="updateLiveAvatar(this.value)" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required placeholder="{{ __('ex_budi_santoso') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('role_assignment') }} <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all font-medium" required>
                                <option value="" disabled selected>{{ __('determine_position') }}</option>
                                @if(Auth::user()->role === 'owner')
                                    <option value="owner">{{ __('business_owner') }}</option>
                                @endif
                                <option value="admin">{{ __('admin_finance') }}</option>
                                <option value="penjualan">{{ __('sales_staff') }}</option>
                                <option value="gudang">{{ __('warehouse_head_staff') }}</option>
                                <option value="kasir">{{ __('store_cashier_operator') }}</option>
                                <option value="pengiriman">{{ __('logistics_delivery') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('system_email') }} <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" required placeholder="budi@mitrausaha.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('whatsapp_no_optional') }}</label>
                            <input type="text" name="no_wa" id="no_wa" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" placeholder="0812xxxx">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('employee_address_optional') }}</label>
                        <textarea name="alamat" id="alamat" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all custom-scrollbar dark:placeholder-gray-500" placeholder="{{ __('type_complete_domicile_address') }}"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-gray-200 dark:border-gray-700 pt-5 mt-4 transition-colors">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('login_password') }} <span id="passRequired" class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('repeat_password') }} <span id="passConfRequired" class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all">
                        </div>
                    </div>
                    <div id="passHelp" class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 p-3 rounded-xl border border-blue-200 dark:border-blue-800/30 text-xs font-medium hidden flex items-start gap-2">
                        <i class="fas fa-info-circle mt-0.5"></i> {{ __('empty_password_if_no_reset') }}
                    </div>
                </div>
                
                {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 shrink-0 flex justify-end gap-3 transition-colors rounded-b-3xl">
                    <button type="button" onclick="closeModal('userModal')" class="px-6 py-3.5 rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 transition-colors text-sm">{{ __('cancel') }}</button>
                    <button type="submit" class="bg-[#1e1e2d] dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white px-8 py-3.5 rounded-xl shadow-md dark:shadow-none font-black flex items-center gap-2 transition-all hover:-translate-y-0.5 text-sm tracking-wide">
                        <i class="fas fa-save"></i> {{ __('save_employee_data') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DETAIL PROFIL PEGAWAI (Pop-up Tampilan Lengkap Interaktif) --}}
    <div id="profileModal" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-900 rounded-3xl w-full max-w-md overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.3)] animate-[dropIn_0.3s_ease-out]">
            
            {{-- Header Profil dengan Background Gradasi Dinamis --}}
            <div id="profileHeaderBg" class="h-32 bg-gradient-to-r from-gray-400 to-gray-500 relative">
                <button type="button" onclick="closeModal('profileModal')" class="absolute top-4 right-4 text-white hover:text-white/70 bg-black/20 hover:bg-black/40 w-8 h-8 rounded-full flex items-center justify-center transition-colors backdrop-blur-md">
                    <i class="fas fa-times"></i>
                </button>
                
                {{-- Foto Melayang Karakter --}}
                <div class="absolute -bottom-10 left-8 w-24 h-24 rounded-2xl bg-white dark:bg-gray-800 border-4 border-white dark:border-gray-900 flex items-end justify-center shadow-md">
                    <img id="profileAvatar" src="" alt="Avatar" class="w-[90%] h-[90%] object-contain relative z-10 drop-shadow-md transform translate-y-1">
                </div>
            </div>

            <div class="pt-14 px-8 pb-8">
                <h2 id="profileName" class="text-2xl font-black text-gray-800 dark:text-white mb-1">Nama Lengkap</h2>
                <div class="flex items-center gap-2 mb-6">
                    <span id="profileRoleBadge" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-200">Role</span>
                    <span id="profileStatus" class="flex items-center gap-1.5 text-xs font-bold text-emerald-500">
                        <i class="fas fa-circle text-[8px]"></i> {{ __('active_employee') }}
                    </span>
                </div>

                <div class="space-y-4">
                    {{-- Detail Card: Kontak Utama --}}
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-0.5">{{ __('access_email') }}</p>
                            <p id="profileEmail" class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">email@example.com</p>
                        </div>
                    </div>

                    {{-- Detail Card: WhatsApp --}}
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 text-green-500 flex items-center justify-center shrink-0">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-0.5">{{ __('whatsapp_no') }}</p>
                            <p id="profileWa" class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">-</p>
                        </div>
                    </div>

                    {{-- Detail Card: Alamat Domisili --}}
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 flex items-center justify-center shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="overflow-hidden pt-0.5">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('domicile_address') }}</p>
                            <p id="profileAlamat" class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-relaxed line-clamp-3">-</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-calendar-alt mr-1"></i> {{ __('joined') }}</p>
                            <p id="profileDate" class="text-sm font-bold text-gray-800 dark:text-gray-200">1 Jan 2026</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-shield-alt mr-1"></i> {{ __('security') }}</p>
                            <p id="profileVerification" class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ __('verified') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center border-t border-gray-200 dark:border-gray-800 pt-6">
                    <button onclick="closeModal('profileModal')" class="text-xs font-bold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors uppercase tracking-widest">
                        {{ __('close_profile') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all opacity-0">
        <div id="deleteModalContent" class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 border border-gray-200 dark:border-gray-700 text-center">
            <div class="pt-8 px-6 pb-6">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-50 dark:border-red-900/10">
                    <i class="fas fa-trash text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2">{{ __('Hapus Pengguna?') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Apakah Anda yakin ingin menghapus akun') }} <strong id="deleteUserName" class="text-gray-700 dark:text-gray-300"></strong>? {{ __('Tindakan ini tidak dapat dibatalkan.') }}</p>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-colors">{{ __('Batal') }}</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors shadow-md shadow-red-500/30">{{ __('Ya, Hapus!') }}</button>
                    </form>
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
        @keyframes dropIn { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    </style>

    <script>
        // Sidebar dan Dropdown dikelola secara global di layouts.header

        // FUNGSI UPDATE AVATAR SECARA LIVE
        function updateLiveAvatar(val) {
            const preview = document.getElementById('liveAvatarPreview');
            const safeVal = val ? encodeURIComponent(val) : 'Karyawan';
            preview.src = `https://api.dicebear.com/9.x/avataaars/svg?seed=${safeVal}&backgroundColor=transparent`;
        }

        // Modal Form Setup
        function openModal(mode, userData = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('modalTitle');
            const methodInput = document.getElementById('formMethod');
            
            const passReq = document.getElementById('passRequired');
            const passConfReq = document.getElementById('passConfRequired');
            const passHelp = document.getElementById('passHelp');
            const passInput = document.getElementById('password');
            const passConfInput = document.getElementById('password_confirmation');

            if (mode === 'add') {
                title.innerHTML = '<i class="fas fa-user-plus text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('add_new_employee') }}';
                form.action = "{{ route('pengguna.store') }}";
                methodInput.value = "POST";
                form.reset();
                
                passReq.classList.remove('hidden');
                passConfReq.classList.remove('hidden');
                passHelp.classList.add('hidden');
                passInput.required = true;
                passConfInput.required = true;
                
                updateLiveAvatar(''); // Reset preview avatar
            } else {
                title.innerHTML = '<i class="fas fa-user-edit text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('edit_employee_data') }}';
                form.action = `/pengguna/${userData.id}`;
                methodInput.value = "PUT";
                
                document.getElementById('name').value = userData.name;
                document.getElementById('email').value = userData.email;
                document.getElementById('role').value = userData.role;
                document.getElementById('no_wa').value = userData.no_wa || '';
                document.getElementById('alamat').value = userData.alamat || '';
                
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
                
                passReq.classList.add('hidden');
                passConfReq.classList.add('hidden');
                passHelp.classList.remove('hidden');
                passInput.required = false; 
                passConfInput.required = false;

                updateLiveAvatar(userData.name); // Generate preview avatar untuk nama saat ini
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }

        // Modal Pop-up Profil Detail
        function openProfileModal(userData, bgClass, avatarUrl) {
            const modal = document.getElementById('profileModal');
            
            // Format Tanggal Manual JS (Mirip Carbon)
            const dateObj = new Date(userData.created_at);
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
            const formattedDate = dateObj.getDate() + ' ' + months[dateObj.getMonth()] + ' ' + dateObj.getFullYear();

            // Setup Data Visual
            document.getElementById('profileHeaderBg').className = `h-32 bg-gradient-to-r ${bgClass} relative`;
            document.getElementById('profileAvatar').src = avatarUrl;
            document.getElementById('profileName').innerText = userData.name;
            document.getElementById('profileEmail').innerText = userData.email;
            
            // Setup WA dan Alamat (Handle Null/Kosong)
            document.getElementById('profileWa').innerText = userData.no_wa ? userData.no_wa : '{{ __('not_set_yet') }}';
            document.getElementById('profileAlamat').innerText = userData.alamat ? userData.alamat : '{{ __('address_not_set_yet') }}';
            
            document.getElementById('profileDate').innerText = formattedDate;
            
            // Setup Role Badge Styling
            const roleBadge = document.getElementById('profileRoleBadge');
            roleBadge.innerText = userData.role.replace('_', ' ');
            
            // Format Badge Colors by Role
            const roleName = userData.role.toLowerCase();
            roleBadge.className = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border ';
            if(roleName === 'owner') roleBadge.classList.add('bg-red-100', 'text-red-700', 'border-red-200', 'dark:bg-red-900/30');
            else if(roleName === 'admin') roleBadge.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-200', 'dark:bg-blue-900/30');
            else if(roleName === 'gudang') roleBadge.classList.add('bg-emerald-100', 'text-emerald-700', 'border-emerald-200', 'dark:bg-emerald-900/30');
            else roleBadge.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300');

            // Email verification check
            const verifText = document.getElementById('profileVerification');
            if(userData.email_verified_at) {
                verifText.innerHTML = '<span class="text-emerald-500"><i class="fas fa-check-circle"></i> {{ __('safe') }}</span>';
            } else {
                verifText.innerHTML = '<span class="text-orange-500"><i class="fas fa-clock"></i> {{ __('check_verification') }}</span>';
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // JS Hapus Modal
        function openDeleteModal(actionUrl, userName) {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('deleteModalContent');
            document.getElementById('deleteUserName').innerText = userName;
            document.getElementById('deleteForm').action = actionUrl;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('deleteModalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            modalContent.classList.remove('scale-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    </script>
</x-app-layout>