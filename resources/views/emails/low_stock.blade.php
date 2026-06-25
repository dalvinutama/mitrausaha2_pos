<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 6px solid {{ $emailSetting->primary_color ?? '#ef4444' }}; }
        .header { background: {{ $emailSetting->header_color ?? '#fef2f2' }}; padding: 25px 30px; text-align: center; }
        .header h1 { color: {{ $emailSetting->primary_color ?? '#ef4444' }}; margin: 0; font-size: 22px; }
        .content { padding: 30px; color: #374151; line-height: 1.6; }
        .details-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .details-box p { margin: 8px 0; font-size: 15px; }
        .details-box strong { color: #111827; }
        .amount { font-size: 24px; font-weight: bold; color: {{ $emailSetting->primary_color ?? '#ef4444' }}; margin-top: 15px; border-top: 1px dashed #d1d5db; padding-top: 15px; text-align: center;}
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; border-top: 1px solid #f3f4f6; }
        .btn { display: inline-block; background-color: {{ $emailSetting->primary_color ?? '#ef4444' }}; color: white !important; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php
                $logoEmail = ($emailSetting && $emailSetting->logo) ? $emailSetting->logo : 'logo-utama.png';
            @endphp
            <img src="{{ asset('storage/logos/' . $logoEmail) }}" alt="Logo" style="max-height: 50px; margin-bottom: 10px;">
            <h1>{{ $emailSetting->low_stock_title ?? '🚨 PERINGATAN STOK KRITIS' }}</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>{{ $emailSetting->low_stock_intro ?? 'Sistem mendeteksi bahwa stok salah satu barang di gudang baru saja menyentuh atau berada di bawah Batas Aman (Reorder Point) akibat adanya transaksi barang keluar hari ini.' }}</p>
            
            <div class="details-box">
                <p><strong>Kode Barang:</strong> {{ $product->kode_barang }}</p>
                <p><strong>Nama Barang:</strong> {{ $product->nama_barang }}</p>
                <p><strong>Batas Aman (ROP):</strong> {{ $product->reorder_point }} {{ $product->satuan->nama_satuan ?? 'pcs' }}</p>
                
                <div class="amount">Sisa Stok: {{ $product->stok }}</div>
            </div>

            <p>{{ $emailSetting->low_stock_outro ?? 'Mohon segera lakukan Purchase Order (PO) kepada Supplier untuk menghindari kekosongan barang yang dapat mengganggu operasional toko.' }}</p>
            
            <center>
                <a href="{{ url('/purchase-order') }}" class="btn">{{ $emailSetting->low_stock_btn ?? 'Buat PO Sekarang' }}</a>
            </center>
        </div>
        <div class="footer">
            <p>{{ $emailSetting->footer_text ?? 'Ini adalah pesan otomatis yang dihasilkan oleh Sistem Manajemen TB Mitra Usaha 2. Harap tidak membalas email ini.' }}</p>
            <p>Notifikasi untuk barang ini telah dibisukan (Cooldown) selama 24 jam ke depan.</p>
        </div>
    </div>
</body>
</html>
