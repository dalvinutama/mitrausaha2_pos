<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">
        
        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')
            
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 text-gray-800 dark:text-gray-200 transition-colors duration-300">
                
                {{-- HEADER HALAMAN & BREADCRUMB --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') ?? '#' }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors" title="{{ __('to_dashboard') }}">
                                <i class="fas fa-home text-sm"></i>
                            </a>
                            <span class="text-[#D00000] dark:text-red-400">/</span>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('security') }}</span>
                            <span class="text-[#D00000] dark:text-red-400">/ {{ __('audit_trail') }}</span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('system_audit_log') }}</h2>
                            
                            {{-- TOOLTIP INFORMASI --}}
                            <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-circle-question text-gray-400 dark:text-gray-500 cursor-pointer text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        {{ __('audit_log_desc') }}
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                        </div>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('audit_log_subtitle') }}</p>
                    </div>
                </div>

                {{-- PANEL FILTER (TOP BAR) --}}
                <form method="GET" action="{{ url()->current() }}" class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col md:flex-row gap-4 justify-between items-center transition-colors duration-300">
                    
                    {{-- Input Search --}}
                    <div class="relative w-full md:w-1/3 xl:w-1/4">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="aktor" value="{{ request('aktor') }}" id="searchInput" placeholder="{{ __('search_activity_actor') }}" class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all shadow-sm">
                    </div>

                    {{-- Filters Section --}}
                    <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3 items-stretch sm:items-center">
                        
                        {{-- Date Range Picker --}}
                        <div class="relative flex items-center bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 shadow-sm">
                            <i class="far fa-calendar-alt text-gray-400 mr-2 text-sm"></i>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}" id="dateFilter" class="bg-transparent border-none p-0 text-sm text-gray-800 dark:text-gray-200 focus:ring-0 cursor-pointer outline-none">
                        </div>
                        
                        {{-- Dropdown Modul --}}
                        <select name="modul" id="moduleFilter" class="border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm cursor-pointer transition-colors appearance-none">
                            <option value="">{{ __('all_modules') }}</option>
                            @php
                                $modules = \App\Models\AuditLog::select('module')->distinct()->pluck('module');
                            @endphp
                            @foreach($modules as $mod)
                                <option value="{{ $mod }}" {{ request('modul') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                            @endforeach
                        </select>
                        
                        {{-- Tombol Filter --}}
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-md card-shadow-blue flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-filter text-xs"></i> {{ __('apply_filter') }}
                        </button>
                        
                        @if(request()->hasAny(['aktor', 'tanggal', 'modul']))
                        <a href="{{ url()->current() }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-xl font-bold transition-all text-sm flex items-center justify-center shadow-sm">
                            {{ __('reset') }}
                        </a>
                        @endif
                    </div>
                </form>

                {{-- TABEL RIWAYAT AKTIVITAS (MAIN CONTENT) --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors duration-300">
                    
                    <div class="overflow-x-auto custom-scrollbar -mx-6 px-6 lg:mx-0 lg:px-0">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            
                            {{-- Header Tabel Sticky --}}
                            <thead class="sticky top-0 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 z-10 shadow-sm">
                                <tr>
                                    <th class="py-3.5 px-4 rounded-tl-xl whitespace-nowrap">{{ __('date_time') }}</th>
                                    <th class="py-3.5 px-4">{{ __('actor_user') }}</th>
                                    <th class="py-3.5 px-4">{{ __('module') }}</th>
                                    <th class="py-3.5 px-4 text-center">{{ __('action') }}</th>
                                    <th class="py-3.5 px-4 w-1/3">{{ __('short_description') }}</th>
                                    <th class="py-3.5 px-4 text-center rounded-tr-xl">{{ __('details') }}</th>
                                </tr>
                            </thead>
                            
                            <tbody id="auditTableBody" class="divide-y divide-gray-100 dark:divide-gray-700/50 text-sm">
                                
                                @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors group">
                                    <td class="py-4 px-4 whitespace-nowrap align-top">
                                        <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $log->created_at->translatedFormat('d M Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $log->created_at->format('H:i:s') }} WIB</div>
                                    </td>
                                    <td class="py-4 px-4 align-top">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold shrink-0 shadow-sm">
                                                {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : 'S' }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 dark:text-gray-200 leading-tight">{{ $log->user ? $log->user->name : __('system') }}</div>
                                                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mt-0.5">{{ $log->user ? $log->user->role : 'SYSTEM' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 align-top">
                                        <span class="inline-flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300 uppercase text-xs">
                                            {{ $log->module }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center align-top">
                                        @php
                                            $actionColor = match($log->action) {
                                                'CREATE' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400',
                                                'UPDATE' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400',
                                                'DELETE' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-900/30 dark:text-gray-400'
                                            };
                                        @endphp
                                        <span class="{{ $actionColor }} border px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide shadow-sm inline-block">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 align-top text-gray-600 dark:text-gray-400 leading-relaxed break-words">
                                        {{ $log->description }}
                                    </td>
                                    <td class="py-4 px-4 text-center align-top">
                                        <button data-log="{{ htmlspecialchars(json_encode($log), ENT_QUOTES, 'UTF-8') }}" onclick="openDynamicDiffModal(this)" class="p-2 w-9 h-9 flex items-center justify-center bg-gray-100 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-blue-900/50 text-gray-600 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-400 rounded-lg transition-all mx-auto shadow-sm group-hover:scale-110" title="{{ __('view_data_comparison') }}">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-3 opacity-50 block"></i>
                                        {{ __('no_activity_history') }}
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL PERBANDINGAN DATA (DIFF VIEWER) --}}
    <div id="diffModal" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all opacity-0">
        {{-- Body Modal --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] md:max-h-[80vh] transform scale-95 transition-transform duration-300 border border-gray-200 dark:border-gray-700" id="diffModalContent">
            
            {{-- Header Modal --}}
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-800/80 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400 flex items-center justify-center shadow-inner">
                        <i class="fas fa-code-compare text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800 dark:text-white tracking-tight">Perbandingan Data Sistem</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="modalModuleName" class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-2 py-0.5 rounded-md uppercase tracking-wider">PRODUK</span>
                            <span id="modalActionBadge" class="text-xs font-black text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/40 px-2 py-0.5 rounded-md uppercase tracking-wider">UPDATE</span>
                        </div>
                    </div>
                </div>
                <button onclick="closeDiffModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-white dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-300 shadow-sm border border-gray-200 dark:border-gray-600 transition-all hover:rotate-90">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Content Modal --}}
            <div id="diffModalBodyContainer" class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-gray-800 relative">
                <!-- Konten dinamis dirender di sini via JS -->
            </div>

            {{-- Footer Modal --}}
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/80 flex justify-end backdrop-blur-md">
                <button onclick="closeDiffModal()" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold rounded-xl transition-all shadow-sm transform hover:-translate-y-0.5">
                    {{ __('close_details') }}
                </button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT UNTUK INTERAKSI MODAL --}}
    <script>
        function openDynamicDiffModal(btn) {
            const log = JSON.parse(btn.getAttribute('data-log'));
            const modal = document.getElementById('diffModal');
            const modalContent = document.getElementById('diffModalContent');
            const bodyContainer = document.getElementById('diffModalBodyContainer');
            
            // Set Header
            document.getElementById('modalModuleName').innerText = log.module;
            document.getElementById('modalActionBadge').innerText = log.action;
            
            // Color logic
            let color = 'blue';
            if(log.action === 'CREATE') color = 'emerald';
            if(log.action === 'DELETE') color = 'red';
            document.getElementById('modalActionBadge').className = `text-xs font-black text-${color}-600 dark:text-${color}-400 bg-${color}-100 dark:bg-${color}-900/40 px-2 py-0.5 rounded-md uppercase tracking-wider`;

            // Date processing
            let dateObj = new Date(log.created_at);
            let dateStr = dateObj.toLocaleString('id-ID', {day: 'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit'});
            let userName = log.user ? log.user.name : 'System';

            // Generate Diff HTML
            let oldHtml = '';
            let newHtml = '';
            
            if(log.old_values && Object.keys(log.old_values).length > 0) {
                for(let key in log.old_values) {
                    let val = typeof log.old_values[key] === 'object' ? JSON.stringify(log.old_values[key]) : log.old_values[key];
                    oldHtml += `<div class="flex py-1 border-b border-red-200/30 last:border-0"><span class="w-1/3 shrink-0 text-gray-500 dark:text-gray-400">${key}:</span> <span class="text-gray-700 dark:text-gray-300 break-words flex-1 font-semibold">${val ?? 'null'}</span></div>`;
                }
            } else {
                oldHtml = `<div class="text-gray-400 italic text-center py-4">{{ __('no_old_data') }}</div>`;
            }

            if(log.new_values && Object.keys(log.new_values).length > 0) {
                for(let key in log.new_values) {
                    let val = typeof log.new_values[key] === 'object' ? JSON.stringify(log.new_values[key]) : log.new_values[key];
                    newHtml += `<div class="flex py-1 border-b border-emerald-200/30 last:border-0"><span class="w-1/3 shrink-0 text-gray-500 dark:text-gray-400">${key}:</span> <span class="text-gray-700 dark:text-gray-300 break-words flex-1 font-semibold">${val ?? 'null'}</span></div>`;
                }
            } else {
                newHtml = `<div class="text-gray-400 italic text-center py-4">{{ __('no_new_data') }}</div>`;
            }

            bodyContainer.innerHTML = `
                <div class="mb-6 flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 text-sm">
                    <i class="fas fa-clock text-gray-400 ml-2"></i>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('performed_on') }} <strong class="text-gray-800 dark:text-gray-200">${dateStr}</strong> {{ __('by') }} <strong class="text-gray-800 dark:text-gray-200">${userName}</strong></span>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative">
                    <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-full items-center justify-center text-gray-400 dark:text-gray-500 shadow-md">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="flex flex-col h-full rounded-2xl overflow-hidden shadow-sm border border-red-200 dark:border-red-800/60 group">
                        <div class="flex items-center justify-between bg-red-100 dark:bg-red-900/40 px-5 py-3 border-b border-red-200 dark:border-red-800/60">
                            <span class="font-bold text-red-800 dark:text-red-400 text-sm flex items-center gap-2">
                                <i class="fas fa-minus-circle"></i> {{ __('old_data') }}
                            </span>
                        </div>
                        <div class="bg-red-50/50 dark:bg-red-900/10 p-5 text-sm font-mono text-gray-800 dark:text-gray-300 flex-1 overflow-x-auto">
                            <div class="space-y-1">${oldHtml}</div>
                        </div>
                    </div>
                    <div class="flex flex-col h-full rounded-2xl overflow-hidden shadow-sm border border-emerald-200 dark:border-emerald-800/60 group mt-4 lg:mt-0">
                        <div class="flex items-center justify-between bg-emerald-100 dark:bg-emerald-900/40 px-5 py-3 border-b border-emerald-200 dark:border-emerald-800/60">
                            <span class="font-bold text-emerald-800 dark:text-emerald-400 text-sm flex items-center gap-2">
                                <i class="fas fa-plus-circle"></i> {{ __('new_data') }}
                            </span>
                        </div>
                        <div class="bg-emerald-50/50 dark:bg-emerald-900/10 p-5 text-sm font-mono text-gray-800 dark:text-gray-300 flex-1 overflow-x-auto">
                            <div class="space-y-1">${newHtml}</div>
                        </div>
                    </div>
                </div>
            `;

            // Tampilkan Modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeDiffModal() {
            const modal = document.getElementById('diffModal');
            const modalContent = document.getElementById('diffModalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modal on click outside (backdrop)
        document.getElementById('diffModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDiffModal();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const modal = document.getElementById('diffModal');
                if (!modal.classList.contains('hidden')) {
                    closeDiffModal();
                }
            }
        });
    </script>

</x-app-layout>
