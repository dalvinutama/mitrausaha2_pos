<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 20px 10px; margin: 0; -webkit-font-smoothing: antialiased; }
        .container { width: 100%; max-width: 500px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .header { background: {{ $emailSetting->header_color ?? '#D00000' }}; padding: 20px; border-radius: 8px 8px 0 0; }
        .header h1 { color: {{ $emailSetting->primary_color ?? '#ffffff' }}; margin: 0; font-size: 20px; letter-spacing: 1px; }
        .content { padding: 30px 20px; color: #374151; line-height: 1.6; }
        .icon { font-size: 40px; margin-bottom: 15px; }
        .title { color: #111827; margin: 0 0 10px 0; font-size: 18px; font-weight: bold; }
        .summary { background: #fee2e2; border: 1px solid #fca5a5; padding: 15px; border-radius: 6px; margin: 20px 0; color: #991b1b; font-weight: bold; font-size: 15px; }
        .desc { font-size: 14px; color: #4b5563; margin-bottom: 25px; }
        .btn { display: inline-block; background-color: {{ $emailSetting->primary_color ?? '#D00000' }}; color: white !important; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; font-size: 14px; }
        .footer { padding: 15px; font-size: 11px; color: #6b7280; border-top: 1px solid #f3f4f6; background-color: #fafafa; border-radius: 0 0 8px 8px; }
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
            <div class="icon">📑</div>
            <h2 class="title">{{ $emailSetting->sys_notif_title ?? 'Laporan Peringatan Sistem' }}</h2>
            
            <div class="summary">
                Terdapat {{ $stokMenipis->count() + $hutangTempo->count() }} Peringatan Darurat
            </div>
            
            <p class="desc">
                {{ $emailSetting->sys_notif_intro ?? '' }}<br><br>
                Sistem mendeteksi adanya <strong>{{ $stokMenipis->count() }} barang dengan stok menipis</strong> dan <strong>{{ $hutangTempo->count() }} hutang jatuh tempo</strong>.<br><br>
                Untuk rincian selengkapnya, silakan <strong>unduh lampiran dokumen PDF</strong> yang ada di email ini.
            </p>

            <a href="{{ url('/') }}" class="btn">Buka Aplikasi Web</a>
        </div>
        <div class="footer">
            <p>{{ $emailSetting->footer_text ?? 'Email Otomatis Sistem Manajemen (Header Notification Digest).' }}</p>
        </div>
    </div>
</body>
</html>
