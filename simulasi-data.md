# Simulasi Data Sistem Informasi Persediaan
## Toko Bangunan Mitra Usaha 2 Pontianak

---

## A. Data Master — User

| No | Nama | Email | Role | Password |
|:--:|------|-------|:----:|----------|
| 1 | Hendra Gunawan | hendra@mitrausaha2.com | Owner | owner123 |
| 2 | Rina Kartika | rina@mitrausaha2.com | Admin | admin123 |
| 3 | Ahmad Fauzi | ahmad@mitrausaha2.com | Gudang | gudang123 |
| 4 | Siti Nurhaliza | siti@mitrausaha2.com | Penjualan | jual123 |
| 5 | Budi Santoso | budi@mitrausaha2.com | Pengiriman | kirim123 |
| 6 | Dewi Lestari | dewi@mitrausaha2.com | Kasir | kasir123 |

---

## B. Data Master — Supplier

| No | Nama Supplier | Alamat | PIC | No HP | Email | Termin | Bank | No Rekening |
|:--:|-------------|--------|:---:|:-----:|-------|:------:|:----:|:-----------:|
| 1 | PT. Semen Indonesia Tbk | Jl. Raya Industri No. 10, Pontianak, Kalbar | Bambang Wijaya | 0812-5678-9012 | bambang@semenindonesia.com | Net30 | BCA | 1234567890 |
| 2 | CV. Besi Baja Utama | Jl. Gajah Mada No. 45, Pontianak, Kalbar | Agus Prasetyo | 0821-3456-7890 | agus@besibaja.com | Cash | Mandiri | 2345678901 |
| 3 | UD. Kayu Meranti | Jl. Tanjung Pura No. 78, Pontianak, Kalbar | Sukirman | 0856-1234-5678 | sukirman@kayumeranti.com | Cash | BNI | 3456789012 |
| 4 | Toko Cat Pelangi | Jl. Diponegoro No. 23, Pontianak, Kalbar | Herman Susanto | 0878-1234-5678 | herman@catpelangi.com | Net30 | BRI | 4567890123 |
| 5 | PT. Pipa PVC Sejahtera | Jl. Ahmad Yani No. 156, Pontianak, Kalbar | Dedi Kurniawan | 0899-1234-5678 | dedi@pipapvc.com | Cash | BCA | 5678901234 |
| 6 | CV. Keramik Indah | Jl. Siantan No. 88, Pontianak, Kalbar | Yuni Astuti | 0852-3456-7891 | yuni@keramikindah.com | Net30 | Mandiri | 6789012345 |

---

## C. Data Master — Kategori

| No | Prefix SKU | Nama Kategori | Deskripsi |
|:--:|:---------:|---------------|-----------|
| 1 | SMT | Semen | Kategori untuk semen berbagai merek dan ukuran |
| 2 | BJ | Besi & Baja | Kategori untuk besi beton, baja ringan, dan kawat |
| 3 | KY | Kayu | Kategori untuk kayu bangunan dan triplek |
| 4 | CT | Cat | Kategori untuk cat tembok, cat kayu, dan cat besi |
| 5 | PPA | Pipa & PVC | Kategori untuk pipa PVC, fitting, dan sambungan |
| 6 | KRM | Keramik | Kategori untuk keramik lantai dan dinding |
| 7 | PKU | Perkakas | Kategori untuk alat pertukangan dan perkakas |
| 8 | LTR | Listrik | Kategori untuk kabel, stop kontak, dan perlengkapan listrik |

---

## D. Data Master — Produk

