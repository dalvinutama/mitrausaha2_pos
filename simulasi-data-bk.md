# Simulasi Data Barang Keluar (Skenario Uji Sistem)
## Toko Bangunan Mitra Usaha 2 Pontianak

**Dasar:** Data barang keluar ini dibuat berdasarkan stok yang tersedia setelah barang masuk (BM) sebelumnya. Berlaku untuk skenario pengujian sistem.

---

## 📊 Stok Awal Sebelum Barang Keluar Baru

(Stok awal produk + stok dari barang masuk - barang keluar sebelumnya)

| No | Produk | Stok Awal | +BM | -BK Lama | Stok Tersedia |
|:--:|--------|:---------:|:---:|:--------:|:-------------:|
| 1 | Semen Padang 50kg | 150 | +50 | -45 | **155** |
| 2 | Semen Holcim 50kg | 120 | +0 | -20 | **100** |
| 3 | Semen Tiga Roda 50kg | 0 | +0 | -0 | **0 (Habis)** |
| 4 | Besi Beton 10mm | 200 | +100 | -2(opname) | **298** |
| 5 | Besi Beton 12mm | 180 | +80 | -50 | **210** |
| 6 | Kawat Bendrat 1kg | 15 | +20 | -0 | **35** |
| 7 | Kayu Borneo 6x12 | 75 | +40 | -2(opname) | **113** |
| 8 | Triplek 9mm | 50 | +30 | -0 | **80** |
| 9 | Cat Tembok Avitex Putih 5kg | 40 | +0 | -5 | **35** |
| 10 | Cat Tembok Dulux Biru 5kg | 25 | +0 | -2 | **23** |
| 11 | Cat Kayu Nippon Paint 1kg | 5 | +0 | -0 | **5** |
| 12 | Pipa PVC 2 inch | 100 | +60 | -0 | **160** |
| 13 | Pipa PVC 3 inch | 80 | +40 | -15 | **105** |
| 14 | Keramik Roman 40x40 Putih | 300 | +150 | -83 | **367** |
| 15 | Keramik Asia Tile 50x50 | 250 | +100 | -1 | **349** |
| 16 | Keramik Mulia 25x40 | 8 | +0 | -0 | **8** |
| 17 | Palu Konde 1kg | 30 | +0 | -1 | **29** |
| 18 | Sekop Baja | 25 | +0 | -0 | **25** |
| 19 | Kabel Listrik NYA 2.5mm | 60 | +0 | -0 | **60** |
| 20 | Stop Kontak Broco | 45 | +0 | -5 | **40** |

---

## 🧪 Skenario 1: Proyek Perumahan Griya Asri (19 Mei 2026)
**No Transaksi:** BK-20260519-0007
**Tanggal:** 19 Mei 2026
**Pelanggan / Tujuan:** Proyek Perumahan Griya Asri (Pengembang)
**Kategori Keluar:** Penjualan Proyek
**Penjualan:** Siti Nurhaliza
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Semen Padang 50kg | 50 | Rp68.000 | 5% | Rp3.400 | Rp64.600 |
| 2 | Besi Beton 10mm | 80 | Rp105.000 | 0% | Rp0 | Rp8.400.000 |
| 3 | Besi Beton 12mm | 60 | Rp118.000 | 0% | Rp0 | Rp7.080.000 |
| 4 | Kawat Bendrat 1kg | 10 | Rp30.000 | 0% | Rp0 | Rp300.000 |
| 5 | Pipa PVC 2 inch | 40 | Rp35.000 | 0% | Rp0 | Rp1.400.000 |
| 6 | Pipa PVC 3 inch | 30 | Rp48.000 | 0% | Rp0 | Rp1.440.000 |
| | **Total** | | | | | **Rp18.684.600** |

**Uji Kasus:** Transaksi multi-item (6 produk) dengan diskon 5% pada semen.

---

