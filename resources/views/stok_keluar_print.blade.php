<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota - {{ $transaction->no_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .mono { font-family: 'Inconsolata', monospace; }
        .dashed { border-top: 1px dashed #9ca3af; margin: 8px 0; }
        .solid { border-top: 1.5px solid #111827; margin: 8px 0; }

        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important; 
                padding: 0 !important; 
                margin: 0 !important;
                width: 100% !important; /* Biarkan driver printer yang menentukan lebar aslinya */
                font-size: 10px !important;
            }
            
            /* Penyesuaian font responsif untuk receipt */
            .text-2xl { font-size: 14px !important; }
            .text-xl { font-size: 12px !important; }
            .text-lg { font-size: 11px !important; }
            .text-sm { font-size: 9px !important; }
            .text-xs { font-size: 8px !important; }
            
            /* Penyesuaian padding bawaan Tailwind */
            .p-8 { padding: 4mm !important; }
            .px-8 { padding-left: 4mm !important; padding-right: 4mm !important; }
            .py-8 { padding-top: 4mm !important; padding-bottom: 4mm !important; }
            .gap-6 { gap: 3mm !important; }
            .mb-6 { margin-bottom: 3mm !important; }
            .mt-8 { margin-top: 0 !important; }

            .max-w-md { max-width: 100% !important; }
            .receipt-card { 
                box-shadow: none !important; 
                border: none !important; 
                margin: 0 auto !important; 
                padding: 3mm !important; 
                max-width: 300px !important; /* Maksimal kira-kira selebar 80mm */
                width: 100% !important; /* Fluid, mengikuti driver printer (A4/80mm/58mm) */
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- TOP BAR --}}
    <div class="no-print bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-gray-800 text-lg">Transaksi Berhasil!</h1>
                    <p class="text-xs text-gray-500">{{ $transaction->no_transaksi }} — {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-[#D00000] hover:bg-red-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i> Cetak Nota
                </button>
                <a href="{{ route('stok_keluar') }}" class="bg-white border border-gray-300 text-gray-700 font-bold px-6 py-2.5 rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- RECEIPT --}}
    <div class="max-w-md mx-auto mt-8 mb-16 px-4">
        <div class="receipt-card bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-8">

                {{-- Header Toko --}}
                <div class="text-center mb-6">
                    <h2 class="font-extrabold text-xl uppercase tracking-wider text-gray-900">{{ $toko->nama_toko ?? 'MITRA USAHA' }}</h2>
                    <p class="text-xs text-gray-500 mt-1">{{ $toko->alamat ?? '-' }}</p>
                    <p class="text-xs text-gray-500">Telp: {{ $toko->telepon ?? '-' }}</p>
                </div>

                <div class="dashed"></div>

                {{-- Info Transaksi --}}
                <div class="grid grid-cols-2 gap-y-1.5 text-sm mb-4">
                    <div class="text-gray-500 font-medium">No. Transaksi</div>
                    <div class="text-right font-bold mono text-gray-900">{{ $transaction->no_transaksi }}</div>

                    <div class="text-gray-500 font-medium">Tanggal</div>
                    <div class="text-right font-semibold text-gray-800">{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}</div>

                    <div class="text-gray-500 font-medium">Kasir</div>
                    <div class="text-right font-semibold text-gray-800">{{ $transaction->user->name ?? 'Kasir' }}</div>
                </div>

                {{-- Info Tujuan --}}
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mb-4">
                    <div class="grid grid-cols-[80px_1fr] gap-y-1.5 text-sm">
                        <div class="text-gray-500 font-medium">Kategori</div>
                        <div class="font-semibold text-gray-800">{{ $transaction->kategori_keluar ?? '-' }}</div>

                        <div class="text-gray-500 font-medium">Tujuan</div>
                        <div class="font-bold text-gray-900">{{ $transaction->tujuan ?? '-' }}</div>

                        @if($transaction->no_referensi)
                        <div class="text-gray-500 font-medium">Referensi</div>
                        <div class="font-semibold text-gray-800">{{ $transaction->no_referensi }}</div>
                        @endif

                        @if($transaction->catatan)
                        <div class="text-gray-500 font-medium">Catatan</div>
                        <div class="italic text-gray-700">{{ $transaction->catatan }}</div>
                        @endif
                    </div>
                </div>

                <div class="solid"></div>

                {{-- Daftar Barang --}}
                <div class="mb-4">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-gray-500 mb-3">Daftar Barang</h3>
                    @foreach($transaction->items as $item)
                    <div class="mb-3">
                        <div class="text-sm font-semibold text-gray-900 leading-tight">{{ $item->product->nama_barang ?? 'Barang' }}</div>
                        <div class="flex justify-between items-end text-sm mt-0.5">
                            <span class="text-gray-500 mono">{{ $item->qty }} {{ $item->product->satuan ?? 'Pcs' }} × {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                            <span class="font-bold mono text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="solid"></div>

                {{-- Total --}}
                <div class="space-y-2 mb-6">
                    @php
                        $subtotal = $transaction->items->sum('subtotal');
                        $diskon = $transaction->diskon;
                    @endphp

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="mono font-semibold">{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    @if($diskon > 0)
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Diskon</span>
                        <span class="mono">-{{ number_format($diskon, 0, ',', '.') }}</span>
                    </div>
                    @elseif($diskon < 0)
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Biaya Tambahan / Ongkir</span>
                        <span class="mono">+{{ number_format(abs($diskon), 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="dashed"></div>

                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-lg text-gray-900">TOTAL</span>
                        <span class="font-extrabold text-xl mono text-[#D00000]">Rp {{ number_format($transaction->total_nilai, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="text-center text-xs text-gray-400 pt-4 border-t border-gray-100">
                    <p>Terima kasih atas kepercayaan Anda.</p>
                    <p class="mt-0.5">Nota ini merupakan bukti pengeluaran sah dari sistem.</p>
                </div>
            </div>
        </div>

        {{-- Bottom Buttons (Mobile Friendly) --}}
        <div class="no-print mt-6 flex gap-3">
            <button onclick="window.print()" class="flex-1 bg-[#D00000] hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 text-sm">
                <i class="fas fa-print"></i> Cetak Nota
            </button>
            <a href="{{ route('stok_keluar') }}" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2 text-sm">
                <i class="fas fa-arrow-left"></i> Transaksi Baru
            </a>
        </div>
    </div>

</body>
</html>