| No | SKU | Nama Barang | Kategori | Stok | Lead Time | Safety Stok | Reorder Point | Harga Beli | Harga Jual | Satuan |
|:--:|:---:|-------------|:--------:|:---:|:---------:|:-----------:|:-------------:|:----------:|:----------:|:------:|
| 1 | SMT-001 | Semen Padang 50kg | Semen | 150 | 3 | 30 | 60 | Rp55.000 | Rp68.000 | Sak |
| 2 | SMT-002 | Semen Holcim 50kg | Semen | 120 | 3 | 25 | 50 | Rp58.000 | Rp72.000 | Sak |
| 3 | SMT-003 | Semen Tiga Roda 50kg | Semen | 0 | 3 | 10 | 30 | Rp57.000 | Rp70.000 | Sak |
| 4 | BJ-001 | Besi Beton 10mm | Besi & Baja | 200 | 5 | 40 | 90 | Rp85.000 | Rp105.000 | Batang |
| 5 | BJ-002 | Besi Beton 12mm | Besi & Baja | 180 | 5 | 35 | 80 | Rp95.000 | Rp118.000 | Batang |
| 6 | BJ-003 | Kawat Bendrat 1kg | Besi & Baja | 15 | 4 | 10 | 20 | Rp20.000 | Rp30.000 | Kg |
| 7 | KY-001 | Kayu Borneo 6x12 | Kayu | 75 | 7 | 15 | 35 | Rp180.000 | Rp225.000 | Batang |
| 8 | KY-002 | Triplek 9mm | Kayu | 50 | 7 | 10 | 25 | Rp95.000 | Rp120.000 | Lembar |
| 9 | CT-001 | Cat Tembok Avitex Putih 5kg | Cat | 40 | 4 | 10 | 20 | Rp85.000 | Rp110.000 | Pail |
| 10 | CT-002 | Cat Tembok Dulux Biru 5kg | Cat | 25 | 4 | 8 | 15 | Rp120.000 | Rp155.000 | Pail |
| 11 | CT-003 | Cat Kayu Nippon Paint 1kg | Cat | 5 | 4 | 5 | 10 | Rp45.000 | Rp62.000 | Kaleng |
| 12 | PPA-001 | Pipa PVC 2 inch | Pipa & PVC | 100 | 4 | 20 | 45 | Rp25.000 | Rp35.000 | Batang |
| 13 | PPA-002 | Pipa PVC 3 inch | Pipa & PVC | 80 | 4 | 15 | 35 | Rp35.000 | Rp48.000 | Batang |
| 14 | KRM-001 | Keramik Roman 40x40 Putih | Keramik | 300 | 6 | 50 | 120 | Rp42.000 | Rp55.000 | Dus |
| 15 | KRM-002 | Keramik Asia Tile 50x50 | Keramik | 250 | 6 | 40 | 100 | Rp55.000 | Rp70.000 | Dus |
| 16 | KRM-003 | Keramik Mulia 25x40 | Keramik | 8 | 6 | 20 | 40 | Rp38.000 | Rp50.000 | Dus |
| 17 | PKU-001 | Palu Konde 1kg | Perkakas | 30 | 3 | 8 | 15 | Rp25.000 | Rp38.000 | Pcs |
| 18 | PKU-002 | Sekop Baja | Perkakas | 25 | 3 | 5 | 12 | Rp35.000 | Rp50.000 | Pcs |
| 19 | LTR-001 | Kabel Listrik NYA 2.5mm | Listrik | 60 | 4 | 10 | 25 | Rp120.000 | Rp155.000 | Rol |
| 20 | LTR-002 | Stop Kontak Broco | Listrik | 45 | 4 | 10 | 20 | Rp12.000 | Rp18.000 | Pcs |

---

## E. Data Transaksi — Barang Masuk

### E.1 BM-20260501-0001 - 01 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BM-20260501-0001 |
| Tanggal | 01 Mei 2026 |
| Supplier | PT. Semen Indonesia Tbk |
| Admin / Pencatat | Rina Kartika |
| Tipe Pembayaran | Net30 |
| No Referensi | Faktur SI-2026-0451 |
| Status | Selesai |

**Item Barang Masuk:**

| No | Produk | Qty Baik | Qty Rusak | Harga Satuan | Subtotal |
|:--:|--------|:--------:|:---------:|:------------:|:--------:|
| 1 | Semen Padang 50kg | 50 | 0 | Rp55.000 | Rp2.750.000 |
| **Total Nilai** | | | | | **Rp2.750.000** |

### E.2 BM-20260503-0002 - 03 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BM-20260503-0002 |
| Tanggal | 03 Mei 2026 |
| Supplier | CV. Besi Baja Utama |
| Admin / Pencatat | Rina Kartika |
| Tipe Pembayaran | Cash |
| No Referensi | Faktur BBU-2026-0788 |
| Status | Selesai |

**Item Barang Masuk:**