## 🧪 Skenario 2: Pemborong An. Rahman (20 Mei 2026)
**No Transaksi:** BK-20260520-0008
**Tanggal:** 20 Mei 2026
**Pelanggan / Tujuan:** Rahman (Pemborong)
**Kategori Keluar:** Penjualan Grosir
**Penjualan:** Siti Nurhaliza
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Semen Holcim 50kg | 25 | Rp72.000 | 0% | Rp0 | Rp1.800.000 |
| 2 | Triplek 9mm | 15 | Rp120.000 | 0% | Rp0 | Rp1.800.000 |
| 3 | Kayu Borneo 6x12 | 20 | Rp225.000 | 0% | Rp0 | Rp4.500.000 |
| 4 | Cat Tembok Avitex Putih 5kg | 10 | Rp110.000 | 0% | Rp0 | Rp1.100.000 |
| | **Total** | | | | | **Rp9.200.000** |

**Uji Kasus:** Transaksi dengan 4 produk kategori berbeda (semen, kayu, cat).

---

## 🧪 Skenario 3: Customer Eceran — An. Fatimah (21 Mei 2026)
**No Transaksi:** BK-20260521-0009
**Tanggal:** 21 Mei 2026
**Pelanggan / Tujuan:** Fatimah (Customer)
**Kategori Keluar:** Penjualan Eceran
**Penjualan:** Siti Nurhaliza
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Cat Tembok Dulux Biru 5kg | 1 | Rp155.000 | 0% | Rp0 | Rp155.000 |
| 2 | Palu Konde 1kg | 1 | Rp38.000 | 0% | Rp0 | Rp38.000 |
| 3 | Stop Kontak Broco | 2 | Rp18.000 | 0% | Rp0 | Rp36.000 |
| | **Total** | | | | | **Rp229.000** |

**Uji Kasus:** Transaksi eceran dengan 3 item kecil.

---

## 🧪 Skenario 4: Proyek Renovasi Sekolah (22 Mei 2026)
**No Transaksi:** BK-20260522-0010
**Tanggal:** 22 Mei 2026
**Pelanggan / Tujuan:** Renovasi SDN 07 Pontianak
**Kategori Keluar:** Penjualan Proyek
**Penjualan:** Siti Nurhaliza
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Semen Padang 50kg | 30 | Rp68.000 | 5% | Rp3.400 | Rp64.600 |
| 2 | Keramik Roman 40x40 Putih | 100 | Rp55.000 | 0% | Rp0 | Rp5.500.000 |
| 3 | Cat Tembok Avitex Putih 5kg | 8 | Rp110.000 | 0% | Rp0 | Rp880.000 |
| 4 | Kabel Listrik NYA 2.5mm | 10 | Rp155.000 | 0% | Rp0 | Rp1.550.000 |
| 5 | Stop Kontak Broco | 10 | Rp18.000 | 0% | Rp0 | Rp180.000 |
| | **Total** | | | | | **Rp8.174.600** |

**Uji Kasus:** Campuran material bangunan + listrik.

---

## 🧪 Skenario 5: Internal — Pemakaian Sendiri (23 Mei 2026)
**No Transaksi:** BK-20260523-0011
**Tanggal:** 23 Mei 2026
**Pelanggan / Tujuan:** Pemakaian Internal (Renovasi Toko)
**Kategori Keluar:** Pemakaian Internal
**Penjualan:** Siti Nurhaliza
**Catatan:** Pemakaian untuk renovasi rak gudang toko
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Kayu Borneo 6x12 | 5 | Rp225.000 | 0% | Rp0 | Rp1.125.000 |
| 2 | Triplek 9mm | 8 | Rp120.000 | 0% | Rp0 | Rp960.000 |
| 3 | Palu Konde 1kg | 2 | Rp38.000 | 0% | Rp0 | Rp76.000 |
| 4 | Sekop Baja | 2 | Rp50.000 | 0% | Rp0 | Rp100.000 |
| | **Total** | | | | | **Rp2.261.000** |

