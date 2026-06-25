<table>
    <thead>
        <tr>
            {{-- Colspan 18 karena sekarang kolomnya jauh lebih lengkap dan melebar --}}
            <th colspan="18" style="font-size: 16px; font-weight: bold; text-align: center; vertical-align: middle; height: 30px;">
                LAPORAN REKAPITULASI MUTASI & VALUASI PERSEDIAAN BARANG
            </th>
        </tr>
        <tr>
            <th colspan="18" style="font-size: 14px; font-weight: bold; text-align: center; vertical-align: middle; height: 25px;">
                Toko Bangunan Mitra Usaha 2
            </th>
        </tr>
        <tr>
            <th colspan="18" style="font-style: italic; text-align: center; color: #555555; vertical-align: middle; height: 20px;">
                Periode: {{ $periode }}
            </th>
        </tr>
        <tr>
            <th colspan="18"></th> {{-- Baris Kosong sebagai pemisah --}}
        </tr>

        {{-- HEADER TIER 1 --}}
        <tr>
            <th rowspan="2" style="background-color: #D00000; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 5px;">No</th>
            <th rowspan="2" style="background-color: #D00000; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 15px;">Kode / SKU</th>
            <th rowspan="2" style="background-color: #D00000; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 35px;">Nama Barang & Spesifikasi</th>
            <th rowspan="2" style="background-color: #D00000; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 15px;">Kategori</th>
            <th rowspan="2" style="background-color: #D00000; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 10px;">Satuan</th>
            <th rowspan="2" style="background-color: #D00000; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 15px;">Harga Modal (Rp)</th>
            
            <th colspan="2" style="background-color: #607D8B; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Persediaan Awal</th>
            <th colspan="2" style="background-color: #2E7D32; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Barang Masuk (+)</th>
            <th colspan="2" style="background-color: #C62828; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Barang Keluar (-)</th>
            <th colspan="2" style="background-color: #1565C0; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Sistem (Akhir)</th>
            <th colspan="2" style="background-color: #4527A0; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Fisik (Opname)</th>
            <th colspan="2" style="background-color: #E65100; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Selisih</th>
        </tr>

        {{-- HEADER TIER 2 --}}
        <tr>
            {{-- Awal --}}
            <th style="background-color: #CFD8DC; font-weight: bold; text-align: center; border: 1px solid #000000; width: 10px;">Qty</th>
            <th style="background-color: #CFD8DC; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Nilai (Rp)</th>
            {{-- Masuk --}}
            <th style="background-color: #C8E6C9; font-weight: bold; text-align: center; border: 1px solid #000000; width: 10px;">Qty</th>
            <th style="background-color: #C8E6C9; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Nilai (Rp)</th>
            {{-- Keluar --}}
            <th style="background-color: #FFCDD2; font-weight: bold; text-align: center; border: 1px solid #000000; width: 10px;">Qty</th>
            <th style="background-color: #FFCDD2; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Nilai (Rp)</th>
            {{-- Sistem --}}
            <th style="background-color: #BBDEFB; font-weight: bold; text-align: center; border: 1px solid #000000; width: 10px;">Qty</th>
            <th style="background-color: #BBDEFB; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Nilai (Rp)</th>
            {{-- Fisik --}}
            <th style="background-color: #D1C4E9; font-weight: bold; text-align: center; border: 1px solid #000000; width: 10px;">Qty</th>
            <th style="background-color: #D1C4E9; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Nilai (Rp)</th>
            {{-- Selisih --}}
            <th style="background-color: #FFE0B2; font-weight: bold; text-align: center; border: 1px solid #000000; width: 10px;">Qty</th>
            <th style="background-color: #FFE0B2; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Nilai (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $index => $item)
            @php
                // Kalkulasi Valuasi (Mirip dengan yang ada di web view)
                $hpp = $item->harga_pokok ?? 0;
                
                $qty_awal = $item->stok_awal ?? 0;
                $nilai_awal = $qty_awal * $hpp;
                
                $qty_masuk = $item->masuk ?? 0;
                $nilai_masuk = $qty_masuk * $hpp;
                
                $qty_keluar = $item->keluar ?? 0;
                $nilai_keluar = $qty_keluar * $hpp;
                
                $qty_akhir = $item->stok_akhir ?? 0;
                $nilai_akhir = $qty_akhir * $hpp;
                
                $qty_fisik = $item->stok_fisik ?? $qty_akhir; 
                $nilai_fisik = $qty_fisik * $hpp;
                
                $qty_selisih = $qty_fisik - $qty_akhir;
                $nilai_selisih = $qty_selisih * $hpp;
            @endphp
        <tr>
            <td style="text-align: center; border: 1px solid #B0BEC5;">{{ $index + 1 }}</td>
            <td style="text-align: left; border: 1px solid #B0BEC5;">{{ $item->kode ?? '-' }}</td>
            <td style="text-align: left; border: 1px solid #B0BEC5; font-weight: bold;">{{ $item->nama }}</td>
            <td style="text-align: left; border: 1px solid #B0BEC5;">{{ $item->kategori ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid #B0BEC5;">{{ $item->satuan }}</td>
            <td style="text-align: right; border: 1px solid #B0BEC5;">{{ $hpp }}</td>
            
            {{-- Awal --}}
            <td style="text-align: center; border: 1px solid #B0BEC5;">{{ $qty_awal }}</td>
            <td style="text-align: right; border: 1px solid #B0BEC5;">{{ $nilai_awal }}</td>
            
            {{-- Masuk --}}
            <td style="text-align: center; border: 1px solid #B0BEC5; color: #2E7D32; font-weight: bold;">{{ $qty_masuk > 0 ? '+'.$qty_masuk : 0 }}</td>
            <td style="text-align: right; border: 1px solid #B0BEC5; color: #2E7D32;">{{ $nilai_masuk }}</td>
            
            {{-- Keluar --}}
            <td style="text-align: center; border: 1px solid #B0BEC5; color: #C62828; font-weight: bold;">{{ $qty_keluar > 0 ? '-'.$qty_keluar : 0 }}</td>
            <td style="text-align: right; border: 1px solid #B0BEC5; color: #C62828;">{{ $nilai_keluar }}</td>
            
            {{-- Sistem Akhir --}}
            <td style="text-align: center; border: 1px solid #B0BEC5; color: #1565C0; font-weight: bold;">{{ $qty_akhir }}</td>
            <td style="text-align: right; border: 1px solid #B0BEC5; color: #1565C0; font-weight: bold;">{{ $nilai_akhir }}</td>
            
            {{-- Fisik --}}
            <td style="text-align: center; border: 1px solid #B0BEC5; color: #4527A0; font-weight: bold;">{{ $qty_fisik }}</td>
            <td style="text-align: right; border: 1px solid #B0BEC5; color: #4527A0; font-weight: bold;">{{ $nilai_fisik }}</td>
            
            {{-- Selisih --}}
            <td style="text-align: center; border: 1px solid #B0BEC5; font-weight: bold; color: {{ $qty_selisih < 0 ? '#C62828' : ($qty_selisih > 0 ? '#2E7D32' : '#555555') }};">
                {{ $qty_selisih > 0 ? '+'.$qty_selisih : $qty_selisih }}
            </td>
            <td style="text-align: right; border: 1px solid #B0BEC5; font-weight: bold; color: {{ $nilai_selisih < 0 ? '#C62828' : ($nilai_selisih > 0 ? '#2E7D32' : '#555555') }};">
                {{ $nilai_selisih }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>