| No | Produk | Qty Baik | Qty Rusak | Harga Satuan | Subtotal |
|:--:|--------|:--------:|:---------:|:------------:|:--------:|
| 1 | Besi Beton 10mm | 100 | 2 | Rp85.000 | Rp8.500.000 |
| 2 | Besi Beton 12mm | 80 | 0 | Rp95.000 | Rp7.600.000 |
| 3 | Kawat Bendrat 1kg | 20 | 0 | Rp20.000 | Rp400.000 |
| **Total Nilai** | | | | | **Rp16.500.000** |

### E.3 BM-20260507-0003 - 07 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BM-20260507-0003 |
| Tanggal | 07 Mei 2026 |
| Supplier | CV. Keramik Indah |
| Admin / Pencatat | Rina Kartika |
| Tipe Pembayaran | Net30 |
| No Referensi | Faktur KI-2026-0123 |
| Status | Selesai |

**Item Barang Masuk:**

| No | Produk | Qty Baik | Qty Rusak | Harga Satuan | Subtotal |
|:--:|--------|:--------:|:---------:|:------------:|:--------:|
| 1 | Keramik Roman 40x40 Putih | 150 | 3 | Rp42.000 | Rp6.300.000 |
| 2 | Keramik Asia Tile 50x50 | 100 | 1 | Rp55.000 | Rp5.500.000 |
| **Total Nilai** | | | | | **Rp11.800.000** |

### E.4 BM-20260510-0004 - 10 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BM-20260510-0004 |
| Tanggal | 10 Mei 2026 |
| Supplier | UD. Kayu Meranti |
| Admin / Pencatat | Rina Kartika |
| Tipe Pembayaran | Cash |
| No Referensi | Faktur KM-2026-0345 |
| Status | Selesai |

**Item Barang Masuk:**

| No | Produk | Qty Baik | Qty Rusak | Harga Satuan | Subtotal |
|:--:|--------|:--------:|:---------:|:------------:|:--------:|
| 1 | Kayu Borneo 6x12 | 40 | 1 | Rp180.000 | Rp7.200.000 |
| 2 | Triplek 9mm | 30 | 0 | Rp95.000 | Rp2.850.000 |
| **Total Nilai** | | | | | **Rp10.050.000** |

### E.5 BM-20260515-0005 - 15 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BM-20260515-0005 |
| Tanggal | 15 Mei 2026 |
| Supplier | PT. Pipa PVC Sejahtera |
| Admin / Pencatat | Rina Kartika |
| Tipe Pembayaran | Cash |
| No Referensi | Faktur PPS-2026-0567 |
| Status | Selesai |

**Item Barang Masuk:**

| No | Produk | Qty Baik | Qty Rusak | Harga Satuan | Subtotal |
|:--:|--------|:--------:|:---------:|:------------:|:--------:|
| 1 | Pipa PVC 2 inch | 60 | 0 | Rp25.000 | Rp1.500.000 |
| 2 | Pipa PVC 3 inch | 40 | 0 | Rp35.000 | Rp1.400.000 |
| **Total Nilai** | | | | | **Rp2.900.000** |

---

## F. Data Transaksi — Barang Keluar

### F.1 BK-20260502-0001 - 02 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BK-20260502-0001 |
| Tanggal | 02 Mei 2026 |
| Pelanggan / Tujuan | Proyek Perumahan Bumi Indah |
| Kategori Keluar | Penjualan |
| Penjualan | Siti Nurhaliza |
| Status | Selesai |

**Item Barang Keluar:**

| No | Produk | Qty | Harga Satuan | Diskon | Subtotal |
|:--:|--------|:---:|:------------:|:-----:|:--------:|
| 1 | Semen Padang 50kg | 30 | Rp68.000 | Rp0 | Rp2.040.000 |
| **Total Nilai** | | | | | **Rp2.040.000** |

### F.2 BK-20260504-0002 - 04 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BK-20260504-0002 |
| Tanggal | 04 Mei 2026 |
| Pelanggan / Tujuan | Pembangunan Masjid Al-Ikhlas |
| Kategori Keluar | Penjualan |
| Penjualan | Siti Nurhaliza |
| Status | Selesai |

**Item Barang Keluar:**