**Uji Kasus:** Kategori "Pemakaian Internal" — khusus untuk kebutuhan toko sendiri, bukan penjualan.

---

## 🧪 Skenario 6: Stok Habis — Semen Tiga Roda (24 Mei 2026)

**No Transaksi:** BK-20260524-0012
**Tanggal:** 24 Mei 2026
**Pelanggan / Tujuan:** Hasan (Customer)
**Kategori Keluar:** Penjualan Eceran
**Penjualan:** Siti Nurhaliza
**Status:** **GAGAL — Stok Tidak Mencukupi**

| No | Produk | Qty Diminta | Stok Tersedia | Hasil |
|:--:|--------|:-----------:|:-------------:|:------|
| 1 | Semen Tiga Roda 50kg | 10 | **0** | ❌ Gagal — stok habis |
| 2 | Semen Padang 50kg | 5 | 75 | ✅ Bisa diproses |

**Uji Kasus:** Sistem harus **menolak transaksi** atau menampilkan peringatan bahwa stok Semen Tiga Roda tidak mencukupi.

---

## 🧪 Skenario 7: Pembelian Partai Besar — PT. Bangun Bersama (25 Mei 2026)
**No Transaksi:** BK-20260525-0013
**Tanggal:** 25 Mei 2026
**Pelanggan / Tujuan:** PT. Bangun Bersama (Kontraktor)
**Kategori Keluar:** Penjualan Grosir
**Penjualan:** Siti Nurhaliza
**Catatan:** Pembayaran tempo 30 hari
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Besi Beton 10mm | 150 | Rp105.000 | 8% | Rp8.400 | Rp96.600 |
| 2 | Keramik Asia Tile 50x50 | 200 | Rp70.000 | 10% | Rp7.000 | Rp63.000 |
| 3 | Semen Holcim 50kg | 50 | Rp72.000 | 5% | Rp3.600 | Rp68.400 |
| 4 | Kawat Bendrat 1kg | 20 | Rp30.000 | 0% | Rp0 | Rp30.000 |
| 5 | Pipa PVC 3 inch | 50 | Rp48.000 | 0% | Rp0 | Rp2.400.000 |
| | **Total** | | | | | **Rp17.953.000** (sebelum diskon) |

*Perhitungan manual:*
- Besi: 150 × Rp105.000 = Rp15.750.000 - diskon 8% = Rp14.490.000
- Keramik: 200 × Rp70.000 = Rp14.000.000 - diskon 10% = Rp12.600.000
- Semen: 50 × Rp72.000 = Rp3.600.000 - diskon 5% = Rp3.420.000
- Kawat: 20 × Rp30.000 = Rp600.000
- Pipa: 50 × Rp48.000 = Rp2.400.000

**Total setelah diskon: Rp33.510.000**

**Uji Kasus:** Transaksi volume besar dengan diskon bertingkat per item. Uji perhitungan subtotal otomatis sistem.

---

## 🧪 Skenario 8: Retur Barang Rusak dari BM Terakhir (26 Mei 2026)
**No Transaksi:** BK-20260526-0014
**Tanggal:** 26 Mei 2026
**Pelanggan / Tujuan:** Retur ke Supplier (PT. Pipa PVC Sejahtera)
**Kategori Keluar:** Retur Supplier
**Penjualan:** Siti Nurhaliza
**Catatan:** Retur dari BM-20260515-0005 — Pipa PVC 2 inch ditemukan cacat produksi
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Pipa PVC 2 inch | 5 | Rp25.000 | 0% | Rp0 | Rp125.000 |
| | **Total** | | | | | **Rp125.000** |

*Menggunakan harga beli (Rp25.000) karena retur ke supplier.*

**Uji Kasus:** Kategori "Retur Supplier" dengan harga pokok (bukan harga jual).

---

