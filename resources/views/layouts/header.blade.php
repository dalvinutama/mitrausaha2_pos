<header class="h-[76px] flex justify-between items-center bg-white dark:bg-gray-900 px-4 lg:px-8 border-b border-gray-200 dark:border-gray-800 shrink-0 relative z-20 transition-colors duration-300">
    <div class="flex items-center gap-3 w-full lg:w-auto">
        <button onclick="toggleSidebar()" class="block lg:hidden shrink-0 text-gray-600 dark:text-gray-400 w-10 h-10 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
            <i class="fas fa-bars text-lg"></i>
        </button>
        
        {{-- PENCARIAN GLOBAL DENGAN LIVE SEARCH --}}
        <div class="relative hidden md:block lg:w-[320px] max-w-sm flex-1 z-[60]">
            <form id="globalSearchForm" action="{{ route('search') ?? '#' }}" method="GET" class="flex items-center bg-gray-50 dark:bg-gray-800/80 px-4 h-11 rounded-xl w-full border border-gray-200 dark:border-gray-700/80 focus-within:border-[#D00000] dark:focus-within:border-red-500 focus-within:bg-white dark:focus-within:bg-gray-900 focus-within:ring-4 focus-within:ring-red-500/10 transition-all shadow-sm focus-within:shadow-md">
                <i class="fas fa-search text-gray-400 dark:text-gray-500 text-sm"></i>
                <input type="text" id="globalSearchInput" name="keyword" autocomplete="off" placeholder="Cari barang, supplier, transaksi..." class="bg-transparent border-none outline-none ml-3 w-full text-sm focus:ring-0 text-gray-700 dark:text-gray-200 dark:placeholder-gray-400" required>
                <button type="submit" class="hidden"></button>
            </form>
            
            {{-- DROPDOWN HASIL LIVE SEARCH --}}
            <div id="liveSearchDropdown" class="absolute top-full left-0 w-[400px] mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden opacity-0 invisible transform translate-y-2 transition-all duration-300">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800/80">
                    <span class="text-[10px] font-black tracking-widest text-gray-500 dark:text-gray-400 uppercase">Hasil Pencarian</span>
                    <button type="button" onclick="closeLiveSearch()" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fas fa-times"></i></button>
                </div>
                <div id="liveSearchResults" class="max-h-[350px] overflow-y-auto custom-scrollbar p-2 space-y-3">
                    {{-- Konten diisi oleh JavaScript --}}
                </div>
                <div id="liveSearchFooter" class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 text-center hidden">
                    <button type="button" onclick="document.getElementById('globalSearchForm').submit()" class="text-[10px] font-bold text-[#D00000] dark:text-red-400 hover:underline">Lihat Semua Hasil &rarr;</button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 lg:gap-6 relative shrink-0">
        
        {{-- ========================================== --}}
        {{-- TOGGLE BAHASA (EN / ID) --}}
        {{-- ========================================== --}}
        <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-full p-1 hidden sm:flex border border-gray-200 dark:border-gray-700 transition-colors">
            <a href="/lang/id" class="px-2.5 py-1 text-[10px] font-black rounded-full transition-all {{ app()->getLocale() == 'id' ? 'bg-white dark:bg-gray-700 text-[#D00000] shadow-sm' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">
                ID
            </a>
            <a href="/lang/en" class="px-2.5 py-1 text-[10px] font-black rounded-full transition-all {{ app()->getLocale() == 'en' ? 'bg-white dark:bg-gray-700 text-[#D00000] shadow-sm' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">
                EN
            </a>
        </div>

        {{-- ========================================== --}}
        {{-- TOGGLE TEMA (TERANG / GELAP) --}}
        {{-- ========================================== --}}
        <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-full p-1 border border-gray-200 dark:border-gray-700 hidden sm:flex transition-colors">
            <button onclick="setTheme('light')" class="px-3 py-1.5 text-xs rounded-full transition-all duration-300 bg-white text-[#D00000] shadow-sm dark:bg-transparent dark:text-gray-500 dark:hover:text-gray-300 dark:shadow-none">
                <i class="fas fa-sun"></i>
            </button>
            <button onclick="setTheme('dark')" class="px-3 py-1.5 text-xs rounded-full transition-all duration-300 bg-transparent text-gray-400 hover:text-gray-600 shadow-none dark:bg-gray-700 dark:text-[#D00000] dark:shadow-sm">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        {{-- GARIS PEMISAH --}}
        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block transition-colors mx-1"></div>
        
        {{-- TOMBOL PESAN/CHAT --}}
        <button onclick="toggleChatDropdown()" class="text-gray-500 dark:text-gray-400 hover:text-[#D00000] dark:hover:text-[#D00000] hover:bg-red-50 dark:hover:bg-red-900/20 w-10 h-10 flex items-center justify-center rounded-full transition-colors relative">
            <i class="fas fa-envelope-open-text text-[17px]"></i>
            @php 
                $userId = Auth::id();
                $unreadMsg = 0; // Inisialisasi default 0
                if(class_exists('\App\Models\Message')) {
                    try {
                        $unreadMsg = \App\Models\Message::where('from_user_id', '!=', $userId)
                            ->whereDoesntHave('reads', function($query) use ($userId) {
                                $query->where('user_id', $userId);
                            })->count(); 
                    } catch (\Exception $e) {}
                }
            @endphp
            
            @if($unreadMsg > 0)
                <span id="chatBadge" class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-gray-900">{{ $unreadMsg }}</span>
            @endif
        </button>
        
        {{-- ========================================== --}}
        {{-- TOMBOL NOTIFIKASI --}}
        {{-- ========================================== --}}
        <div class="relative">
            <button onclick="toggleNotif()" class="text-gray-500 dark:text-gray-400 hover:text-[#D00000] dark:hover:text-[#D00000] hover:bg-red-50 dark:hover:bg-red-900/20 w-10 h-10 flex items-center justify-center rounded-full transition-colors relative">
                <i class="fas fa-bell text-[17px]"></i>
                
                @php
                    $totalNotif = 0;
                    $stokMenipisNotif = collect([]);
                    $hutangTempoNotif = collect([]);
                    
                    if(class_exists('\App\Models\Product') && class_exists('\App\Models\Transaction')) {
                        try {
                            $stokMenipisNotif = \App\Models\Product::whereColumn('stok', '<=', 'reorder_point')->get();
                            
                            $besok = \Carbon\Carbon::tomorrow()->format('Y-m-d');
                            $transaksiTempo = \App\Models\Transaction::with('supplier')
                                ->where('jenis_transaksi', 'masuk')
                                ->where('catatan', 'LIKE', '%[Pembayaran TEMPO%')
                                ->get();
                                
                            foreach($transaksiTempo as $trx) {
                                preg_match('/Jatuh Tempo:\s*([^\]]+)/', $trx->catatan, $matches);
                                if(isset($matches[1])) {
                                    try {
                                        $tglTempoDb = \Carbon\Carbon::createFromFormat('d/m/Y', trim($matches[1]))->format('Y-m-d');
                                        if($tglTempoDb <= $besok) {
                                            $trx->tanggal_tempo = $tglTempoDb; 
                                            $hutangTempoNotif->push($trx);
                                        }
                                    } catch(\Exception $e) {}
                                }
                            }
                            $totalNotif = $stokMenipisNotif->count() + $hutangTempoNotif->count();
                        } catch (\Exception $e) {}
                    }
                @endphp

                @if($totalNotif > 0)
                    <span class="absolute top-1 right-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-gray-900 animate-pulse">
                        {{ $totalNotif }}
                    </span>
                @endif
            </button>

            {{-- DROPDOWN NOTIFIKASI --}}
            <div id="dropdownNotif" class="hidden fixed inset-x-2 top-16 sm:absolute sm:inset-auto sm:right-0 sm:mt-3 w-auto sm:w-80 bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700 z-[60] overflow-hidden transform origin-top sm:origin-top-right transition-all max-h-[80vh] sm:max-h-[none] flex flex-col">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 flex justify-between items-center backdrop-blur-sm transition-colors">
                    <h4 class="text-sm font-black text-gray-800 dark:text-gray-100">Notifikasi Sistem</h4>
                    @if($totalNotif > 0)
                        <span class="text-[10px] font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2.5 py-1 rounded-lg">{{ $totalNotif }} Peringatan</span>
                    @endif
                </div>
                
                <div class="max-h-[320px] overflow-y-auto custom-scrollbar bg-white dark:bg-gray-800">
                    
                    {{-- LOOPING NOTIFIKASI JATUH TEMPO --}}
                    @forelse($hutangTempoNotif as $hutang)
                        <a href="{{ route('dashboard') }}" class="flex items-start gap-4 p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/20 text-orange-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                                <i class="fas fa-file-invoice-dollar text-sm"></i>
                            </div>
                            <div class="flex-1 w-full">
                                @php
                                    $tglTempo = \Carbon\Carbon::parse($hutang->tanggal_tempo);
                                    $isLewat = $tglTempo->isPast() && !$tglTempo->isToday();
                                @endphp
                                <div class="flex justify-between items-start gap-2">
                                    <p class="text-xs font-bold {{ $isLewat ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }} group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors">
                                        {{ $isLewat ? 'Tagihan Telat!' : 'Jatuh Tempo Besok/Hari Ini!' }}
                                    </p>
                                    <span class="text-[9px] text-gray-400 font-medium whitespace-nowrap">{{ \Carbon\Carbon::parse($hutang->updated_at ?? $hutang->created_at)->format('d M, H:i') }}</span>
                                </div>
                                <p class="text-[11px] font-bold text-gray-600 dark:text-gray-400 mt-0.5">Inv: {{ $hutang->no_transaksi }}</p>
                                <p class="text-[10px] text-gray-500 mt-1">Tagihan: <span class="font-black text-gray-800 dark:text-gray-200">Rp {{ number_format($hutang->total_nilai, 0, ',', '.') }}</span></p>
                            </div>
                        </a>
                    @empty
                    @endforelse

                    {{-- LOOPING NOTIFIKASI STOK MENIPIS --}}
                    @forelse($stokMenipisNotif as $item)
                        <a href="{{ Route::has('persediaan') ? route('persediaan') : '#' }}" class="flex items-start gap-4 p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-red-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/20 text-red-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                                <i class="fas fa-exclamation-triangle text-sm"></i>
                            </div>
                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-start gap-2">
                                    <p class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">Stok Hampir Habis!</p>
                                    <span class="text-[9px] text-gray-400 font-medium whitespace-nowrap">{{ \Carbon\Carbon::parse($item->updated_at ?? now())->format('d M, H:i') }}</span>
                                </div>
                                <p class="text-[11px] font-bold text-gray-600 dark:text-gray-400 mt-0.5">{{ $item->nama_barang }}</p>
                                <p class="text-[10px] text-gray-500 mt-1">Sisa: <span class="font-black text-red-500 dark:text-red-400">{{ $item->stok }} {{ $item->satuan }}</span> (Batas: {{ $item->reorder_point }})</p>
                            </div>
                        </a>
                    @empty
                    @endforelse

                    @if($totalNotif == 0)
                        <div class="p-8 text-center flex flex-col items-center justify-center opacity-70">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-600">
                                <i class="fas fa-bell-slash text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-600 dark:text-gray-400">Belum ada peringatan baru.</p>
                            <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-1">Sistem berjalan dengan normal.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block transition-colors"></div>

        {{-- PROFIL USER --}}
        <div class="relative">
            <button onclick="toggleDropdown()" class="flex items-center gap-3 pl-2 sm:pl-0 hover:opacity-80 transition-opacity">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100 leading-none">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-[#D00000] dark:text-red-400 font-bold mt-1 uppercase">{{ Auth::user()->role ?? 'OWNER' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=D00000&color=fff&bold=true" class="w-10 h-10 rounded-xl border-2 border-white dark:border-gray-800 shadow-sm transition-colors">
            </button>
            
            {{-- DROPDOWN PROFIL --}}
            <div id="dropdownUser" class="hidden absolute right-0 top-14 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 z-50 overflow-hidden transform origin-top-right transition-all">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 transition-colors">
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'admin@mitrausaha2.com' }}</p>
                </div>
                
                {{-- MOBILE ONLY OPTIONS (LANGUAGE & THEME) --}}
                <div class="block sm:hidden border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="px-4 py-3 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400"><i class="fas fa-language w-5"></i> {{ __('language') }}</span>
                        <div class="flex items-center bg-gray-200 dark:bg-gray-700 rounded-full p-1 border border-gray-300 dark:border-gray-600">
                            <a href="/lang/id" class="px-2.5 py-1 text-[9px] font-black rounded-full transition-all {{ app()->getLocale() == 'id' ? 'bg-white dark:bg-gray-600 text-[#D00000] shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">ID</a>
                            <a href="/lang/en" class="px-2.5 py-1 text-[9px] font-black rounded-full transition-all {{ app()->getLocale() == 'en' ? 'bg-white dark:bg-gray-600 text-[#D00000] shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">EN</a>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400"><i class="fas fa-adjust w-5"></i> {{ __('theme') }}</span>
                        <div class="flex items-center bg-gray-200 dark:bg-gray-700 rounded-full p-1 border border-gray-300 dark:border-gray-600">
                            <button onclick="setTheme('light')" class="px-3 py-1 rounded-full transition-all bg-white text-[#D00000] shadow-sm dark:bg-transparent dark:text-gray-500"><i class="fas fa-sun text-[10px]"></i></button>
                            <button onclick="setTheme('dark')" class="px-3 py-1 rounded-full transition-all bg-transparent text-gray-500 dark:bg-gray-600 dark:text-[#D00000] dark:shadow-sm"><i class="fas fa-moon text-[10px]"></i></button>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('pengaturan') ?? '#' }}" class="flex items-center px-4 py-3 text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-[#D00000] dark:hover:text-[#D00000] hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"><i class="fas fa-user-cog w-5"></i> {{ __('store_settings') }}</a>
                @else
                    <a href="javascript:void(0)" class="flex items-center justify-between px-4 py-3 text-sm font-bold text-gray-400 dark:text-gray-500 opacity-60 cursor-not-allowed" title="{{ __('access_denied_menu') }}">
                        <div class="flex items-center">
                            <i class="fas fa-user-cog w-5"></i> {{ __('store_settings') }}
                        </div>
                        <i class="fas fa-lock text-xs text-gray-400"></i>
                    </a>
                @endif
                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                <form method="POST" action="{{ route('logout') ?? '#' }}">
                    @csrf
                    <button class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i> {{ __('logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- DROPDOWN PESAN / CHAT (WHATSAPP MINI) --}}
    <div id="dropdownMessage" class="hidden fixed inset-x-2 top-16 bottom-4 sm:absolute sm:inset-auto sm:right-16 sm:top-16 sm:w-[360px] sm:h-[480px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 z-[60] overflow-hidden transition-colors">
        <div class="flex h-full w-full">
            {{-- ========================================== --}}
            {{-- AREA CHAT KANAN (CHAT BODY) --}}
            {{-- ========================================== --}}
            <div class="w-full flex flex-col h-full bg-[#efeae2] dark:bg-gray-900 relative">
                
                {{-- HEADER CHAT --}}
                <div class="h-14 px-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex justify-between items-center shrink-0 z-20 transition-colors">
                    <div class="flex items-center gap-2 flex-1 min-w-0 pr-2">
                        <div class="w-9 h-9 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center text-[#D00000] border border-red-200 dark:border-red-900/50 shadow-sm cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <i class="fas fa-users text-sm"></i>
                        </div>
                        <div class="cursor-pointer hover:opacity-80 transition-opacity flex-1 min-w-0">
                            <h4 class="font-black text-gray-800 dark:text-gray-100 text-[13px] tracking-tight truncate">Grup Internal Toko</h4>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 truncate w-full">Anda, Admin Gudang, Kasir...</p>
                        </div>
                    </div>
                    <div class="flex gap-1 text-gray-500 dark:text-gray-400 relative shrink-0" id="chatHeaderOptionsWrapper">
                        <button onclick="toggleChatSearch()" id="btnChatSearchToggle" class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center transition-colors"><i class="fas fa-search text-xs"></i></button>
                        <button onclick="document.getElementById('chatFileInput').click()" class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center transition-colors"><i class="fas fa-paperclip text-xs"></i></button>
                        
                        {{-- OPSI DROP DOWN CHAT --}}
                        <div class="relative">
                            <button id="btnChatOptionsToggle" onclick="toggleChatOptionsDropdown(event)" class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center transition-colors"><i class="fas fa-ellipsis-v text-xs"></i></button>
                            <div id="chatOptionsDropdown" class="hidden absolute right-0 top-10 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 py-1.5 text-[13px] text-gray-700 dark:text-gray-200">
                                <button onclick="Swal.fire('{{ __('group_info') }}', 'Fitur info grup sedang dikembangkan.', 'info')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                                    <i class="fas fa-info-circle w-4 text-center text-gray-400"></i> {{ __('group_info') }}
                                </button>
                                <button onclick="Swal.fire('{{ __('mute_notification') }}', 'Notifikasi grup ini telah dibisukan.', 'success')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                                    <i class="fas fa-bell-slash w-4 text-center text-gray-400"></i> {{ __('mute_notification') }}
                                </button>
                                <button onclick="showInlineClearConfirm('local')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 border-t border-gray-100 dark:border-gray-700 mt-1 pt-2">
                                    <i class="fas fa-trash-alt w-4 text-center text-gray-400"></i> {{ __('clear_local_history') }}
                                </button>
                                @if(in_array(Auth::user()->role, ['owner', 'admin']))
                                <button onclick="showInlineClearConfirm('global')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400 transition-colors flex items-center gap-2 border-t border-gray-100 dark:border-gray-700 mt-1 pt-2">
                                    <i class="fas fa-bomb w-4 text-center"></i> {{ __('delete_global_history') }}
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PENCARIAN CHAT LOKAL --}}
                <div id="chatSearchBar" class="hidden px-4 py-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-20 shadow-inner">
                    <input type="text" id="chatSearchInput" onkeyup="searchChat()" placeholder="Cari pesan di sini..." class="w-full text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 focus:ring-red-500 focus:border-red-500 transition-colors text-gray-800 dark:text-gray-200">
                </div>
            
            {{-- BACKGROUND TEXTURE --}}
            <div class="absolute inset-0 top-[64px] bottom-[60px] opacity-10 dark:opacity-5 pointer-events-none z-0" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

            {{-- INLINE CONFIRMATION OVERLAY --}}
            <div id="inlineConfirmOverlay" class="hidden absolute inset-0 top-[64px] bottom-[60px] bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm z-40 flex items-center justify-center opacity-0 transition-opacity duration-200">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-11/12 max-w-[300px] text-center transform scale-95 transition-transform duration-200" id="inlineConfirmBox">
                    <div id="inlineConfirmIcon" class="w-12 h-12 mx-auto bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-full flex items-center justify-center text-xl mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 id="inlineConfirmTitle" class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-1">Konfirmasi</h4>
                    <p id="inlineConfirmText" class="text-xs text-gray-500 dark:text-gray-400 mb-5 leading-relaxed">Apakah Anda yakin?</p>
                    <div class="flex gap-2 justify-center">
                        <button onclick="closeInlineConfirm()" class="px-4 py-2 flex-1 text-[13px] font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-colors">Batal</button>
                        <button id="inlineConfirmActionBtn" class="px-4 py-2 flex-1 text-[13px] font-medium text-white bg-[#D00000] hover:bg-red-700 rounded-xl transition-colors shadow-sm">Yakin</button>
                    </div>
                </div>
            </div>

            {{-- AREA PESAN (SCROLLABLE) --}}
            <div class="flex-1 p-5 overflow-y-auto flex flex-col gap-3 custom-scrollbar relative z-10" id="chatBox">
                @php 
                    // FASE 1: Menampilkan pesan global lama sebagai fallback Grup Internal Toko
                    $messages = collect([]);
                    if(class_exists('\App\Models\Message')) {
                        try {
                            $messages = \App\Models\Message::with(['sender', 'reads', 'attachments'])->latest()->take(50)->get()->reverse(); 
                        } catch (\Exception $e) {}
                    }
                    $lastDate = null;
                    $unreadSeparatorShown = false;
                @endphp
                
                @forelse($messages as $msg)
                    @php
                        $msgDate = $msg->created_at->format('Y-m-d');
                        $today = \Carbon\Carbon::now()->format('Y-m-d');
                        $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');
                        $dateLabel = ($msgDate == $today) ? 'Hari Ini' : (($msgDate == $yesterday) ? 'Kemarin' : $msg->created_at->format('d M Y'));
                        $isReadByMe = $msg->reads->contains('user_id', Auth::id());
                        $isReadByOthers = $msg->reads->count() > 0;
                    @endphp

                    @if($lastDate != $msgDate)
                        <div class="flex justify-center my-3">
                            <span class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm text-gray-500 dark:text-gray-400 text-[11px] font-bold px-4 py-1.5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">{{ $dateLabel }}</span>
                        </div>
                        @php $lastDate = $msgDate; @endphp
                    @endif

                    @if($msg->from_user_id != Auth::id() && !$isReadByMe && !$unreadSeparatorShown)
                        <div id="unreadMarker" class="flex items-center justify-center gap-3 my-4 transition-all duration-1000">
                            <div class="h-px bg-red-200 dark:bg-red-900/50 flex-1"></div>
                            <span class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm text-[#D00000] text-[10px] font-black px-4 py-1.5 rounded-full shadow-sm border border-red-200 dark:border-red-900/50 transition-colors">{{ $unreadMsg ?? 0 }} PESAN BARU</span>
                            <div class="h-px bg-red-200 dark:bg-red-900/50 flex-1"></div>
                        </div>
                        @php $unreadSeparatorShown = true; @endphp
                    @endif

                    @if($msg->from_user_id == Auth::id())
                        {{-- CHAT SAYA (KANAN) --}}
                        <div class="flex justify-end w-full group mb-1 chat-bubble-wrapper my-chat-bubble" data-id="{{ $msg->id }}" data-read="{{ $isReadByOthers ? 'true' : 'false' }}">
                            <div class="hidden group-hover:flex flex-col justify-center items-center gap-1 pr-2 opacity-50 hover:opacity-100 transition-opacity">
                                <button onclick="editPesan({{ $msg->id }}, {{ json_encode($msg->content) }})" class="text-[10px] text-gray-500 hover:text-blue-600 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-6 h-6 rounded-full shadow-sm flex items-center justify-center transition-colors"><i class="fas fa-pen"></i></button>
                                <button onclick="hapusPesan({{ $msg->id }})" class="text-[10px] text-gray-500 hover:text-red-600 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-6 h-6 rounded-full shadow-sm flex items-center justify-center transition-colors"><i class="fas fa-trash"></i></button>
                            </div>
                            <div class="flex flex-col items-end max-w-[85%] relative">
                                <div class="bg-[#dcf8c6] dark:bg-[#005c4b] border border-[#c4e8ab] dark:border-[#004d3e] p-2.5 px-3 rounded-2xl rounded-tr-none shadow-sm relative z-10 transition-colors">
                                    @if($msg->attachments && $msg->attachments->count() > 0)
                                        @php 
                                            $att = $msg->attachments->first(); 
                                            $isImage = str_starts_with($att->file_type, 'image/');
                                            $isAudio = str_starts_with($att->file_type, 'audio/') || str_starts_with($att->file_type, 'video/webm');
                                        @endphp
                                        @if($isImage)
                                            <img src="{{ $att->file_path }}" onclick="openLightbox('{{ $att->file_path }}')" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mb-2 border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90 transition-opacity">
                                        @elseif($isAudio)
                                            <div class="flex items-center gap-3 bg-black/5 dark:bg-white/5 p-1.5 rounded-full mb-2 w-full max-w-[220px]">
                                                <button type="button" onclick="event.stopPropagation(); toggleVoiceNote(this, this.nextElementSibling);" class="w-7 h-7 flex-shrink-0 bg-[#D00000] dark:bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm outline-none">
                                                    <i class="fas fa-play text-[10px] ml-0.5"></i>
                                                </button>
                                                <audio onended="this.previousElementSibling.innerHTML='<i class=\'fas fa-play text-[10px] ml-0.5\'></i>'" class="hidden">
                                                    <source src="{{ $att->file_path }}" type="audio/webm">
                                                    <source src="{{ $att->file_path }}" type="audio/mp3">
                                                    <source src="{{ $att->file_path }}" type="audio/ogg">
                                                    <source src="{{ $att->file_path }}" type="{{ $att->file_type }}">
                                                </audio>
                                                <div class="flex-1 flex items-center h-4 gap-[2px]">
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/2 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-3/4 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/3 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-2/3 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/4 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/2 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-3/4 rounded-full"></div>
                                                </div>
                                                <i class="fas fa-microphone text-[10px] text-gray-400 mr-2"></i>
                                            </div>
                                        @else
                                            <a href="{{ $att->file_path }}" target="_blank" class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-2 rounded-lg mb-2 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"><i class="fas fa-file-download text-[#D00000]"></i><span class="text-xs text-blue-600 dark:text-blue-400 underline truncate max-w-[150px]">{{ $att->file_name }}</span></a>
                                        @endif
                                    @endif
                                    <p class="text-[13px] text-gray-900 dark:text-[#e9edef] leading-relaxed break-words">{{ $msg->content }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1 -mb-1">
                                        <p class="text-[10px] font-medium text-gray-500 dark:text-[#8696a0]">{{ $msg->created_at->format('H:i') }}</p>
                                        <i class="fas fa-check-double text-[12px] {{ $isReadByOthers ? 'text-[#53bdeb]' : 'text-gray-400 dark:text-[#8696a0]' }}"></i>
                                    </div>
                                </div>
                                <div class="absolute top-0 -right-2 w-3 h-3 bg-[#dcf8c6] dark:bg-[#005c4b] border-r border-t border-[#c4e8ab] dark:border-[#004d3e] transform rotate-45 translate-y-1.5 -translate-x-1.5 rounded-sm z-0 transition-colors"></div>
                            </div>
                        </div>
                    @else
                        {{-- CHAT ORANG LAIN (KIRI) --}}
                        <div class="flex justify-start w-full mb-1 chat-bubble-wrapper" data-id="{{ $msg->id }}">
                            <div class="flex flex-col items-start max-w-[85%] relative">
                                <div class="bg-white dark:bg-[#202c33] border border-gray-200 dark:border-gray-800 p-2.5 px-3 rounded-2xl rounded-tl-none shadow-sm relative z-10 transition-colors">
                                    <span class="text-[12px] font-black text-[#D00000] dark:text-[#f87171] mb-1 block leading-none">{{ $msg->sender->name ?? 'Sistem' }}</span>
                                    
                                    @if($msg->attachments && $msg->attachments->count() > 0)
                                        @php 
                                            $att = $msg->attachments->first(); 
                                            $isImage = str_starts_with($att->file_type, 'image/');
                                            $isAudio = str_starts_with($att->file_type, 'audio/') || str_starts_with($att->file_type, 'video/webm');
                                        @endphp
                                        @if($isImage)
                                            <img src="{{ $att->file_path }}" onclick="openLightbox('{{ $att->file_path }}')" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mb-2 border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90 transition-opacity">
                                        @elseif($isAudio)
                                            <div class="flex items-center gap-3 bg-black/5 dark:bg-white/5 p-1.5 rounded-full mb-2 w-full max-w-[220px]">
                                                <button type="button" onclick="event.stopPropagation(); toggleVoiceNote(this, this.nextElementSibling);" class="w-7 h-7 flex-shrink-0 bg-[#D00000] dark:bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm outline-none">
                                                    <i class="fas fa-play text-[10px] ml-0.5"></i>
                                                </button>
                                                <audio onended="this.previousElementSibling.innerHTML='<i class=\'fas fa-play text-[10px] ml-0.5\'></i>'" class="hidden">
                                                    <source src="{{ $att->file_path }}" type="audio/webm">
                                                    <source src="{{ $att->file_path }}" type="audio/mp3">
                                                    <source src="{{ $att->file_path }}" type="audio/ogg">
                                                    <source src="{{ $att->file_path }}" type="{{ $att->file_type }}">
                                                </audio>
                                                <div class="flex-1 flex items-center h-4 gap-[2px]">
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/2 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-3/4 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/3 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-2/3 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/4 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-1/2 rounded-full"></div>
                                                    <div class="w-1 bg-black/30 dark:bg-white/30 h-3/4 rounded-full"></div>
                                                </div>
                                                <i class="fas fa-microphone text-[10px] text-gray-400 mr-2"></i>
                                            </div>
                                        @else
                                            <a href="{{ $att->file_path }}" target="_blank" class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-2 rounded-lg mb-2 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"><i class="fas fa-file-download text-[#D00000]"></i><span class="text-xs text-blue-600 dark:text-blue-400 underline truncate max-w-[150px]">{{ $att->file_name }}</span></a>
                                        @endif
                                    @endif

                                    <p class="text-[13px] text-gray-800 dark:text-[#e9edef] leading-relaxed break-words">{{ $msg->content }}</p>
                                    <div class="flex justify-end gap-1 mt-1 -mb-1">
                                        <p class="text-[10px] font-medium text-gray-500 dark:text-[#8696a0]">{{ $msg->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                                <div class="absolute top-0 -left-2 w-3 h-3 bg-white dark:bg-[#202c33] border-l border-t border-gray-200 dark:border-gray-800 transform -rotate-45 translate-y-1.5 translate-x-1.5 rounded-sm z-0 transition-colors"></div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div id="emptyChatState" class="h-full flex flex-col items-center justify-center opacity-80 relative z-10">
                        <div class="w-20 h-20 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center mb-4 shadow text-gray-300 dark:text-gray-600 text-3xl transition-colors"><i class="fas fa-comments"></i></div>
                        <p class="text-base font-black text-gray-600 dark:text-gray-300">Belum Ada Pesan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center max-w-[250px]">Kirim pesan pertama untuk memulai percakapan di grup ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- TOMBOL AUTO SCROLL KE BAWAH --}}
            <button id="btnScrollDown" onclick="scrollToBottom(true)" class="absolute bottom-[80px] right-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:text-[#D00000] dark:hover:text-[#D00000] w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all opacity-0 translate-y-4 pointer-events-none z-20">
                <i class="fas fa-chevron-down"></i>
            </button>

            {{-- PREVIEW LAMPIRAN --}}
            <div id="chatAttachmentPreview" class="hidden p-2 px-4 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between z-20">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-[#D00000] text-white flex items-center justify-center"><i id="previewIcon" class="fas fa-file"></i></div>
                    <span id="previewName" class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate w-48"></span>
                </div>
                <button type="button" onclick="clearAttachment()" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 w-6 h-6 flex items-center justify-center rounded-full hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors"><i class="fas fa-times"></i></button>
            </div>

            {{-- FOOTER / FORM KIRIM PESAN --}}
            <div class="p-2 bg-gray-100 dark:bg-gray-800 shrink-0 border-t border-gray-200 dark:border-gray-700 z-20 transition-colors flex items-center gap-1.5 relative">
                
                {{-- EMOJI PICKER --}}
                <div id="emojiPicker" class="hidden absolute bottom-12 left-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl p-2 z-50 grid grid-cols-5 gap-1 w-48">
                    @php $emojis = ['😀','😂','🥰','😎','😭','😡','👍','🙏','🔥','🎉','💯','✅','❌','⚠️','✨']; @endphp
                    @foreach($emojis as $emoji)
                        <button type="button" onclick="insertEmoji('{{ $emoji }}')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded text-base">{{ $emoji }}</button>
                    @endforeach
                </div>

                <button type="button" onclick="toggleEmojiPicker()" class="w-8 h-8 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex justify-center items-center shrink-0">
                    <i class="far fa-smile text-lg"></i>
                </button>
                <form id="chatForm" action="{{ route('pesan.store') ?? '#' }}" method="POST" enctype="multipart/form-data" class="flex-1 m-0 relative">
                    @csrf
                    <input type="file" id="chatFileInput" name="file" class="hidden" onchange="previewAttachment(this)">
                    <input type="text" id="chatInput" name="content" placeholder="Ketik pesan..." autocomplete="off" class="w-full text-[13px] bg-white dark:bg-[#2a3942] border-none text-gray-800 dark:text-gray-100 rounded-lg focus:ring-0 py-2 px-3 shadow-sm transition-colors">
                    <button type="submit" class="absolute right-1 top-1 bottom-1 bg-[#D00000] text-white w-8 rounded flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm">
                        <i class="fas fa-paper-plane text-xs ml-0.5"></i>
                    </button>
                </form>
                <button type="button" id="btnVoiceNote" class="w-8 h-8 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex justify-center items-center shrink-0 relative" title="{{ __('hold_to_record') }}">
                    <i class="fas fa-microphone text-lg"></i>
                    <span id="recordingIndicator" class="hidden absolute -top-1 -right-1 flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                </button>
            </div>
        </div>

        {{-- INLINE CHAT MODAL (EDIT / DELETE) --}}
        <div id="inlineChatModal" class="hidden absolute inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity opacity-0 duration-200">
            <div class="bg-white dark:bg-gray-800 w-[85%] rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-5 transform scale-95 transition-transform duration-200" id="inlineChatModalContent">
                <h4 id="inlineModalTitle" class="font-bold text-gray-800 dark:text-gray-100 mb-3 text-sm"></h4>
                <div id="inlineModalBody" class="mb-4"></div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeInlineModal()" class="px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">{{ __('Cancel') ?? 'Batal' }}</button>
                    <button type="button" id="inlineModalConfirmBtn" class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">{{ __('Confirm') ?? 'Ya' }}</button>
                </div>
            </div>
        </div>

    </div>
</div>

</header>

{{-- IMAGE LIGHTBOX MODAL --}}
<div id="imageLightbox" class="hidden fixed inset-0 z-[100] bg-black/80 backdrop-blur-md flex items-center justify-center opacity-0 transition-opacity duration-300" onclick="closeLightbox(event)">
    <div class="relative max-w-5xl max-h-[90vh] p-4 flex flex-col items-center justify-center">
        <button type="button" onclick="closeLightbox(event)" class="absolute top-0 right-0 lg:-right-10 w-10 h-10 bg-black/50 hover:bg-[#D00000] text-white rounded-full flex items-center justify-center transition-colors shadow-lg z-50">
            <i class="fas fa-times text-xl"></i>
        </button>
        <img id="lightboxImage" src="" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl transform transition-transform duration-300 cursor-zoom-in" onclick="toggleZoom(event)">
    </div>
</div>

{{-- FORM EDIT & HAPUS --}}
<form id="formEditPesan" method="POST" class="hidden">@csrf @method('PUT') <input type="hidden" name="content" id="inputEditPesan"></form>
<form id="formHapusPesan" method="POST" class="hidden">@csrf @method('DELETE')</form>

<style>
    /* Diubah sedikit untuk mendukung CSS Variables dari JS */
    #chatBox::-webkit-scrollbar { width: 6px; }
    #chatBox::-webkit-scrollbar-track { background: transparent; }
    #chatBox::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    #chatBox::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    html.dark #chatBox::-webkit-scrollbar-thumb { background: #4b5563; }
    html.dark #chatBox::-webkit-scrollbar-thumb:hover { background: #6b7280; }
</style>

<script>
    let isUserScrolling = false;
    let syncInterval = null;

    // FUNGSI DROPDOWN (Pindah kesini agar jalan di semua halaman, termasuk Dashboard)
    function toggleDropdown() { closeAllDropdowns('dropdownUser'); document.getElementById('dropdownUser').classList.toggle('hidden'); }
    function toggleNotif() { closeAllDropdowns('dropdownNotif'); document.getElementById('dropdownNotif').classList.toggle('hidden'); }
    function closeAllDropdowns(exceptId) {
        const dropdowns = ['dropdownUser', 'dropdownNotif', 'dropdownMessage'];
        dropdowns.forEach(id => {
            let el = document.getElementById(id);
            if (el && id !== exceptId) el.classList.add('hidden');
        });
    }
    
    function toggleSidebar() { 
        document.getElementById('sidebar').classList.toggle('-translate-x-full'); 
        document.getElementById('overlay').classList.toggle('hidden'); 
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);
    });
    window.addEventListener('click', function(e) {
        // Cek klik di luar dropdown navigasi utama
        if (!e.target.closest('button[onclick*="toggleDropdown"]') && 
            !e.target.closest('button[onclick*="toggleNotif"]') &&
            !e.target.closest('button[onclick*="toggleChatDropdown"]') && 
            !e.target.closest('#dropdownUser') && 
            !e.target.closest('#dropdownNotif') && 
            !e.target.closest('#dropdownMessage') &&
            !e.target.closest('#imageLightbox')) { 
            closeAllDropdowns(''); 
        }

        // Cek klik di luar elemen mini chat
        let emojiPicker = document.getElementById('emojiPicker');
        let optionsDropdown = document.getElementById('chatOptionsDropdown');
        
        if (emojiPicker && !emojiPicker.classList.contains('hidden')) {
            if (!e.target.closest('#emojiPicker') && !e.target.closest('button[onclick*="toggleEmojiPicker"]')) {
                emojiPicker.classList.add('hidden');
            }
        }

        if (optionsDropdown && !optionsDropdown.classList.contains('hidden')) {
            if (!e.target.closest('#chatOptionsDropdown') && !e.target.closest('#btnChatOptionsToggle')) {
                optionsDropdown.classList.add('hidden');
            }
        }

        let searchBar = document.getElementById('chatSearchBar');
        if (searchBar && !searchBar.classList.contains('hidden')) {
            if (!e.target.closest('#chatSearchBar') && !e.target.closest('#btnChatSearchToggle')) {
                searchBar.classList.add('hidden');
                document.getElementById('chatSearchInput').value = '';
                searchChat();
            }
        }
    });

    function getLastMessageId() {
        let bubbles = document.querySelectorAll('.chat-bubble-wrapper');
        if (bubbles.length === 0) return 0;
        return bubbles[bubbles.length - 1].getAttribute('data-id');
    }

    function getMyUnreadMessageIds() {
        let unreadMyMsgs = document.querySelectorAll('.my-chat-bubble[data-read="false"]');
        return Array.from(unreadMyMsgs).map(el => el.getAttribute('data-id')).filter(id => !id.startsWith('temp_'));
    }

    // ==========================================
    // 1. SMART POLLING (REALTIME SYNC)
    // ==========================================
    function startSyncing() {
        if(syncInterval) clearInterval(syncInterval);
        
        syncInterval = setInterval(() => {
            let lastId = getLastMessageId();
            let myUnreadIds = getMyUnreadMessageIds();
            
            fetch(`{{ route('pesan.sync') ?? '#' }}?last_id=${lastId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ my_message_ids: myUnreadIds })
            })
            .then(res => res.json())
            .then(data => {
                // A. Update Centang Biru
                if(data.read_updates && data.read_updates.length > 0) {
                    data.read_updates.forEach(id => {
                        let bubble = document.querySelector(`.my-chat-bubble[data-id="${id}"]`);
                        if(bubble) {
                            bubble.setAttribute('data-read', 'true');
                            let icon = bubble.querySelector('.fa-check-double');
                            // Ditambahkan deteksi dark mode untuk centang biru
                            if(icon) {
                                icon.classList.remove('text-gray-400', 'dark:text-gray-500');
                                icon.classList.add('text-[#3b82f6]', 'dark:text-[#60a5fa]');
                            }
                        }
                    });
                }

                // B. Render Pesan Baru
                if(data.new_messages && data.new_messages.length > 0) {
                    let chatMenu = document.getElementById('dropdownMessage');
                    let isChatOpen = !chatMenu.classList.contains('hidden');

                    data.new_messages.forEach(msg => {
                        if(!msg.is_mine && !document.querySelector(`.chat-bubble-wrapper[data-id="${msg.id}"]`)) {
                            appendOtherMessage(msg.id, msg.sender_name, msg.content, msg.time, msg.attachments);
                        }
                    });
                    
                    if(isChatOpen) {
                        markMessagesAsRead();
                        if(!isUserScrolling) scrollToBottom(true);
                    }
                }

                // C. Update Badge
                if(data.unread_count !== undefined) {
                    let badge = document.getElementById('chatBadge');
                    if (data.unread_count > 0) {
                        if(badge) { badge.style.display = 'inline-block'; badge.innerText = data.unread_count; }
                    } else {
                        if(badge) badge.style.display = 'none';
                        let um = document.getElementById('unreadMarker');
                        if(um) um.style.display = 'none';
                    }
                }
            })
            .catch(err => {});
        }, 3000);
    }

    // ==========================================
    // 2. KIRIM PESAN AJAX & LAMPIRAN
    // ==========================================
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        let input = document.getElementById('chatInput');
        let fileInput = document.getElementById('chatFileInput');
        let content = input.value;
        let hasFile = fileInput.files.length > 0;
        
        if(content.trim() === '' && !hasFile) return;

        let tempId = 'temp_' + Date.now();
        let timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        
        // Buat dummy preview untuk pesan yang kita kirim
        let attachmentHtml = '';
        if(hasFile) {
            let file = fileInput.files[0];
            let isImage = file.type.startsWith('image/');
            if(isImage) {
                let url = URL.createObjectURL(file);
                attachmentHtml = `<img src="${url}" onclick="openLightbox('${url}')" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mb-2 border border-[#c4e8ab] dark:border-[#004d3e] cursor-pointer hover:opacity-90 transition-opacity">`;
            } else {
                attachmentHtml = `<div class="flex items-center gap-2 bg-black/5 dark:bg-black/20 p-2 rounded-lg mb-2"><i class="fas fa-file text-[#D00000]"></i><span class="text-xs truncate max-w-[150px]">${file.name}</span></div>`;
            }
        }
        
        // Gunakan FormData untuk mendukung file (sebelum input dikosongkan)
        let formData = new FormData(this);

        appendMyMessage(tempId, content, timeNow, attachmentHtml);
        
        input.value = '';
        clearAttachment();
        scrollToBottom(true);

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.message) {
                let tempBubble = document.querySelector(`.chat-bubble-wrapper[data-id="${tempId}"]`);
                if(tempBubble) {
                    tempBubble.setAttribute('data-id', data.message.id);
                    
                    let editBtn = tempBubble.querySelector('button[onclick^="editPesan"]');
                    let delBtn = tempBubble.querySelector('button[onclick^="hapusPesan"]');
                    
                    if(editBtn) {
                        let cleanContent = data.message.content.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                        editBtn.setAttribute('onclick', `editPesan('${data.message.id}', '${cleanContent}')`);
                    }
                    if(delBtn) {
                        delBtn.setAttribute('onclick', `hapusPesan('${data.message.id}')`);
                    }
                }
            }
        }).catch(err => {});
    });

    function appendDateSeparatorIfNotExists() {
        let chatBox = document.getElementById('chatBox');
        let hasToday = Array.from(chatBox.querySelectorAll('span')).some(el => el.textContent.trim().toUpperCase() === 'HARI INI');
        if(!hasToday) {
            let sep = `<div class="flex justify-center my-4 relative">
                <div class="h-px bg-gray-200 dark:bg-gray-700 absolute top-1/2 left-0 right-0 z-0"></div>
                <span class="bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-[10px] font-black px-4 py-1.5 rounded-full shadow-sm relative z-10 uppercase transition-colors tracking-widest border border-gray-200 dark:border-gray-700">Hari Ini</span>
            </div>`;
            chatBox.insertAdjacentHTML('beforeend', sep);
        }
    }

    function appendMyMessage(id, content, time, attachmentHtml = '') {
        let chatBox = document.getElementById('chatBox');
        let emptyState = document.getElementById('emptyChatState');
        if (emptyState) emptyState.remove();

        appendDateSeparatorIfNotExists();

        // Bersihkan escape chars untuk parameter fungsi onclick
        let cleanContent = content.replace(/'/g, "\\'").replace(/"/g, '&quot;');

        let html = `
        <div class="flex justify-end w-full group mb-1 chat-bubble-wrapper my-chat-bubble" data-id="${id}" data-read="false">
            <div class="hidden group-hover:flex flex-col justify-center items-center gap-1 pr-2 opacity-50 hover:opacity-100 transition-opacity">
                <button onclick="editPesan('${id}', '${cleanContent}')" class="text-[10px] text-gray-500 hover:text-blue-600 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-6 h-6 rounded-full shadow-sm flex items-center justify-center transition-colors"><i class="fas fa-pen"></i></button>
                <button onclick="hapusPesan('${id}')" class="text-[10px] text-gray-500 hover:text-red-600 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-6 h-6 rounded-full shadow-sm flex items-center justify-center transition-colors"><i class="fas fa-trash"></i></button>
            </div>
            <div class="flex flex-col items-end max-w-[85%] relative">
                <div class="bg-[#dcf8c6] dark:bg-[#005c4b] border border-[#c4e8ab] dark:border-[#004d3e] p-2.5 px-3 rounded-2xl rounded-tr-none shadow-sm relative z-10 transition-colors">
                    ${attachmentHtml}
                    <p class="text-[13px] text-gray-900 dark:text-[#e9edef] leading-relaxed break-words">${content}</p>
                    <div class="flex items-center justify-end gap-1 mt-1 -mb-1">
                        <p class="text-[10px] font-medium text-gray-500 dark:text-[#8696a0]">${time}</p>
                        <i class="fas fa-check-double text-[12px] text-gray-400 dark:text-[#8696a0]"></i>
                    </div>
                </div>
                <div class="absolute top-0 -right-2 w-3 h-3 bg-[#dcf8c6] dark:bg-[#005c4b] border-r border-t border-[#c4e8ab] dark:border-[#004d3e] transform rotate-45 translate-y-1.5 -translate-x-1.5 rounded-sm z-0 transition-colors"></div>
            </div>
        </div>`;
        chatBox.insertAdjacentHTML('beforeend', html);
    }

    function appendOtherMessage(id, senderName, content, time, attachments = null) {
        let chatBox = document.getElementById('chatBox');
        let emptyState = document.getElementById('emptyChatState');
        if (emptyState) emptyState.remove();

        appendDateSeparatorIfNotExists();

        let attachmentHtml = '';
        if(attachments && attachments.length > 0) {
            let att = attachments[0];
            let isImage = att.file_type.startsWith('image/');
            let isAudio = att.file_type.startsWith('audio/') || att.file_type.startsWith('video/webm');
            
            if(isImage) {
                attachmentHtml = `<img src="${att.file_path}" onclick="openLightbox('${att.file_path}')" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mb-2 border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90 transition-opacity">`;
            } else if(isAudio) {
                attachmentHtml = `
                    <div class="flex items-center gap-3 bg-black/5 dark:bg-white/5 p-1.5 rounded-full mb-2 w-full max-w-[220px]">
                        <button type="button" onclick="event.stopPropagation(); toggleVoiceNote(this, this.nextElementSibling);" class="w-7 h-7 flex-shrink-0 bg-[#D00000] dark:bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm outline-none">
                            <i class="fas fa-play text-[10px] ml-0.5"></i>
                        </button>
                        <audio onended="this.previousElementSibling.innerHTML='<i class=\\'fas fa-play text-[10px] ml-0.5\\'></i>'" class="hidden">
                            <source src="${att.file_path}" type="audio/webm">
                            <source src="${att.file_path}" type="audio/mp3">
                            <source src="${att.file_path}" type="audio/ogg">
                            <source src="${att.file_path}" type="${att.file_type}">
                        </audio>
                        <div class="flex-1 flex items-center h-4 gap-[2px]">
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-1/2 rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-3/4 rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-1/3 rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-2/3 rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-1/4 rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-full rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-1/2 rounded-full"></div>
                            <div class="w-1 bg-black/30 dark:bg-white/30 h-3/4 rounded-full"></div>
                        </div>
                        <i class="fas fa-microphone text-[10px] text-gray-400 mr-2"></i>
                    </div>`;
            } else {
                attachmentHtml = `<a href="${att.file_path}" target="_blank" class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-2 rounded-lg mb-2 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"><i class="fas fa-file-download text-[#D00000]"></i><span class="text-xs text-blue-600 dark:text-blue-400 underline truncate max-w-[150px]">${att.file_name}</span></a>`;
            }
        }

        let html = `
        <div class="flex justify-start w-full mb-1 chat-bubble-wrapper" data-id="${id}">
            <div class="flex flex-col items-start max-w-[85%] relative">
                <div class="bg-white dark:bg-[#202c33] border border-gray-200 dark:border-gray-800 p-2.5 px-3 rounded-2xl rounded-tl-none shadow-sm relative z-10 transition-colors">
                    <span class="text-[12px] font-black text-[#D00000] dark:text-[#f87171] mb-1 block leading-none">${senderName}</span>
                    ${attachmentHtml}
                    <p class="text-[13px] text-gray-800 dark:text-[#e9edef] leading-relaxed break-words">${content}</p>
                    <div class="flex justify-end gap-1 mt-1 -mb-1">
                        <p class="text-[10px] font-medium text-gray-500 dark:text-[#8696a0]">${time}</p>
                    </div>
                </div>
                <div class="absolute top-0 -left-2 w-3 h-3 bg-white dark:bg-[#202c33] border-l border-t border-gray-200 dark:border-gray-800 transform -rotate-45 translate-y-1.5 translate-x-1.5 rounded-sm z-0 transition-colors"></div>
            </div>
        </div>`;
        chatBox.insertAdjacentHTML('beforeend', html);
    }

    // ==========================================
    // 3. FUNGSI UI & LAINNYA
    // ==========================================
    // --- EMOJI ---
    function toggleEmojiPicker() {
        document.getElementById('emojiPicker').classList.toggle('hidden');
    }
    function insertEmoji(char) {
        let input = document.getElementById('chatInput');
        input.value += char;
        input.focus();
        toggleEmojiPicker();
    }

    // --- LAMPIRAN ---
    function previewAttachment(input) {
        let preview = document.getElementById('chatAttachmentPreview');
        let nameEl = document.getElementById('previewName');
        let iconEl = document.getElementById('previewIcon');
        
        if(input.files && input.files[0]) {
            let file = input.files[0];
            nameEl.innerText = file.name;
            
            if(file.type.startsWith('image/')) iconEl.className = 'fas fa-image';
            else if(file.type.startsWith('audio/')) iconEl.className = 'fas fa-music';
            else if(file.type.startsWith('video/')) iconEl.className = 'fas fa-video';
            else iconEl.className = 'fas fa-file-alt';
            
            preview.classList.remove('hidden');
        } else {
            clearAttachment();
        }
    }
    function clearAttachment() {
        document.getElementById('chatFileInput').value = '';
        document.getElementById('chatAttachmentPreview').classList.add('hidden');
    }

    // --- PENCARIAN ---
    function toggleChatSearch() {
        let bar = document.getElementById('chatSearchBar');
        bar.classList.toggle('hidden');
        if(!bar.classList.contains('hidden')) {
            document.getElementById('chatSearchInput').focus();
        } else {
            document.getElementById('chatSearchInput').value = '';
            searchChat(); // reset
        }
    }
    function searchChat() {
        let val = document.getElementById('chatSearchInput').value.toLowerCase();
        let bubbles = document.querySelectorAll('.chat-bubble-wrapper');
        bubbles.forEach(b => {
            let text = b.innerText.toLowerCase();
            if(text.includes(val) || val === '') {
                b.style.display = 'flex';
            } else {
                b.style.display = 'none';
            }
        });
    }

    // --- OPSI CHAT ---
    function toggleChatOptionsDropdown(e) {
        if(e) e.stopPropagation();
        document.getElementById('chatOptionsDropdown').classList.toggle('hidden');
    }

    let clearActionType = '';
    function showInlineClearConfirm(type) {
        document.getElementById('chatOptionsDropdown').classList.add('hidden');
        clearActionType = type;
        
        let title = document.getElementById('inlineConfirmTitle');
        let text = document.getElementById('inlineConfirmText');
        
        if(type === 'local') {
            title.innerText = "Bersihkan (Lokal)";
            text.innerText = "{{ __('delete_local_chat_confirm') }}";
        } else {
            title.innerText = "{{ __('delete_global') }}";
            text.innerText = "{{ __('delete_global_confirm_desc') }}";
        }
        
        let overlay = document.getElementById('inlineConfirmOverlay');
        let box = document.getElementById('inlineConfirmBox');
        
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            box.classList.remove('scale-95');
        }, 10);
    }

    function closeInlineConfirm() {
        let overlay = document.getElementById('inlineConfirmOverlay');
        let box = document.getElementById('inlineConfirmBox');
        
        overlay.classList.add('opacity-0');
        box.classList.add('scale-95');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200);
    }

    document.getElementById('inlineConfirmActionBtn').addEventListener('click', function() {
        if(clearActionType === 'local') {
            document.getElementById('chatBox').innerHTML = `
                <div id="emptyChatState" class="h-full flex flex-col items-center justify-center opacity-80 relative z-10">
                    <div class="w-20 h-20 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center mb-4 shadow text-gray-300 dark:text-gray-600 text-3xl"><i class="fas fa-comments"></i></div>
                    <p class="text-base font-black text-gray-600 dark:text-gray-300">Riwayat Dibersihkan</p>
                </div>
            `;
            closeInlineConfirm();
        } else if(clearActionType === 'global') {
            let btn = this;
            let originalText = btn.innerText;
            btn.innerText = '{{ __('deleting') }}';
            btn.disabled = true;

            fetch("{{ route('pesan.clear_global') ?? '#' }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).then(res => res.json()).then(data => {
                btn.innerText = originalText;
                btn.disabled = false;
                closeInlineConfirm();
                
                if(data.success) {
                    document.getElementById('chatBox').innerHTML = '';
                } else {
                    Swal.fire('{{ __('access_denied_menu') }}', '{{ __('only_owner_can_delete_global') }}', 'error');
                }
            });
        }
    });

    // --- LIGHTBOX ---
    function openLightbox(src) {
        let lb = document.getElementById('imageLightbox');
        let img = document.getElementById('lightboxImage');
        img.src = src;
        img.classList.remove('scale-150', 'cursor-zoom-out');
        img.classList.add('cursor-zoom-in');
        
        lb.classList.remove('hidden');
        setTimeout(() => lb.classList.remove('opacity-0'), 10);
    }
    
    function closeLightbox(e) {
        if(e && e.target.id === 'lightboxImage') return; // Jangan tutup jika klik gambar langsung
        let lb = document.getElementById('imageLightbox');
        lb.classList.add('opacity-0');
        setTimeout(() => lb.classList.add('hidden'), 300);
    }

    function toggleZoom(e) {
        let img = e.target;
        if(img.classList.contains('scale-150')) {
            img.classList.remove('scale-150', 'cursor-zoom-out');
            img.classList.add('cursor-zoom-in');
        } else {
            img.classList.add('scale-150', 'cursor-zoom-out');
            img.classList.remove('cursor-zoom-in');
        }
    }

    // --- PESAN SUARA (WEB AUDIO API) ---
    let mediaRecorder;
    let audioChunks = [];
    let isRecording = false;
    let voiceBtn = document.getElementById('btnVoiceNote');
    let recIndicator = document.getElementById('recordingIndicator');

    if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        voiceBtn.addEventListener('mousedown', startRecording);
        voiceBtn.addEventListener('mouseup', stopRecording);
        voiceBtn.addEventListener('mouseleave', stopRecording);
        
        // Touch support for mobile
        voiceBtn.addEventListener('touchstart', (e) => { e.preventDefault(); startRecording(); });
        voiceBtn.addEventListener('touchend', stopRecording);
    }

    function startRecording() {
        if(isRecording) return;
        navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
            isRecording = true;
            recIndicator.classList.remove('hidden');
            voiceBtn.classList.add('text-red-500', 'animate-pulse');
            
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.ondataavailable = e => { audioChunks.push(e.data); };
            mediaRecorder.onstop = () => {
                let audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                audioChunks = [];
                
                // Masukkan ke file input (secara programmatic)
                let file = new File([audioBlob], "voice_note_" + Date.now() + ".webm", { type: 'audio/webm', lastModified: new Date().getTime() });
                let container = new DataTransfer();
                container.items.add(file);
                document.getElementById('chatFileInput').files = container.files;
                
                // Auto submit form
                document.getElementById('chatInput').value = '🎤 Pesan Suara';
                document.getElementById('chatForm').dispatchEvent(new Event('submit'));
            };
            mediaRecorder.start();
        }).catch(err => {
            console.error(err);
            Swal.fire('{{ __('access_denied_menu') }}', '{{ __('mic_access_denied') }}', 'warning');
        });
    }

    function stopRecording() {
        if(!isRecording) return;
        isRecording = false;
        recIndicator.classList.add('hidden');
        voiceBtn.classList.remove('text-red-500', 'animate-pulse');
        if(mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            // Matikan stream mic
            mediaRecorder.stream.getTracks().forEach(t => t.stop());
        }
    }

    function markMessagesAsRead() {
        fetch("{{ route('pesan.mark_read') ?? '#' }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
        }).then(res => res.json()).then(data => {
            if(data.success) {
                let badge = document.getElementById('chatBadge');
                if(badge) badge.style.display = 'none';
                
                let unreadMarker = document.getElementById('unreadMarker');
                if(unreadMarker) {
                    setTimeout(() => {
                        unreadMarker.style.opacity = '0';
                        setTimeout(() => unreadMarker.style.display = 'none', 1000);
                    }, 2500);
                }
            }
        }).catch(err => {});
    }

    let inlineModalAction = null;
    let inlineModalId = null;

    function closeInlineModal() {
        let m = document.getElementById('inlineChatModal');
        let mc = document.getElementById('inlineChatModalContent');
        m.classList.add('opacity-0');
        mc.classList.remove('scale-100');
        mc.classList.add('scale-95');
        setTimeout(() => m.classList.add('hidden'), 200);
    }

    function showInlineModal(title, bodyHtml, confirmText, confirmColorClass, actionType, id) {
        let m = document.getElementById('inlineChatModal');
        let mc = document.getElementById('inlineChatModalContent');
        
        document.getElementById('inlineModalTitle').innerText = title;
        document.getElementById('inlineModalBody').innerHTML = bodyHtml;
        
        let btn = document.getElementById('inlineModalConfirmBtn');
        btn.innerText = confirmText;
        btn.className = `px-3 py-1.5 text-xs font-bold text-white rounded-lg transition-colors ${confirmColorClass}`;
        
        inlineModalAction = actionType;
        inlineModalId = id;
        
        m.classList.remove('hidden');
        setTimeout(() => {
            m.classList.remove('opacity-0');
            mc.classList.remove('scale-95');
            mc.classList.add('scale-100');
            if(actionType === 'edit') {
                let input = document.getElementById('inlineEditInput');
                if(input) { input.focus(); input.setSelectionRange(input.value.length, input.value.length); }
            }
        }, 10);
    }

    document.getElementById('inlineModalConfirmBtn').addEventListener('click', function() {
        let btn = this;
        let originalText = btn.innerText;
        btn.innerText = 'Memproses...';
        btn.disabled = true;

        if(inlineModalAction === 'edit') {
            let n = document.getElementById('inlineEditInput').value;
            let oldContent = document.getElementById('inlineEditInput').dataset.old;
            if (n && n.trim() !== "" && n !== oldContent) {
                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('content', n);
                
                fetch('/pesan/' + inlineModalId, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                }).then(res => res.json()).then(data => {
                    btn.innerText = originalText; btn.disabled = false;
                    closeInlineModal();
                    if(data.success) {
                        let bubble = document.querySelector(`.chat-bubble-wrapper[data-id="${inlineModalId}"]`);
                        if(bubble) {
                            let p = bubble.querySelector('p');
                            if(p) p.innerText = n;
                            let editBtn = bubble.querySelector('button[onclick^="editPesan"]');
                            if(editBtn) {
                                let cleanContent = n.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                                editBtn.setAttribute('onclick', `editPesan('${inlineModalId}', '${cleanContent}')`);
                            }
                        }
                    }
                }).catch(err => { btn.innerText = originalText; btn.disabled = false; closeInlineModal(); });
            } else {
                btn.innerText = originalText; btn.disabled = false;
                closeInlineModal();
            }
        } else if(inlineModalAction === 'delete') {
            let formData = new FormData();
            formData.append('_method', 'DELETE');
            
            fetch('/pesan/' + inlineModalId, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            }).then(res => res.json()).then(data => {
                btn.innerText = originalText; btn.disabled = false;
                closeInlineModal();
                if(data.success) {
                    let bubble = document.querySelector(`.chat-bubble-wrapper[data-id="${inlineModalId}"]`);
                    if(bubble) bubble.remove();
                }
            }).catch(err => { btn.innerText = originalText; btn.disabled = false; closeInlineModal(); });
        }
    });

    function editPesan(id, oldContent) {
        let bodyHtml = `<textarea id="inlineEditInput" data-old="${oldContent.replace(/"/g, '&quot;')}" class="w-full text-[13px] bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500 py-2 px-3 shadow-sm transition-colors" rows="3">${oldContent}</textarea>`;
        showInlineModal("Edit Pesan", bodyHtml, "Simpan", "bg-blue-600 hover:bg-blue-700", "edit", id);
    }
    
    function hapusPesan(id) {
        let bodyHtml = `<p class="text-xs text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus pesan ini? Pesan yang dihapus tidak dapat dikembalikan.</p>`;
        showInlineModal("{{ __('delete_message') }}", bodyHtml, "{{ __('delete') }}", "bg-red-600 hover:bg-red-700", "delete", id);
    }

    function toggleChatDropdown() { 
        if (typeof closeAllDropdowns === 'function') {
            closeAllDropdowns('dropdownMessage'); 
        }
        let c = document.getElementById('dropdownMessage'); 
        c.classList.toggle('hidden'); 
        
        if (!c.classList.contains('hidden')) { 
            markMessagesAsRead();
            setTimeout(() => {
                let unreadMarker = document.getElementById('unreadMarker');
                if (unreadMarker && unreadMarker.style.display !== 'none') {
                    unreadMarker.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    scrollToBottom(true);
                }
            }, 50); 
        }
    }

    document.getElementById('chatBox').addEventListener('scroll', function() {
        let box = this;
        let btn = document.getElementById('btnScrollDown');
        
        if (box.scrollHeight - box.scrollTop - box.clientHeight > 150) {
            isUserScrolling = true;
            btn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
        } else {
            isUserScrolling = false;
            btn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
        }
    });

    function scrollToBottom(force = false) {
        if (!isUserScrolling || force) {
            setTimeout(() => { 
                let b = document.getElementById('chatBox'); 
                b.scrollTo({ top: b.scrollHeight, behavior: 'smooth' }); 
            }, 10);
        }
    }

    // --- MANAJEMEN PEMUTARAN AUDIO ---
    function toggleVoiceNote(btn, audioEl) {
        if (audioEl.paused) {
            // Hentikan semua audio yang sedang diputar di halaman ini
            document.querySelectorAll('audio').forEach(a => {
                if (a !== audioEl && !a.paused) {
                    a.pause();
                    let prevBtn = a.previousElementSibling;
                    if (prevBtn && prevBtn.tagName === 'BUTTON') {
                        prevBtn.innerHTML = '<i class="fas fa-play text-[10px] ml-0.5"></i>';
                    }
                }
            });
            // Putar audio yang dipilih
            audioEl.play();
            btn.innerHTML = '<i class="fas fa-pause text-[10px]"></i>';
        } else {
            // Pause jika audio tersebut sedang berjalan
            audioEl.pause();
            btn.innerHTML = '<i class="fas fa-play text-[10px] ml-0.5"></i>';
        }
    }

    document.addEventListener("DOMContentLoaded", function() { 
        startSyncing();
        let lb = document.getElementById('imageLightbox');
        if (lb) document.body.appendChild(lb);
    });

    // ==========================================
    // FUNGSI TEMA (DARK / LIGHT MODE)
    // ==========================================
    function setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark'); // Aktifkan mode gelap
            localStorage.setItem('theme', 'dark'); // Simpan di memori browser
        } else {
            document.documentElement.classList.remove('dark'); // Matikan mode gelap
            localStorage.setItem('theme', 'light'); // Simpan di memori browser
        }
        
        // RE-RENDER CHART JIKA ADA
        if (typeof renderChart === 'function') {
            let chartSelect = document.getElementById('chartFilter');
            if(chartSelect) {
                renderChart(chartSelect.value);
            }
        }
    }

    // ==========================================
    // FUNGSI LIVE SEARCH (AJAX)
    // ==========================================
    let searchTimeout = null;
    const searchInput = document.getElementById('globalSearchInput');
    const searchDropdown = document.getElementById('liveSearchDropdown');
    const searchResults = document.getElementById('liveSearchResults');
    const searchFooter = document.getElementById('liveSearchFooter');

    function closeLiveSearch() {
        searchDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
        searchDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
    }

    if(searchInput) {
        // Tutup dropdown jika klik di luar form
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                closeLiveSearch();
            }
        });

        // Tampilkan kembali dropdown saat input di-klik (jika ada isinya)
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.trim().length > 0 && searchResults.innerHTML.trim() !== '') {
                searchDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
                searchDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            }
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const keyword = this.value.trim();

            if (keyword.length === 0) {
                closeLiveSearch();
                searchResults.innerHTML = '';
                return;
            }

            // Animasi loading kecil
            searchDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
            searchDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            searchResults.innerHTML = `
                <div class="flex flex-col items-center justify-center py-6 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2 text-[#D00000]"></i>
                    <span class="text-[10px] font-bold tracking-widest uppercase">Mencari...</span>
                </div>
            `;
            searchFooter.classList.add('hidden');

            // Debounce 400ms
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('search') }}?keyword=${encodeURIComponent(keyword)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    let hasResults = false;

                    // Helper render item
                    const renderItem = (icon, title, subtitle, badgeText = null, badgeClass = '') => {
                        let badgeHtml = badgeText ? `<span class="${badgeClass} px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest ml-2 shrink-0">${badgeText}</span>` : '';
                        return `
                            <div class="flex items-start gap-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-xl transition-colors cursor-pointer group" onclick="document.getElementById('globalSearchForm').submit()">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0 group-hover:bg-white dark:group-hover:bg-gray-600 transition-colors shadow-sm">
                                    ${icon}
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <div class="flex justify-between items-center">
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate group-hover:text-[#D00000] dark:group-hover:text-red-400 transition-colors">${title}</p>
                                        ${badgeHtml}
                                    </div>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate mt-0.5">${subtitle}</p>
                                </div>
                            </div>
                        `;
                    };

                    // 1. Produk
                    if (data.products && data.products.length > 0) {
                        hasResults = true;
                        let html = `<div class="mb-2"><p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-2 mb-1">Master Barang</p>`;
                        data.products.forEach(p => {
                            html += renderItem(
                                '<i class="fas fa-box text-blue-500"></i>', 
                                p.nama_barang, 
                                `SKU: ${p.sku ?? '-'} &bull; Stok: ${p.stok} ${p.satuan}`,
                                p.stok <= (p.reorder_point ?? 0) ? 'Low' : '',
                                'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'
                            );
                        });
                        html += `</div>`;
                        searchResults.insertAdjacentHTML('beforeend', html);
                    }

                    // 2. Supplier
                    if (data.suppliers && data.suppliers.length > 0) {
                        hasResults = true;
                        let html = `<div class="mb-2"><p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-2 mb-1">Supplier / Mitra</p>`;
                        data.suppliers.forEach(s => {
                            html += renderItem('<i class="fas fa-truck-field text-emerald-500"></i>', s.nama_supplier, s.nama_pic ?? s.alamat ?? '-');
                        });
                        html += `</div>`;
                        searchResults.insertAdjacentHTML('beforeend', html);
                    }

                    // 3. Transaksi
                    if (data.transactions && data.transactions.length > 0) {
                        hasResults = true;
                        let html = `<div><p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-2 mb-1">Transaksi</p>`;
                        data.transactions.forEach(t => {
                            let icon = t.jenis_transaksi === 'masuk' ? '<i class="fas fa-arrow-down text-emerald-500"></i>' : (t.jenis_transaksi === 'keluar' ? '<i class="fas fa-arrow-up text-red-500"></i>' : '<i class="fas fa-file-invoice text-indigo-500"></i>');
                            let badgeClass = t.jenis_transaksi === 'masuk' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400';
                            html += renderItem(icon, t.no_transaksi, t.catatan ?? t.tujuan ?? '-', t.jenis_transaksi.toUpperCase(), badgeClass);
                        });
                        html += `</div>`;
                        searchResults.insertAdjacentHTML('beforeend', html);
                    }

                    if (hasResults) {
                        searchFooter.classList.remove('hidden');
                    } else {
                        searchResults.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-6 text-gray-400">
                                <i class="fas fa-search-minus text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                <span class="text-[10px] font-bold tracking-widest uppercase">Data tidak ditemukan</span>
                                <span class="text-xs mt-1">Coba kata kunci lain.</span>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error("Live search error:", error);
                    searchResults.innerHTML = `<div class="p-4 text-center text-xs text-red-500">Terjadi kesalahan koneksi.</div>`;
                });
            }, 400); // end debounce
        });
    }

    // Cek memori browser saat halaman pertama kali dimuat
    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    });
</script>