| No | Produk | Qty | Harga Satuan | Diskon | Subtotal |
|:--:|--------|:---:|:------------:|:-----:|:--------:|
| 1 | Besi Beton 12mm | 50 | Rp118.000 | Rp0 | Rp5.900.000 |
| 2 | Semen Holcim 50kg | 20 | Rp72.000 | Rp0 | Rp1.440.000 |
| **Total Nilai** | | | | | **Rp7.340.000** |

### F.3 BK-20260506-0003 - 06 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BK-20260506-0003 |
| Tanggal | 06 Mei 2026 |
| Pelanggan / Tujuan | Hendra (Customer) |
| Kategori Keluar | Penjualan Eceran |
| Penjualan | Siti Nurhaliza |
| Status | Selesai |

**Item Barang Keluar:**

| No | Produk | Qty | Harga Satuan | Diskon | Subtotal |
|:--:|--------|:---:|:------------:|:-----:|:--------:|
| 1 | Cat Tembok Dulux Biru 5kg | 2 | Rp155.000 | Rp0 | Rp310.000 |
| 2 | Palu Konde 1kg | 1 | Rp38.000 | Rp0 | Rp38.000 |
| **Total Nilai** | | | | | **Rp348.000** |

### F.4 BK-20260508-0004 - 08 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BK-20260508-0004 |
| Tanggal | 08 Mei 2026 |
| Pelanggan / Tujuan | Proyek Renovasi Ruko |
| Kategori Keluar | Penjualan |
| Penjualan | Siti Nurhaliza |
| Status | Selesai |

**Item Barang Keluar:**

| No | Produk | Qty | Harga Satuan | Diskon | Subtotal |
|:--:|--------|:---:|:------------:|:-----:|:--------:|
| 1 | Keramik Roman 40x40 Putih | 80 | Rp55.000 | Rp0 | Rp4.400.000 |
| 2 | Semen Padang 50kg | 15 | Rp68.000 | Rp0 | Rp1.020.000 |
| 3 | Cat Tembok Avitex Putih 5kg | 5 | Rp110.000 | Rp0 | Rp550.000 |
| **Total Nilai** | | | | | **Rp5.970.000** |

### F.5 BK-20260512-0005 - 12 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BK-20260512-0005 |
| Tanggal | 12 Mei 2026 |
| Pelanggan / Tujuan | Supriyadi (Customer) |
| Kategori Keluar | Penjualan Eceran |
| Penjualan | Siti Nurhaliza |
| Status | Selesai |

**Item Barang Keluar:**

| No | Produk | Qty | Harga Satuan | Diskon | Subtotal |
|:--:|--------|:---:|:------------:|:-----:|:--------:|
| 1 | Pipa PVC 3 inch | 15 | Rp48.000 | Rp0 | Rp720.000 |
| 2 | Stop Kontak Broco | 5 | Rp18.000 | Rp0 | Rp90.000 |
| **Total Nilai** | | | | | **Rp810.000** |

### F.6 BK-20260518-0006 - 18 Mei 2026
| Data | Keterangan |
|------|-----------|
| No Transaksi | BK-20260518-0006 |
| Tanggal | 18 Mei 2026 |
| Pelanggan / Tujuan | Retur Barang (Rusak) |
| Kategori Keluar | Retur |
| Penjualan | Siti Nurhaliza |
| Catatan | Retur dari BM-20260507-0003 (Keramik rusak) |
| Status | Selesai |

**Item Barang Keluar:**

| No | Produk | Qty | Harga Satuan | Diskon | Subtotal |
|:--:|--------|:---:|:------------:|:-----:|:--------:|
| 1 | Keramik Roman 40x40 Putih | 3 | Rp42.000 | Rp0 | Rp126.000 |
| 2 | Keramik Asia Tile 50x50 | 1 | Rp55.000 | Rp0 | Rp55.000 |
| **Total Nilai** | | | | | **Rp181.000** |

---

## G. Data Transaksi — Purchase Order

### G.1 PO-20260428-0001
| Data | Keterangan |
|------|-----------|
| No PO | PO-20260428-0001 |
| Tanggal | 28 April 2026 |
| Supplier | PT. Semen Indonesia Tbk |
| Dibuat Oleh | Rina Kartika |
| Status | Approved (disetujui Hendra Gunawan) |
| Tipe Pembayaran | Net30 |
| Estimasi Datang | 05 Mei 2026 |