## 🧪 Skenario 9: Penjualan Akhir Bulan — Beragam Customer (28 Mei 2026)
**No Transaksi:** BK-20260528-0015
**Tanggal:** 28 Mei 2026
**Pelanggan / Tujuan:** Beragam (Gabungan)
**Kategori Keluar:** Penjualan Eceran
**Penjualan:** Siti Nurhaliza
**Status:** Selesai

| No | Produk | Qty | Harga Jual | Diskon (%) | Diskon (Rp) | Subtotal |
|:--:|--------|:---:|:----------:|:----------:|:-----------:|:--------:|
| 1 | Cat Kayu Nippon Paint 1kg | 3 | Rp62.000 | 0% | Rp0 | Rp186.000 |
| 2 | Sekop Baja | 5 | Rp50.000 | 0% | Rp0 | Rp250.000 |
| 3 | Stop Kontak Broco | 10 | Rp18.000 | 0% | Rp0 | Rp180.000 |
| 4 | Keramik Mulia 25x40 | 5 | Rp50.000 | 0% | Rp0 | Rp250.000 |
| | **Total** | | | | | **Rp866.000** |

**Uji Kasus:** Transaksi campuran dengan aksesoris bangunan.

---

## 📋 Rekapitulasi Semua Skenario

| No | No Transaksi | Tgl | Pelanggan | Total Item | Total Nilai | Kategori | Catatan Uji |
|:--:|:-----------:|:---:|-----------|:----------:|:-----------:|----------|-------------|
| 1 | BK-20260519-0007 | 19-05 | Proyek Griya Asri | 6 | Rp18.684.600 | Penjualan Proyek | Multi-item + diskon |
| 2 | BK-20260520-0008 | 20-05 | Rahman (Pemborong) | 4 | Rp9.200.000 | Penjualan Grosir | Campur kategori |
| 3 | BK-20260521-0009 | 21-05 | Fatimah | 3 | Rp229.000 | Penjualan Eceran | Eceran kecil |
| 4 | BK-20260522-0010 | 22-05 | SDN 07 Pontianak | 5 | Rp8.174.600 | Penjualan Proyek | Material + listrik |
| 5 | BK-20260523-0011 | 23-05 | Pemakaian Internal | 4 | Rp2.261.000 | Pemakaian Internal | Non-penjualan |
| 6 | BK-20260524-0012 | 24-05 | Hasan | 1 | ❌ Gagal | Penjualan Eceran | **Stok habis test** |
| 7 | BK-20260525-0013 | 25-05 | PT. Bangun Bersama | 5 | Rp33.510.000 | Penjualan Grosir | Volume besar + diskon % |
| 8 | BK-20260526-0014 | 26-05 | Retur Supplier | 1 | Rp125.000 | Retur Supplier | Retur harga beli |
| 9 | BK-20260528-0015 | 28-05 | Beragam | 4 | Rp866.000 | Penjualan Eceran | Akhir bulan |

---

## ✅ Skenario Uji yang Dicakup

| No | Skenario Uji | Terdapat di |
|:--:|-------------|:-----------:|
| 1 | Transaksi dengan 1 item (sederhana) | Skenario 3, 8 |
| 2 | Transaksi multi-item (banyak produk) | Skenario 1, 4, 7 |
| 3 | Diskon per item (%) | Skenario 1, 4, 7 |
| 4 | Pembayaran tempo | Skenario 7 |
| 5 | Kategori "Pemakaian Internal" | Skenario 5 |
| 6 | Kategori "Retur Supplier" | Skenario 8 |
| 7 | Stok habis / tidak mencukupi (error handling) | Skenario 6 |
| 8 | Transaksi volume besar (puluhan juta) | Skenario 7 |
| 9 | Transaksi eceran kecil (ratusan ribu) | Skenario 3, 9 |
| 10 | Pelanggan proyek (instansi) | Skenario 4 |
