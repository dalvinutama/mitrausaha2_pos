<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembayaran - {{ $payment->transaction->no_transaksi }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            font-size: 28px;
            color: #059669; /* Emerald 600 */
        }
        .company-info p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .document-title {
            text-align: right;
        }
        .document-title h2 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .document-title p {
            margin: 5px 0 0 0;
            font-weight: bold;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .supplier-info h4, .po-details h4 {
            margin: 0 0 5px 0;
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
        }
        .po-details {
            text-align: right;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        table th {
            background: #f8f8f8;
            border-bottom: 2px solid #ddd;
            padding: 10px;
            font-weight: bold;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        table td.right, table th.right {
            text-align: right;
        }
        .total-row td {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
        }
        .grand-total-row td {
            font-weight: 900;
            font-size: 16px;
            color: #059669;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #fdfdfd;
            border-left: 4px solid #059669;
        }
        .notes h4 {
            margin: 0 0 5px 0;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .sig-box {
            width: 200px;
        }
        .sig-line {
            margin-top: 70px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }
        @media print {
            body { padding: 0; }
            .invoice-box {
                box-shadow: none;
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

@php
    $toko = \App\Models\StoreProfile::where('is_active', true)->first() ?? \App\Models\StoreProfile::first();
    $transaksi = $payment->transaction;
    
    // Hitung total bayar sampai dengan pembayaran ini (berdasarkan ID)
    $sudah_bayar_sekarang = \App\Models\TransactionPayment::where('transaction_id', $transaksi->id)
                            ->where('id', '<=', $payment->id)
                            ->sum('nominal');
                            
    $sisa_hutang = $transaksi->total_nilai - $sudah_bayar_sekarang;
@endphp

<div class="invoice-box">
    <div class="header">
        <div class="company-info">
            <h1>{{ $toko ? $toko->nama_toko : config('aplikasi.nama_aplikasi', 'TbMitraUsaha') }}</h1>
            <p style="margin-bottom: 4px; font-weight: bold; color: #444;">{{ $toko ? $toko->tagline : 'Sistem Manajemen Persediaan Ritel' }}</p>
            @if($toko)
            <p style="font-size: 11px; margin-top: 0; line-height: 1.4;">
                {{ $toko->alamat }}<br>
                Telp: {{ $toko->telepon }} {{ $toko->email ? '| Email: '.$toko->email : '' }}
            </p>
            @endif
        </div>
        <div class="document-title">
            <h2 style="font-weight: 900; color: #000;"><strong>BUKTI PEMBAYARAN</strong></h2>
            <p>ID Bayar: #PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <div class="meta-info">
        <div class="supplier-info">
            <h4>Dibayarkan Kepada:</h4>
            <strong>{{ $transaksi->supplier->nama_supplier ?? 'Supplier Tidak Diketahui' }}</strong><br>
            @if(isset($transaksi->supplier->alamat))
                {{ $transaksi->supplier->alamat }}<br>
            @endif
            @if(isset($transaksi->supplier->no_hp))
                Telp: {{ $transaksi->supplier->no_hp }}
            @endif
        </div>
        <div class="po-details">
            <h4>Detail Transaksi Utama:</h4>
            No Nota: <strong>{{ $transaksi->no_transaksi }}</strong><br>
            Tanggal Masuk: <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</strong><br>
            Total Hutang Awal: <strong>Rp {{ number_format($transaksi->total_nilai, 0, ',', '.') }}</strong>
        </div>
    </div>

    <table style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th>Tanggal Bayar</th>
                <th>Metode</th>
                <th>Diterima Oleh Sistem</th>
                <th class="right">Nominal Dibayar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d F Y') }}</td>
                <td>{{ strtoupper($payment->metode_pembayaran) }}</td>
                <td>{{ $payment->user->name ?? 'Sistem' }}</td>
                <td class="right" style="font-weight: bold;">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 50%; margin-left: auto; border: none;">
        <tbody>
            <tr class="total-row">
                <td style="border: none; text-align: right; padding: 5px;">Total Tagihan :</td>
                <td class="right" style="border: none; padding: 5px;">Rp {{ number_format($transaksi->total_nilai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right; padding: 5px;">Total Terbayar (s/d saat ini) :</td>
                <td class="right" style="border: none; padding: 5px;">Rp {{ number_format($sudah_bayar_sekarang, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total-row">
                <td style="border: none; text-align: right; padding: 5px; border-top: 2px solid #ddd;">Sisa Tagihan :</td>
                <td class="right" style="border: none; padding: 5px; border-top: 2px solid #ddd;">
                    @if($sisa_hutang <= 0)
                        <span style="color: #059669;">LUNAS</span>
                    @else
                        Rp {{ number_format($sisa_hutang, 0, ',', '.') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="notes">
        <h4>Keterangan Tambahan:</h4>
        <p style="margin:0">
            Bukti pembayaran ini adalah sah dan dicetak secara otomatis dari Sistem {{ config('aplikasi.nama_aplikasi', 'MitraUsaha') }}. 
            @if($sisa_hutang <= 0)
                <br><strong>Status: Transaksi telah Lunas.</strong>
            @else
                <br>Status: Cicilan Pembayaran. Sisa tagihan belum lunas.
            @endif
        </p>
    </div>

    <div class="signatures">
        <div class="sig-box">
            <p>Diserahkan Oleh,</p>
            <div class="sig-line">{{ $payment->user->name ?? 'Admin / Kasir' }}</div>
        </div>
        <div class="sig-box">
            <p>Diterima Oleh,</p>
            <div class="sig-line">{{ $transaksi->supplier->nama_pic ?? 'Pihak Supplier' }}</div>
        </div>
    </div>
</div>

</body>
</html>
