<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisa Stok - {{ $profile->nama_toko ?: 'Mitra Usaha 2' }}</title>
    <style>
        /* CSS STANDAR DOMPDF */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* --- HEADER KOP SURAT --- */
        .header-container {
            width: 100%;
            border: none;
        }
        .header-left {
            width: 65%;
            vertical-align: top;
        }
        .header-right {
            width: 35%;
            vertical-align: top;
            text-align: right;
        }

        .company-info-table {
            border: none;
            width: 100%;
        }
        .company-info-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        
        /* HANYA INI YANG DIUBAH: LOGO DIBUAT BULAT */
        .logo-toko {
            width: 75px;
            height: 75px; /* Tinggi disamakan dengan lebar agar bulat sempurna */
            border-radius: 50%; /* Membuat efek lingkaran */
            object-fit: cover; /* Mencegah gambar gepeng */
            border: 1px solid #ccc; /* Sedikit garis tepi agar lebih rapi */
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }
        .company-slogan {
            font-size: 11px;
            font-style: italic;
            color: #444;
            margin: 0 0 6px 0;
        }
        .company-address {
            margin: 0 0 3px 0;
            font-size: 10px;
            line-height: 1.3;
        }

        /* --- KOTAK INFO KANAN --- */
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }
        
        .meta-box {
            width: 160px;
            border-collapse: collapse;
        }
        .meta-box th, .meta-box td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        .meta-box th {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        /* --- GARIS PEMISAH KOP SURAT --- */
        .garis-kop {
            border-bottom: 2px solid #000;
            margin-top: 10px;
            margin-bottom: 15px;
            width: 100%;
        }

        /* --- TABEL DATA UTAMA --- */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            margin-top: 10px;
        }
        .tabel-data th, .tabel-data td {
            border: 1px solid #000;
            padding: 6px 4px;
        }
        .tabel-data th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* --- TANDA TANGAN --- */
        .ttd-container {
            width: 100%;
            page-break-inside: avoid;
            margin-top: 30px;
        }
        .ttd-table {
            width: 100%;
            border: none;
        }
        .ttd-table td {
            border: none;
            text-align: center;
            width: 33%;
            vertical-align: bottom;
            font-size: 11px;
        }
        .ttd-space {
            height: 70px;
        }
    </style>
</head>
<body>

    <table class="header-container">
        <tr>
            <td class="header-left">
                <table class="company-info-table">
                    <tr>
                        <td style="width: 85px;">
                            @if($profile->logo && file_exists(public_path('storage/logos/' . $profile->logo)))
                                <img src="{{ public_path('storage/logos/' . $profile->logo) }}" class="logo-toko" alt="Logo">
                            @else
                                <img src="{{ public_path('images/mu2.jpeg') }}" class="logo-toko" alt="Logo">
                            @endif
                        </td>
                        <td>
                            <h1 class="company-name">{{ $profile->nama_toko ?: 'MITRA USAHA 2' }}</h1>
                            <p class="company-slogan">{{ $profile->tagline ?: 'Maju Bersama Membangun Negeri' }}</p>
                            
                            <p class="company-address">{{ $profile->alamat ?: 'Alamat belum diatur di sistem' }}</p>
                            <p class="company-address">Telp. {{ $profile->telepon ?: '-' }} &nbsp;|&nbsp; Email: {{ $profile->email ?: '-' }}</p>
                        </td>
                    </tr>
                </table>
            </td>
            
            <td class="header-right">
                <h2 class="report-title">LAPORAN MUTASI STOK</h2>
                
                <table class="meta-box" align="right">
                    <tr><th>Tanggal Cetak</th></tr>
                    <tr><td>{{ now()->translatedFormat('d F Y') }}</td></tr>
                    <tr><th>Dicetak Oleh</th></tr>
                    <tr><td>{{ Auth::user()->name ?? 'Admin Sistem' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="garis-kop"></div>

    <p style="margin: 0 0 10px 0; font-size: 11px;">
        <strong>Periode Data:</strong> {{ \Carbon\Carbon::parse($startDate ?? now())->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate ?? now())->translatedFormat('d M Y') }}<br>
        <strong>Kategori Filter:</strong> {{ $kategori ?: 'Semua Kategori' }}
    </p>

    <table class="tabel-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 14%;">Kode Item</th>
                <th style="width: 34%;">Nama Item & Spesifikasi</th>
                <th style="width: 8%;">Satuan</th>
                <th style="width: 10%;">Stok Awal</th>
                <th style="width: 10%;">Masuk (+)</th>
                <th style="width: 10%;">Keluar (-)</th>
                <th style="width: 10%;">Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-center">{{ $item->stok_awal }}</td>
                    <td class="text-center">{{ $item->masuk }}</td>
                    <td class="text-center">{{ $item->keluar }}</td>
                    <td class="text-center font-bold">{{ $item->stok_akhir }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; font-style: italic; color: #666;">
                        Tidak ada mutasi data untuk periode yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Kepala Gudang</strong>
                    <div class="ttd-space"></div>
                    ( <span style="text-decoration: underline;">{{ $profile->nama_kepala_gudang ?: '....................................' }}</span> )
                </td>
                <td>
                    </td>
                <td>
                    {{ $profile->kota_ttd ?: 'Pontianak' }}, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Dibuat Oleh</strong>
                    <div class="ttd-space"></div>
                    ( <span style="text-decoration: underline;">{{ Auth::user()->name ?? '....................................' }}</span> )
                </td>
            </tr>
        </table>
    </div>

</body>
</html>