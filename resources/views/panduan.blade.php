<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom Scrollbar for inner content */
        .premium-scroll::-webkit-scrollbar { width: 6px; }
        .premium-scroll::-webkit-scrollbar-track { background: transparent; }
        .premium-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .premium-scroll::-webkit-scrollbar-thumb { background: #475569; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .dark .glass-panel {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .step-line::before {
            content: '';
            position: absolute;
            left: 23px;
            top: 48px;
            bottom: -20px;
            width: 2px;
            background: #e2e8f0;
        }
        .dark .step-line::before { background: #334155; }
        .step-item:last-child .step-line::before { display: none; }
    </style>
    
    <div class="flex h-screen bg-slate-50 dark:bg-slate-950 overflow-hidden font-sans text-slate-800 dark:text-slate-100 selection:bg-indigo-500/30">
        
        @include('layouts.sidebar')
        
        <div id="overlay" class="fixed inset-0 bg-slate-900/60 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>
        
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <!-- Background Decorations -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
            
            @include('layouts.header')
            
            <div class="flex-1 overflow-hidden flex flex-col z-10 relative">
                
                {{-- PREMIUM HERO HEADER --}}
                <div class="px-4 lg:px-6 pt-4 lg:pt-6 pb-2 shrink-0">
                    <div class="bg-slate-900 rounded-3xl overflow-hidden relative shadow-2xl shadow-indigo-900/20 border border-slate-800">
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-600 rounded-full blur-[80px] opacity-50"></div>
                        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-600 rounded-full blur-[80px] opacity-30"></div>
                        
                        <div class="relative z-10 px-6 py-5 md:px-8 md:py-6 flex flex-col md:flex-row items-center gap-5 md:gap-6">
                            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30 border border-white/10 shrink-0 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                                <i class="fas fa-book-reader text-2xl md:text-3xl"></i>
                            </div>
                            <div class="text-center md:text-left">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-indigo-300 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1.5 backdrop-blur-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                                    Knowledge Base 2.0
                                </div>
                                <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-1">Pusat Bantuan & Panduan Sistem</h1>
                                <p class="text-slate-400 max-w-3xl text-xs md:text-sm leading-relaxed">Jelajahi arsitektur *Enterprise Resource Planning* kami. Pelajari secara mendalam alur kerja, logika kecerdasan buatan (AI), dan manajemen rantai pasok dengan antarmuka pembelajaran interaktif.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WORKSPACE AREA --}}
                <div class="flex-1 flex flex-col lg:flex-row overflow-hidden p-4 lg:p-6 gap-6 pt-4">
                    
                    {{-- SIDEBAR NAVIGATION (LEFT) --}}
                    <div class="w-full lg:w-80 flex flex-col glass-panel rounded-3xl shadow-sm overflow-hidden shrink-0 h-[40vh] lg:h-full relative z-20">
                        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700/50 bg-white/50 dark:bg-slate-800/50">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                <i class="fas fa-layer-group text-indigo-500"></i> Katalog Modul
                            </h3>
                        </div>
                        <div class="flex-1 overflow-y-auto premium-scroll p-3 space-y-2" id="nav-container">
                            <!-- JS Injected Nav -->
                        </div>
                    </div>

                    {{-- CONTENT AREA (RIGHT) --}}
                    <div class="flex-1 glass-panel rounded-3xl shadow-sm overflow-hidden relative z-10 h-full flex flex-col">
                        <!-- Scrollable inner container -->
                        <div class="overflow-y-auto premium-scroll w-full h-full" id="content-container">
                            <div id="content-area" class="w-full p-6 lg:p-8 xl:p-10 transition-all duration-300 transform opacity-0 translate-y-4">
                                <!-- JS Injected Content -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- PANDUAN DATA & SCRIPT -->
    <script>
        const panduanData = [
            {
                id: 'intro',
                icon: 'fa-chess-knight',
                colorClasses: {
                    active: 'bg-indigo-500 text-white shadow-md shadow-indigo-500/20 border-indigo-400',
                    iconActive: 'text-white',
                    badge: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 border-indigo-200 dark:border-indigo-500/30'
                },
                title: 'Arsitektur & Hierarki',
                desc: 'Role pengguna dan Single Source of Truth.',
                content: `
                    <div class="mb-8">
                        <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-4 border \${badgeColor}">Bagian 1: Pengantar</div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Memahami Pondasi Sistem</h2>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">Sistem ini bukan sekadar aplikasi pencatatan, melainkan sebuah <b>Enterprise Resource Planning (ERP)</b> skala menengah yang menghubungkan pengadaan barang, gudang, hingga kasir (POS) dalam satu ekosistem waktu nyata (<i>Real-time</i>).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <div class="bg-slate-900 dark:bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
                            <div class="relative z-10 w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl mb-5 border border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.5)] group-hover:scale-105 transition-transform"><i class="fas fa-network-wired"></i></div>
                            <h3 class="relative z-10 text-lg font-bold text-white mb-2">Single Source of Truth (SSOT)</h3>
                            <p class="relative z-10 text-sm text-slate-300 leading-relaxed">Semua divisi melihat data yang sama. Jika kasir menjual barang, detik itu juga stok di layar staf gudang dan laporan keuangan Owner akan diperbarui. Tidak ada lagi selisih data akibat keterlambatan sinkronisasi.</p>
                        </div>
                        <div class="bg-slate-900 dark:bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
                            <div class="relative z-10 w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-5 border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.5)] group-hover:scale-105 transition-transform"><i class="fas fa-user-shield"></i></div>
                            <h3 class="relative z-10 text-lg font-bold text-white mb-2">Hierarki Akses Berjenjang</h3>
                            <p class="relative z-10 text-sm text-slate-300 leading-relaxed">Sistem memisahkan tugas secara ketat (<i>Separation of Duties</i>). Staf Gudang tidak bisa melihat harga modal, Kasir tidak bisa mengubah stok manual, dan hak hapus data (Hapus Permanen) dikunci eksklusif untuk <b>Owner</b>.</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 text-lg"><i class="fas fa-lightbulb text-amber-500"></i> Alur Kerja Makro (Siklus Hidup Barang)</h4>
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center gap-5 bg-white dark:bg-slate-800 p-4 md:p-5 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 dark:text-slate-400 text-sm shrink-0">1</div>
                                <div>
                                    <p class="font-bold text-base text-slate-800 dark:text-slate-200 mb-1">Perencanaan Pemasok (PO)</p>
                                    <p class="text-xs text-slate-500">Dokumen niat beli untuk menghindari pesanan barang fiktif.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5 bg-white dark:bg-slate-800 p-4 md:p-5 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 dark:text-slate-400 text-sm shrink-0">2</div>
                                <div>
                                    <p class="font-bold text-base text-slate-800 dark:text-slate-200 mb-1">Penerimaan Barang (Inbound)</p>
                                    <p class="text-xs text-slate-500">Barang tiba dari supplier, stok komputer ditambah secara sah.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5 bg-white dark:bg-slate-800 p-4 md:p-5 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 dark:text-slate-400 text-sm shrink-0">3</div>
                                <div>
                                    <p class="font-bold text-base text-slate-800 dark:text-slate-200 mb-1">Penjualan via Kasir (Outbound)</p>
                                    <p class="text-xs text-slate-500">Pelanggan membeli barang, stok dikurangi dan struk dicetak.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                id: 'master',
                icon: 'fa-cubes',
                colorClasses: {
                    active: 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 border-emerald-400',
                    iconActive: 'text-white',
                    badge: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30'
                },
                title: 'Master Data & Pengaturan',
                desc: 'Manajemen katalog, ROP, dan Kustomisasi Toko.',
                content: `
                    <div class="mb-8">
                        <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-4 border \${badgeColor}">Bagian 2: Persiapan Transaksi</div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Setup Master Data</h2>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">Keakuratan operasional sangat bergantung pada bagaimana Anda mengelola <b>Katalog Persediaan</b>. Kesalahan kecil dalam pendaftaran data referensi dapat mengacaukan riwayat transaksi.</p>
                    </div>

                    <div class="space-y-10 pl-2">
                        <!-- Step 1 -->
                        <div class="flex gap-6 md:gap-8 step-item relative">
                            <div class="step-line relative z-10 shrink-0">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-slate-800 border-4 border-white dark:border-slate-900 ring-2 ring-emerald-500 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black shadow-lg shadow-emerald-500/20 text-lg">1</div>
                            </div>
                            <div class="pt-1 pb-4">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Fondasi Referensi Dasar</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">Sistem menolak penyimpanan barang tanpa klasifikasi. Anda wajib membuat <span class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md font-semibold text-slate-800 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-700">Kategori</span> (Contoh: Alat Listrik), <span class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md font-semibold text-slate-800 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-700">Satuan</span> (Contoh: Pcs, Kg), dan <span class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md font-semibold text-slate-800 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-700">Data Supplier</span> (vendor distributor Anda).</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex gap-6 md:gap-8 step-item relative">
                            <div class="step-line relative z-10 shrink-0">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-slate-800 border-4 border-white dark:border-slate-900 ring-2 ring-emerald-500 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black shadow-lg shadow-emerald-500/20 text-lg">2</div>
                            </div>
                            <div class="pt-1 pb-4 w-full">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Logika Katalog Persediaan</h3>
                                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mt-4 shadow-sm">
                                    <ul class="space-y-6">
                                        <li class="flex gap-5 items-start">
                                            <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-800">
                                                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">Batas Aman (ROP - Reorder Point)</h4>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Ini bukan sekadar angka mati. Jika sisa stok di sistem sama dengan atau di bawah nilai ROP ini, sistem pengawas otomatis akan membunyikan peringatan merah di halaman Dashboard dan menembakkan Notifikasi Email ke Owner.</p>
                                            </div>
                                        </li>
                                        <hr class="border-slate-100 dark:border-slate-700/50">
                                        <li class="flex gap-5 items-start">
                                            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-800">
                                                <i class="fas fa-money-bill-wave text-emerald-500"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">HPP (Harga Modal) Otomatis</h4>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Saat membuat barang pertama kali, isi Harga Modal. Menariknya, sistem ini akan me-<i>replace</i> otomatis nilai Harga Modal tersebut ketika Anda menerima kedatangan barang baru (di Inbound) dengan harga kulakan yang berbeda dari Supplier.</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex gap-6 md:gap-8 step-item relative">
                            <div class="step-line relative z-10 shrink-0">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-slate-800 border-4 border-white dark:border-slate-900 ring-2 ring-emerald-500 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black shadow-lg shadow-emerald-500/20 text-lg">3</div>
                            </div>
                            <div class="pt-1">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Panel Pengaturan (Visual Toko)</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">Terletak di modul <b>Pengaturan</b>. Owner dapat menyuntikkan <i>Branding</i> aplikasi. Atur Logo Kop Surat untuk Printer Kasir, ubah Nama Toko di Sidebar, dan yang terbaru: Anda bisa merancang desain tampilan <b>Email Peringatan Dinamis</b> (memilih warna korporat dan teks *header* kustom).</p>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                id: 'po',
                icon: 'fa-file-invoice',
                colorClasses: {
                    active: 'bg-purple-500 text-white shadow-md shadow-purple-500/20 border-purple-400',
                    iconActive: 'text-white',
                    badge: 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300 border-purple-200 dark:border-purple-500/30'
                },
                title: 'Purchase Order (PO)',
                desc: 'Alur persetujuan pemesanan stok ke supplier.',
                content: `
                    <div class="mb-8">
                        <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-4 border \${badgeColor}">Bagian 3: Pengadaan</div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Manajemen Purchase Order</h2>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">Purchase Order (PO) berfungsi sebagai dokumen legal niat beli yang mencegah pengadaan barang curang di belakang layar. Sistem kami mengunci *workflow* ini dengan State Machine (Alur Persetujuan) yang tidak bisa dilewati.</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800 mb-8 overflow-hidden relative">
                        <h3 class="font-black text-slate-900 dark:text-white mb-6 text-xl tracking-tight">State Machine Approval</h3>
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0 relative z-10">
                            
                            <!-- State 1 -->
                            <div class="text-center w-full md:w-1/3 flex flex-col items-center">
                                <div class="w-16 h-16 bg-white dark:bg-slate-800 border-2 border-amber-400 rounded-full flex items-center justify-center mb-3 shadow-lg shadow-amber-500/20 z-10">
                                    <i class="fas fa-file-signature text-amber-500 text-xl"></i>
                                </div>
                                <span class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 px-3 py-1 rounded-full text-xs font-bold tracking-wider mb-2 uppercase">1. Draft (Pending)</span>
                                <p class="text-sm text-slate-500 px-2 leading-relaxed">Staf membuat daftar kebutuhan barang.</p>
                            </div>
                            
                            <!-- Connector -->
                            <div class="h-8 md:h-1 w-1 md:w-full bg-slate-200 dark:bg-slate-700 -mt-2 md:mt-0 md:-ml-8 md:-mr-8 z-0"></div>
                            
                            <!-- State 2 -->
                            <div class="text-center w-full md:w-1/3 flex flex-col items-center">
                                <div class="w-16 h-16 bg-white dark:bg-slate-800 border-2 border-purple-500 rounded-full flex items-center justify-center mb-3 shadow-lg shadow-purple-500/20 z-10 relative">
                                    <div class="absolute -right-2 -top-2 w-6 h-6 bg-rose-500 rounded-full text-white flex items-center justify-center text-[10px] animate-bounce"><i class="fas fa-lock"></i></div>
                                    <i class="fas fa-check-double text-purple-500 text-xl"></i>
                                </div>
                                <span class="bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400 px-3 py-1 rounded-full text-xs font-bold tracking-wider mb-2 uppercase">2. Approved</span>
                                <p class="text-sm text-slate-500 px-2 leading-relaxed">Owner memvalidasi dokumen untuk sah dicetak.</p>
                            </div>
                            
                            <!-- Connector -->
                            <div class="h-8 md:h-1 w-1 md:w-full bg-slate-200 dark:bg-slate-700 -mt-2 md:mt-0 md:-ml-8 md:-mr-8 z-0"></div>
                            
                            <!-- State 3 -->
                            <div class="text-center w-full md:w-1/3 flex flex-col items-center">
                                <div class="w-16 h-16 bg-white dark:bg-slate-800 border-2 border-emerald-500 rounded-full flex items-center justify-center mb-3 shadow-lg shadow-emerald-500/20 z-10">
                                    <i class="fas fa-box-open text-emerald-500 text-xl"></i>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-bold tracking-wider mb-2 uppercase">3. Selesai</span>
                                <p class="text-sm text-slate-500 px-2 leading-relaxed">Berubah otomatis saat truk masuk gudang.</p>
                            </div>

                        </div>
                    </div>

                    <div class="bg-slate-900 rounded-2xl p-6 shadow-xl text-slate-300 border border-slate-700 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="flex items-center gap-3 mb-4 relative z-10">
                            <i class="fas fa-shield-virus text-purple-400 text-xl"></i>
                            <h3 class="font-bold text-white tracking-wide text-lg">Proteksi: Anti-Double Order</h3>
                        </div>
                        <p class="text-sm leading-relaxed relative z-10">
                            Masalah klasik di dunia retail adalah memesan barang yang ternyata "sudah pernah dipesan kemarin tapi truknya belum datang". Kami telah menyematkan algoritma filter. Saat Anda menambah produk ke Cart PO, sistem akan memblokir (*hide*) katalog barang yang statusnya masih <code class="bg-slate-800 px-1.5 py-0.5 rounded text-purple-300 border border-slate-700">Pending</code> atau <code class="bg-slate-800 px-1.5 py-0.5 rounded text-purple-300 border border-slate-700">Approved</code> di dokumen PO lain yang belum selesai. Menghemat *Cash Flow* Anda.
                        </p>
                    </div>
                `
            },
            {
                id: 'inbound_outbound',
                icon: 'fa-barcode',
                colorClasses: {
                    active: 'bg-teal-500 text-white shadow-md shadow-teal-500/20 border-teal-400',
                    iconActive: 'text-white',
                    badge: 'bg-teal-100 text-teal-700 dark:bg-teal-500/20 dark:text-teal-300 border-teal-200 dark:border-teal-500/30'
                },
                title: 'Inbound & POS Kasir',
                desc: 'Siklus Barang Masuk dan Point of Sales.',
                content: `
                    <div class="mb-8">
                        <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-4 border \${badgeColor}">Bagian 4: Transaksi Operasional</div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Pusat Lalu Lintas Fisik</h2>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">Inilah jembatan di mana lembaran dokumen digital berubah menjadi objek fisik nyata (Truk Masuk) dan berujung di tangan konsumen (Kasir).</p>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-6 md:gap-8">
                        <!-- INBOUND -->
                        <div class="bg-slate-900 dark:bg-slate-900 rounded-3xl border border-slate-800 p-8 flex flex-col h-full relative overflow-hidden shadow-xl group">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl group-hover:bg-teal-500/20 transition-all"></div>
                            <div class="absolute -top-10 -right-10 p-4 opacity-10 text-teal-500 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12"><i class="fas fa-truck-loading text-9xl"></i></div>
                            <div class="relative z-10">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-teal-500/20 text-teal-400 mb-6 border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.5)] group-hover:scale-105 transition-transform">
                                    <i class="fas fa-boxes text-2xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-3 tracking-tight">Penerimaan Gudang</h3>
                                <p class="text-sm text-slate-300 mb-6 leading-relaxed">Eksekusi surat jalan truk distributor masuk ke gerbang gudang.</p>
                                <ul class="space-y-4 text-sm text-slate-300">
                                    <li class="flex gap-3 items-start"><i class="fas fa-check-circle text-teal-400 mt-0.5 text-lg shrink-0"></i> <span>Tarik riwayat otomatis menggunakan <b class="text-white">Referensi No. PO</b> (Tanpa input manual dua kali).</span></li>
                                    <li class="flex gap-3 items-start"><i class="fas fa-check-circle text-teal-400 mt-0.5 text-lg shrink-0"></i> <span>Terdapat fitur <b class="text-white">Koreksi Kuantitas</b> jika barang fisik dari truk mengalami cacat/kurang.</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- OUTBOUND / POS -->
                        <div class="bg-slate-900 dark:bg-slate-900 rounded-3xl border border-slate-800 p-8 flex flex-col h-full relative overflow-hidden shadow-xl group">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl group-hover:bg-sky-500/20 transition-all"></div>
                            <div class="absolute -top-10 -right-10 p-4 opacity-10 text-sky-500 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12"><i class="fas fa-cash-register text-9xl"></i></div>
                            <div class="relative z-10">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-sky-500/20 text-sky-400 mb-6 border border-sky-500/30 shadow-[0_0_15px_rgba(14,165,233,0.5)] group-hover:scale-105 transition-transform">
                                    <i class="fas fa-shopping-cart text-2xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-3 tracking-tight">POS Kasir Terpadu</h3>
                                <p class="text-sm text-slate-300 mb-6 leading-relaxed">Antarmuka <i>Point of Sales</i> elegan yang dioptimalkan untuk kecepatan dan keamanan.</p>
                                <ul class="space-y-4 text-sm text-slate-300">
                                    <li class="flex gap-3 items-start"><i class="fas fa-check-circle text-sky-400 mt-0.5 text-lg shrink-0"></i> <span>Dukungan penuh untuk perangkat lunak <b class="text-white">Barcode Scanner</b> (*Plug & Play*).</span></li>
                                    <li class="flex gap-3 items-start"><i class="fas fa-check-circle text-sky-400 mt-0.5 text-lg shrink-0"></i> <span>Ditanamkan <b class="text-white">Safety Lock</b>: Mustahil memproses keranjang bila item melewati total sisa stok yang ada (Anti Minus).</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `
            },
            {
                id: 'ai_audit',
                icon: 'fa-brain',
                colorClasses: {
                    active: 'bg-rose-500 text-white shadow-md shadow-rose-500/20 border-rose-400',
                    iconActive: 'text-white',
                    badge: 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border-rose-200 dark:border-rose-500/30'
                },
                title: 'Audit & Kecerdasan Buatan',
                desc: 'Stock Opname, EOQ, dan Simulasi Monte Carlo.',
                content: `
                    <div class="mb-8">
                        <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-4 border \${badgeColor}">Bagian 5: Advanced Engineering</div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Manajemen Cerdas Berbasis Data</h2>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">Singkirkan asumsi dan emosi dalam manajemen. Modul ini adalah 'Otak Kiri' dari sistem kami yang didesain untuk menyelamatkan aset perusahaan dari kebangkrutan tersembunyi.</p>
                    </div>

                    <div class="space-y-5">
                        
                        <!-- Audit -->
                        <div class="bg-slate-900 dark:bg-slate-900 rounded-2xl border border-slate-800 p-6 md:p-8 shadow-xl group relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-all"></div>
                            <div class="flex flex-col md:flex-row md:items-start gap-6 relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30 text-2xl group-hover:scale-105 transition-transform shadow-[0_0_15px_rgba(245,158,11,0.5)]"><i class="fas fa-clipboard-check"></i></div>
                                <div>
                                    <h4 class="font-black text-white text-xl mb-2 tracking-tight">Stock Opname (Audit Fisik)</h4>
                                    <div class="bg-amber-500/20 text-amber-300 text-xs px-2 py-1 rounded inline-block mb-3 font-bold uppercase tracking-widest border border-amber-500/30">Keamanan Pintu Ganda</div>
                                    <p class="text-sm md:text-base text-slate-300 leading-relaxed">Secanggih apapun komputasi, barang akan selalu mengalami penyusutan fisik (rusak dimakan rayap, pecah, dicuri). Modul Audit menyeimbangkan dunia digital dan nyata. Staf menghitung angka fisik → Selisih terlihat jelas → <b class="text-white">Owner harus klik Setujui</b> sebelum angka komputer tertimpa secara permanen (mencegah korupsi karyawan).</p>
                                </div>
                            </div>
                        </div>

                        <!-- EOQ -->
                        <div class="bg-slate-900 dark:bg-slate-900 rounded-2xl border border-slate-800 p-6 md:p-8 shadow-xl group relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl group-hover:bg-rose-500/20 transition-all"></div>
                            <div class="flex flex-col md:flex-row md:items-start gap-6 relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30 text-2xl group-hover:scale-105 transition-transform shadow-[0_0_15px_rgba(244,63,94,0.5)]"><i class="fas fa-chart-area"></i></div>
                                <div>
                                    <h4 class="font-black text-white text-xl mb-2 tracking-tight">Economic Order Quantity (EOQ)</h4>
                                    <div class="bg-rose-500/20 text-rose-300 text-xs px-2 py-1 rounded inline-block mb-3 font-bold uppercase tracking-widest border border-rose-500/30">AI Pembelian</div>
                                    <p class="text-sm md:text-base text-slate-300 leading-relaxed">Terletak tersembunyi di dalam form Purchase Order. Daripada menduga-duga "mau beli berapa sak semen bulan ini?", AI EOQ akan menganalisis histori laku harian produk Anda, mengkalikan dengan biaya penyimpanan gudang, dan mengkalkulasi <b>Saran Jumlah Angka Paling Optimal</b> secara matematis (Cetak biru <i>Lean Management</i>).</p>
                                </div>
                            </div>
                        </div>

                        <!-- ROP -->
                        <div class="bg-slate-900 dark:bg-slate-900 rounded-2xl border border-slate-800 p-6 md:p-8 shadow-xl group relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all"></div>
                            <div class="flex flex-col md:flex-row md:items-start gap-6 relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/30 text-2xl group-hover:scale-105 transition-transform shadow-[0_0_15px_rgba(99,102,241,0.5)]"><i class="fas fa-bell"></i></div>
                                <div>
                                    <h4 class="font-black text-white text-xl mb-2 tracking-tight">Reorder Point (ROP)</h4>
                                    <div class="bg-indigo-500/20 text-indigo-300 text-xs px-2 py-1 rounded inline-block mb-3 font-bold uppercase tracking-widest border border-indigo-500/30">Sistem Peringatan Dini</div>
                                    <p class="text-sm md:text-base text-slate-300 leading-relaxed">Sistem tidak lagi membiarkan Anda kecolongan saat barang benar-benar habis. Algoritma ROP bekerja sebagai pengawas pasif 24 jam. Begitu persediaan suatu barang menyentuh titik batas aman, sistem akan otomatis membunyikan sinyal bahaya visual di Dashboard dan menembakkan Notifikasi Email, memberi Anda <b>jeda waktu krusial</b> untuk restock tepat waktu tanpa mengganggu penjualan.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                `
            },
            {
                id: 'utils',
                icon: 'fa-bolt',
                colorClasses: {
                    active: 'bg-sky-500 text-white shadow-md shadow-sky-500/20 border-sky-400',
                    iconActive: 'text-white',
                    badge: 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300 border-sky-200 dark:border-sky-500/30'
                },
                title: 'Utilitas Kolaborasi Internal',
                desc: 'Live Chat AJAX dan Email Engine Background.',
                content: `
                    <div class="mb-8">
                        <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-4 border \${badgeColor}">Bagian 6: Skalabilitas Komunikasi</div>
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Alat Kolaborasi Real-Time</h2>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg">Pusatkan seluruh komunikasi operasional Anda di dalam satu atap, terhindar dari platform eksternal yang rentan tercampur urusan personal.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <!-- Chat -->
                        <div class="bg-slate-900 dark:bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-xl relative overflow-hidden group">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl group-hover:bg-sky-500/20 transition-all"></div>
                            <div class="relative z-10 w-14 h-14 rounded-2xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-2xl mb-6 border border-sky-500/30 shadow-[0_0_15px_rgba(14,165,233,0.5)] group-hover:scale-110 transition-transform"><i class="fas fa-comment-dots"></i></div>
                            <h3 class="relative z-10 text-xl font-black text-white mb-3 tracking-tight">Live Chat Internal (AJAX)</h3>
                            <p class="relative z-10 text-sm text-slate-300 leading-relaxed mb-4">Tersemat di *top-bar* atas. Aplikasi perpesanan mini yang dibangun menggunakan teknologi sinkronisasi asinkron.</p>
                            <ul class="relative z-10 text-sm text-slate-300 space-y-2">
                                <li><i class="fas fa-angle-right text-sky-400 mr-2"></i><b class="text-white">Global Chat:</b> Ruang diskusi terbuka seluruh pegawai.</li>
                                <li><i class="fas fa-angle-right text-sky-400 mr-2"></i><b class="text-white">Private Chat:</b> Percakapan terenkripsi antar divisi secara rahasia tanpa me-refresh halaman.</li>
                            </ul>
                        </div>
                        
                        <!-- Email Cron -->
                        <div class="bg-slate-900 dark:bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-xl relative overflow-hidden group">
                            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all"></div>
                            <div class="relative z-10 w-14 h-14 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl mb-6 border border-indigo-500/30 shadow-[0_0_15px_rgba(99,102,241,0.5)] group-hover:scale-110 transition-transform"><i class="fas fa-paper-plane"></i></div>
                            <h3 class="relative z-10 text-xl font-black text-white mb-3 tracking-tight">Background Email Engine</h3>
                            <p class="relative z-10 text-sm text-slate-300 leading-relaxed mb-4">Sistem ini mempekerjakan asisten robot siluman (<i>Cron Jobs & Background Queues</i>) yang akan menembakkan email secara otomatis.</p>
                            <ul class="relative z-10 text-sm text-slate-300 space-y-2">
                                <li><i class="fas fa-angle-right text-indigo-400 mr-2"></i>Peringatan *Low Stock* ROP Instan.</li>
                                <li><i class="fas fa-angle-right text-indigo-400 mr-2"></i>Notifikasi validasi PO.</li>
                                <li><i class="fas fa-angle-right text-indigo-400 mr-2"></i>Desain UI Email diatur kustom 100% oleh Anda via menu Pengaturan.</li>
                            </ul>
                        </div>
                    </div>
                `
            }
        ];

        let activeId = 'intro';

        function renderNavigation() {
            const container = document.getElementById('nav-container');
            container.innerHTML = '';
            
            panduanData.forEach(item => {
                const isActive = item.id === activeId;
                
                // Active State Styling
                const activeWrapper = isActive 
                    ? item.colorClasses.active 
                    : 'bg-transparent text-slate-600 dark:text-slate-400 border-transparent hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:border-slate-200 dark:hover:border-slate-700';
                
                const iconColor = isActive ? item.colorClasses.iconActive : 'text-slate-400 group-hover:text-slate-500 dark:group-hover:text-slate-300';
                const weightClass = isActive ? 'font-black' : 'font-bold';

                const html = `
                    <button onclick="changeTopic('${item.id}')" class="group w-full text-left p-4 rounded-2xl border transition-all duration-300 flex items-center gap-4 ${activeWrapper} focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 dark:focus:ring-offset-slate-900">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center shrink-0 transition-colors">
                            <i class="fas ${item.icon} ${iconColor} text-xl transition-colors"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="${weightClass} text-sm md:text-base truncate">${item.title}</div>
                            <div class="text-xs mt-1 opacity-70 leading-tight truncate font-medium">${item.desc}</div>
                        </div>
                    </button>
                `;
                container.innerHTML += html;
            });
        }

        function renderContent() {
            const container = document.getElementById('content-area');
            const data = panduanData.find(i => i.id === activeId);
            
            // Replace badge string replacement logic correctly
            const processedContent = data.content.replace(/\$\{badgeColor\}/g, data.colorClasses.badge);
            
            // Exit animation
            container.classList.remove('opacity-100', 'translate-y-0');
            container.classList.add('opacity-0', 'translate-y-4');
            
            setTimeout(() => {
                container.innerHTML = processedContent;
                // Enter animation
                container.classList.remove('opacity-0', 'translate-y-4');
                container.classList.add('opacity-100', 'translate-y-0');
            }, 250); // Matches transition duration
        }

        function changeTopic(id) {
            if(activeId === id) return;
            activeId = id;
            renderNavigation();
            renderContent();
            
            // Mobile auto-scroll logic to content container wrapper
            if(window.innerWidth < 1024) {
                document.getElementById('content-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderNavigation();
            renderContent();
            setTimeout(() => {
                document.getElementById('content-area').classList.remove('opacity-0', 'translate-y-4');
                document.getElementById('content-area').classList.add('opacity-100', 'translate-y-0');
            }, 50);
        });
    </script>
</x-app-layout>