**Item PO:**

| No | Produk | Qty | Harga Satuan | Subtotal |
|:--:|--------|:---:|:------------:|:--------:|
| 1 | Semen Holcim 50kg | 60 | Rp58.000 | Rp3.480.000 |
| **Total Nilai** | | | | **Rp3.480.000** |

### G.2 PO-20260505-0002
| Data | Keterangan |
|------|-----------|
| No PO | PO-20260505-0002 |
| Tanggal | 05 Mei 2026 |
| Supplier | Toko Cat Pelangi |
| Dibuat Oleh | Rina Kartika |
| Status | Pending (menunggu approve) |
| Tipe Pembayaran | Net30 |
| Estimasi Datang | 15 Mei 2026 |

**Item PO:**

| No | Produk | Qty | Harga Satuan | Subtotal |
|:--:|--------|:---:|:------------:|:--------:|
| 1 | Cat Tembok Avitex Putih 5kg | 20 | Rp85.000 | Rp1.700.000 |
| 2 | Cat Kayu Nippon Paint 1kg | 15 | Rp45.000 | Rp675.000 |
| **Total Nilai** | | | | **Rp2.375.000** |

### G.3 PO-20260510-0003
| Data | Keterangan |
|------|-----------|
| No PO | PO-20260510-0003 |
| Tanggal | 10 Mei 2026 |
| Supplier | CV. Keramik Indah |
| Dibuat Oleh | Rina Kartika |
| Status | Approved (disetujui Hendra Gunawan) |
| Tipe Pembayaran | Net30 |
| Estimasi Datang | 20 Mei 2026 |

**Item PO:**

| No | Produk | Qty | Harga Satuan | Subtotal |
|:--:|--------|:---:|:------------:|:--------:|
| 1 | Keramik Asia Tile 50x50 | 100 | Rp55.000 | Rp5.500.000 |
| 2 | Keramik Mulia 25x40 | 50 | Rp38.000 | Rp1.900.000 |
| **Total Nilai** | | | | **Rp7.400.000** |

### G.4 PO-20260515-0004
| Data | Keterangan |
|------|-----------|
| No PO | PO-20260515-0004 |
| Tanggal | 15 Mei 2026 |
| Supplier | UD. Kayu Meranti |
| Dibuat Oleh | Rina Kartika |
| Status | Ditolak (oleh Hendra Gunawan) |
| Tipe Pembayaran | Cash |
| Estimasi Datang | 22 Mei 2026 |
| Catatan | Harga tidak sesuai dengan budget |

**Item PO:**

| No | Produk | Qty | Harga Satuan | Subtotal |
|:--:|--------|:---:|:------------:|:--------:|
| 1 | Kayu Borneo 6x12 | 50 | Rp195.000 | Rp9.750.000 |
| **Total Nilai** | | | | **Rp9.750.000** |

---

## H. Data Transaksi — Stok Opname

### H.1 SO-202604-0001 - April 2026
| Data | Keterangan |
|------|-----------|
| No Opname | SO-202604-0001 |
| Tanggal | 30 April 2026 |
| Periode | April 2026 |
| Dibuat Oleh | Ahmad Fauzi (Bagian Gudang) |
| Disetujui Oleh | Hendra Gunawan (Owner) |
| Status | Approved |
| Catatan | Opname rutin akhir bulan |

**Detail Opname:**

| No | Produk | Stok Sistem | Stok Fisik | Selisih | Harga Pokok | Nilai Selisih | Keterangan |
|:--:|--------|:-----------:|:----------:|:------:|:-----------:|:-------------:|------------|
| 1 | Semen Padang 50kg | 130 | 130 | 0 | Rp55.000 | Rp0 | Sesuai |
| 2 | Semen Holcim 50kg | 100 | 100 | 0 | Rp58.000 | Rp0 | Sesuai |
| 3 | Besi Beton 10mm | 105 | 103 | -2 | Rp85.000 | -Rp170.000 | Hilang saat bongkar |
| 4 | Besi Beton 12mm | 130 | 130 | 0 | Rp95.000 | Rp0 | Sesuai |
| 5 | Kayu Borneo 6x12 | 80 | 78 | -2 | Rp180.000 | -Rp360.000 | Penyusutan |
| 6 | Triplek 9mm | 55 | 55 | 0 | Rp95.000 | Rp0 | Sesuai |
| 7 | Kabel Listrik NYA 2.5mm | 65 | 65 | 0 | Rp120.000 | Rp0 | Sesuai |
| **Total Penyesuaian** | | | | | | **-Rp530.000** | |

