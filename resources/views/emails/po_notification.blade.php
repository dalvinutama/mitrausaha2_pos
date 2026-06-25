<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 30px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: {{ $emailSetting->header_color ?? '#fef2f2' }}; padding: 25px 30px; text-align: center; }
        .header h1 { color: {{ $emailSetting->primary_color ?? '#ef4444' }}; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 30px; color: #374151; line-height: 1.6; }
        .content h2 { color: #111827; margin-top: 0; }
        .details-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .details-box p { margin: 8px 0; font-size: 15px; }
        .details-box strong { color: #111827; }
        .amount { font-size: 20px; font-weight: bold; color: {{ $emailSetting->primary_color ?? '#ef4444' }}; margin-top: 15px; border-top: 1px dashed #d1d5db; padding-top: 15px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; border-top: 1px solid #f3f4f6; }
        .btn { display: inline-block; background-color: {{ $emailSetting->primary_color ?? '#ef4444' }}; color: white !important; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php
                $logoEmail = ($emailSetting && $emailSetting->logo) ? $emailSetting->logo : 'logo-utama.png';
                $namaToko = ($emailSetting && $emailSetting->nama_toko) ? $emailSetting->nama_toko : config('aplikasi.nama_aplikasi', 'TB. MITRA USAHA 2');
            @endphp
            <img src="{{ asset('storage/logos/' . $logoEmail) }}" alt="Logo" style="max-height: 50px; margin-bottom: 10px;">
            <h1>{{ $namaToko }}</h1>
        </div>
        <div class="content">
            <h2>{{ $emailSetting->po_new_title ?? 'Pemberitahuan Purchase Order' }}</h2>
            <p>Halo,</p>
            <p>{{ $emailSetting->po_new_intro ?? 'Terdapat dokumen Purchase Order baru yang baru saja dicatat ke dalam sistem. Berikut adalah rincian transaksinya:' }}</p>
            
            <div class="details-box">
                <p><strong>No Transaksi:</strong> {{ $transaksi->no_transaksi }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') }}</p>
                <p><strong>Supplier:</strong> {{ $transaksi->supplier->nama_supplier ?? 'Tanpa Supplier' }}</p>
                <p><strong>Status:</strong> 
                    @if($transaksi->status == 'pending')
                        <span style="color: #ea580c; font-weight: bold;">Menunggu Persetujuan (Pending)</span>
                    @else
                        <span style="color: #16a34a; font-weight: bold;">Otomatis Disetujui (Approved)</span>
                    @endif
                </p>
                <p class="amount">Total Nilai: Rp {{ number_format($transaksi->total_nilai, 0, ',', '.') }}</p>
            </div>

            <p>{{ $emailSetting->po_new_outro ?? 'Silakan segera masuk ke dalam sistem aplikasi untuk meninjau lebih detail dan memproses transaksi ini jika diperlukan.' }}</p>
            
            <center>
                <a href="{{ url('/purchase-order') }}" class="btn">{{ $emailSetting->po_new_btn ?? 'Buka Aplikasi Sekarang' }}</a>
            </center>
        </div>
        <div class="footer">
            <p>{{ $emailSetting->footer_text ?? 'Ini adalah pesan otomatis yang dihasilkan oleh Sistem Manajemen TB Mitra Usaha 2. Harap tidak membalas email ini.' }}</p>
        </div>
    </div>
</body>
</html>
