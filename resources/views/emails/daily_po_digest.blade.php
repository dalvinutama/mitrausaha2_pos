<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 30px; }
        .container { max-width: 700px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: {{ $emailSetting->header_color ?? '#0f172a' }}; padding: 25px 30px; text-align: center; }
        .header h1 { color: {{ $emailSetting->primary_color ?? '#ffffff' }}; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 30px; color: #374151; line-height: 1.6; }
        .content h2 { color: #111827; margin-top: 0; }
        .alert-box { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #f8fafc; color: #475569; font-size: 12px; text-transform: uppercase; padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0; }
        td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        .total-row { background-color: #f1f5f9; font-weight: bold; color: #0f172a; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; border-top: 1px solid #f3f4f6; }
        .btn { display: inline-block; background-color: {{ $emailSetting->primary_color ?? '#f59e0b' }}; color: white !important; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; margin-top: 25px; }
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
            <h2>{{ $emailSetting->po_digest_title ?? 'Laporan Rekapitulasi Purchase Order' }}</h2>
            
            <div class="alert-box">
                <p style="margin:0;"><strong>Perhatian:</strong> Terdapat <strong>{{ $pos->count() }} Purchase Order</strong> yang saat ini masih berstatus <em>Pending</em> dan menunggu persetujuan Anda.</p>
            </div>

            <p>{{ $emailSetting->po_digest_intro ?? 'Berikut adalah ringkasan transaksinya:' }}</p>
            
            <table>
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th style="text-align: right;">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($pos as $po)
                    <tr>
                        <td><strong>{{ $po->no_transaksi }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($po->supplier->nama_supplier ?? '-', 20) }}</td>
                        <td style="text-align: right;">Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                    </tr>
                    @php $grandTotal += $po->total_nilai; @endphp
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">GRAND TOTAL</td>
                        <td style="text-align: right; color: #D00000;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <center>
                <a href="{{ url('/purchase-order') }}" class="btn">Tinjau & Setujui Sekarang</a>
            </center>
        </div>
        <div class="footer">
            <p>{{ $emailSetting->footer_text ?? 'Email ini dikirim secara otomatis oleh Sistem.' }}</p>
        </div>
    </div>
</body>
</html>