### H.2 SO-202605-0002 - Mei 2026
| Data | Keterangan |
|------|-----------|
| No Opname | SO-202605-0002 |
| Tanggal | 29 Mei 2026 |
| Periode | Mei 2026 |
| Dibuat Oleh | Ahmad Fauzi (Bagian Gudang) |
| Disetujui Oleh | (Menunggu persetujuan) |
| Status | Pending Approval |
| Catatan | Opname akhir bulan Mei |

**Detail Opname (Sementara):**

| No | Produk | Stok Sistem | Stok Fisik | Selisih | Harga Pokok | Nilai Selisih | Keterangan |
|:--:|--------|:-----------:|:----------:|:------:|:-----------:|:-------------:|------------|
| 1 | Semen Padang 50kg | 150 | 150 | 0 | Rp55.000 | Rp0 | Sesuai |
| 2 | Besi Beton 10mm | 198 | 195 | -3 | Rp85.000 | -Rp255.000 | Penyusutan |
| 3 | Cat Tembok Avitex Putih 5kg | 40 | 38 | -2 | Rp85.000 | -Rp170.000 | Bocor saat penyimpanan |
| 4 | Keramik Roman 40x40 Putih | 277 | 275 | -2 | Rp42.000 | -Rp84.000 | Pecah saat dipindah |
| 5 | Pipa PVC 2 inch | 100 | 100 | 0 | Rp25.000 | Rp0 | Sesuai |
| **Total Penyesuaian Sementara** | | | | | | **-Rp509.000** | |

---

## I. Data Chat Internal

| No | Tanggal | Waktu | Dari (Pengirim) | Kepada (Penerima) | Isi Pesan |
|:--:|:-------:|:-----:|:----------------:|:-----------------:|-----------|
| 1 | 30-04-2026 | 08.30 | Ahmad Fauzi | Rina Kartika | "Stok Semen Padang tinggal 130 sak. Besi Beton 10mm ada selisih minus 2 setelah opname. Mohon info untuk pengadaan." |
| 2 | 30-04-2026 | 09.15 | Rina Kartika | Ahmad Fauzi | "Baik, saya catat. Untuk selisihnya nanti saya sesuaikan setelah opname di-approve Owner." |
| 3 | 02-05-2026 | 07.45 | Hendra Gunawan | Rina Kartika | "Tolong buat PO untuk Semen Holcim 60 sak ke PT Semen Indonesia. Stok mulai menipis." |
| 4 | 02-05-2026 | 08.00 | Rina Kartika | Hendra Gunawan | "Siap, Pak. Saya buat sekarang." |
| 5 | 02-05-2026 | 10.00 | Siti Nurhaliza | Budi Santoso | "Ada pengiriman 30 sak Semen Padang ke Proyek Perumahan Bumi Indah. Tolong disiapkan." |
| 6 | 02-05-2026 | 10.30 | Budi Santoso | Siti Nurhaliza | "Siap, sudah saya muat ke mobil. Estimasi sampai jam 12 siang." |
| 7 | 04-05-2026 | 09.00 | Siti Nurhaliza | Budi Santoso | "Pengiriman hari ini: Besi Beton 50 batang dan Semen Holcim 20 sak ke Masjid Al-Ikhlas. Tolong disiapkan." |
| 8 | 05-05-2026 | 11.00 | Rina Kartika | Hendra Gunawan | "PO untuk Semen Holcim sudah di-approve, Pak. Estimasi datang 5 hari lagi." |
| 9 | 05-05-2026 | 11.05 | Hendra Gunawan | Rina Kartika | "Baik, terima kasih." |
| 10 | 07-05-2026 | 13.00 | Ahmad Fauzi | Rina Kartika | "Barang dari CV. Keramik Indah sudah datang. Ada keramik pecah 3 dus Roman dan 1 dus Asia Tile." |
| 11 | 07-05-2026 | 13.15 | Rina Kartika | Ahmad Fauzi | "Catat saja sebagai retur, nanti kita buatkan barang keluar kategori Retur." |
| 12 | 08-05-2026 | 08.00 | Siti Nurhaliza | Budi Santoso | "Pengiriman ke Proyek Ruko: Keramik 80 dus, Semen 15 sak, Cat 5 pail. Tolong siapkan." |
| 13 | 10-05-2026 | 09.30 | Rina Kartika | Hendra Gunawan | "Pak, saya buat PO baru untuk Keramik Asia Tile dan Mulia ke CV. Keramik Indah. Mohon approve." |
| 14 | 10-05-2026 | 10.00 | Hendra Gunawan | Rina Kartika | "PO sudah saya approve." |
| 15 | 15-05-2026 | 14.00 | Rina Kartika | Hendra Gunawan | "Pak, PO ke UD. Kayu Meranti ditolak karena harga Rp195.000 per batang di luar anggaran." |
| 16 | 15-05-2026 | 14.05 | Hendra Gunawan | Rina Kartika | "Benar. Harga terlalu tinggi. Cari supplier lain atau negosiasi ulang." |
| 17 | 18-05-2026 | 10.00 | Siti Nurhaliza | Budi Santoso | "Hari ini tidak ada pengiriman. Ada retur keramik rusak dari barang masuk minggu lalu." |
| 18 | 20-05-2026 | 08.30 | Dewi Lestari | Rina Kartika | "Pembayaran dari Proyek Bumi Indah sudah masuk Rp2.040.000 via transfer." |
| 19 | 20-05-2026 | 09.00 | Rina Kartika | Dewi Lestari | "Baik, sudah saya catat." |
| 20 | 29-05-2026 | 07.00 | Hendra Gunawan | Semua Pengguna | "Selamat pagi. Mohon semua staf menyelesaikan laporan akhir bulan sebelum jam 12 siang." |

