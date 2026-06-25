<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak PO - {{ $po->no_transaksi }}</title>
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
            color: #D00000;
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
        table td.center, table th.center {
            text-align: center;
        }
        .total-row td {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 16px;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #fdfdfd;
            border-left: 4px solid #D00000;
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

<div class="invoice-box">
    <div class="header">
        @php
            $toko = \App\Models\StoreProfile::where('is_active', true)->first() ?? \App\Models\StoreProfile::first();
        @endphp
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
            <h2 style="font-weight: 900; color: #000;"><strong>PURCHASE ORDER</strong></h2>
            <p>{{ $po->no_transaksi }}</p>
        </div>
    </div>

    <div class="meta-info">
        <div class="supplier-info">
            <h4>Kepada (Supplier):</h4>
            <strong>{{ $po->supplier->nama_supplier ?? 'Unknown Supplier' }}</strong><br>
            @if(isset($po->supplier->alamat))
                {{ $po->supplier->alamat }}<br>
            @endif
            @if(isset($po->supplier->no_hp))
                Telp: {{ $po->supplier->no_hp }}
            @endif
        </div>
        <div class="po-details">
            <h4>Detail PO:</h4>
            Tanggal: <strong>{{ \Carbon\Carbon::parse($po->tanggal)->format('d F Y') }}</strong><br>
            Dibuat Oleh: <strong>{{ $po->user->name ?? 'Admin' }}</strong><br>
            Status: <strong>{{ strtoupper($po->status) }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang (SKU)</th>
                <th class="center">Qty</th>
                <th class="right">Harga Satuan</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->nama_barang ?? 'Item Dihapus' }}</strong><br>
                    <small style="color:#888">{{ $item->product->sku ?? '-' }}</small>
                </td>
                <td class="center">{{ $item->qty }} {{ $item->product->satuan ?? 'Pcs' }}</td>
                <td class="right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="right">GRAND TOTAL</td>
                <td class="right">Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($po->catatan)
    <div class="notes">
        <h4>Catatan Pesanan:</h4>
        <p style="margin:0">{{ $po->catatan }}</p>
    </div>
    @endif

    <div class="signatures">
        <div class="sig-box">
            <p>Dibuat Oleh,</p>
            <div class="sig-line">{{ $po->user->name ?? 'Admin Keuangan' }}</div>
        </div>
        <div class="sig-box">
            <p>Disetujui Oleh,</p>
            <div class="sig-line">Owner / Direktur</div>
        </div>
        <div class="sig-box">
            <p>Pihak Supplier,</p>
            <div class="sig-line">{{ $po->supplier->nama_pic ?? 'Penanggung Jawab' }}</div>
        </div>
    </div>
</div>

</body>
</html>
