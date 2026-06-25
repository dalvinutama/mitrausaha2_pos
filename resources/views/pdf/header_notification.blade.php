<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peringatan Sistem</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
        .header { background-color: #D00000; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #fecaca; }
        
        .content { padding: 30px; }
        .alert-box { background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 25px; }
        .alert-box p { margin: 0; color: #991b1b; font-size: 14px; font-weight: bold; }
        
        .section-title { font-size: 16px; font-weight: bold; color: #b91c1c; margin-top: 30px; margin-bottom: 10px; border-bottom: 2px solid #fecaca; padding-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8fafc; color: #475569; font-size: 11px; text-transform: uppercase; padding: 10px; text-align: left; border: 1px solid #e2e8f0; }
        td { padding: 10px; border: 1px solid #e2e8f0; font-size: 12px; }
        
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 50px; text-align: center; font-size: 10px; color: #64748b; }
        
        /* Utility */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-red { color: #ef4444; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TB. MITRA USAHA 2</h1>
        <p>Laporan Peringatan Sistem (Header Notifications)</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Manajemen TB. Mitra Usaha 2
    </div>

    <div class="content">
        <div class="alert-box">
            <p>Terdapat {{ $stokMenipis->count() + $hutangTempo->count() }} Peringatan Darurat yang membutuhkan perhatian Anda segera.</p>
        </div>

        @if($stokMenipis->count() > 0)
            <div class="section-title">⚠️ PERINGATAN STOK MENIPIS ({{ $stokMenipis->count() }} ITEM)</div>
            <table>
                <thead>
                    <tr>
                        <th width="15%">Kode Barang</th>
                        <th width="45%">Nama Barang</th>
                        <th width="20%" class="text-center">Sisa Stok</th>
                        <th width="20%" class="text-center">Batas Aman (ROP)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stokMenipis as $item)
                    <tr>
                        <td>{{ $item->sku }}</td>
                        <td class="font-bold">{{ $item->nama_barang }}</td>
                        <td class="text-center text-red font-bold">{{ $item->stok }} {{ $item->satuan }}</td>
                        <td class="text-center">{{ $item->reorder_point }} {{ $item->satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($hutangTempo->count() > 0)
            <div class="section-title">🚨 HUTANG JATUH TEMPO ({{ $hutangTempo->count() }} TAGIHAN)</div>
            <table>
                <thead>
                    <tr>
                        <th width="20%">No. Transaksi</th>
                        <th width="35%">Supplier</th>
                        <th width="20%">Jatuh Tempo</th>
                        <th width="25%" class="text-right">Sisa Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hutangTempo as $hutang)
                    <tr>
                        <td class="font-bold">{{ $hutang->no_transaksi }}</td>
                        <td>{{ $hutang->supplier->nama_supplier ?? '-' }}</td>
                        <td class="text-red font-bold">
                            {{ \Carbon\Carbon::parse($hutang->tanggal_tempo)->format('d/m/Y') }}
                        </td>
                        <td class="text-right font-bold">
                            Rp {{ number_format($hutang->total_nilai - $hutang->jumlah_dibayar, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