---

## J. Data Pembayaran

| No | Tanggal | Kasir | No Transaksi | Pelanggan | Metode | Jumlah Dibayar | Status |
|:--:|:-------:|:-----:|:------------:|-----------|:------:|:--------------:|:------:|
| 1 | 02-05-2026 | Dewi Lestari | BK-20260502-0001 | Proyek Perumahan Bumi Indah | Transfer BCA | Rp2.040.000 | Lunas |
| 2 | 04-05-2026 | Dewi Lestari | BK-20260504-0002 | Pembangunan Masjid Al-Ikhlas | Transfer Mandiri | Rp7.340.000 | Lunas |
| 3 | 06-05-2026 | Dewi Lestari | BK-20260506-0003 | Hendra (Customer) | Tunai | Rp348.000 | Lunas |
| 4 | 08-05-2026 | Dewi Lestari | BK-20260508-0004 | Proyek Renovasi Ruko | Transfer BRI | Rp5.970.000 | Lunas |
| 5 | 08-05-2026 | Dewi Lestari | BK-20260508-0004 | Proyek Renovasi Ruko | Tunai | Rp500.000 (DP) | Lunas |
| 6 | 12-05-2026 | Dewi Lestari | BK-20260512-0005 | Supriyadi (Customer) | Tunai | Rp810.000 | Lunas |
| 7 | 15-05-2026 | Dewi Lestari | - | PT. Semen Indonesia Tbk | Transfer BCA | Rp2.750.000 | Hutang (Net30) |
| 8 | 20-05-2026 | Dewi Lestari | - | CV. Keramik Indah | Transfer Mandiri | Rp11.800.000 | Hutang (Net30) |

---

## Rekapitulasi Total Data

| No | Kategori Data | Jumlah Data |
|:--:|--------------|:-----------:|
| 1 | User | 6 |
| 2 | Supplier | 6 |
| 3 | Kategori | 8 |
| 4 | Produk | 20 |
| 5 | Barang Masuk | 5 |
| 6 | Barang Keluar | 6 |
| 7 | Purchase Order | 4 |
| 8 | Stok Opname | 2 |
| 9 | Chat Internal | 20 |
| 10 | Pembayaran | 8 |
| **Total Keseluruhan** | | **±85 data** |
