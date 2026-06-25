# Use Case Diagram — Tabel Simbol & Definisi

---

## 1. Simbol-Simbol Use Case Diagram

| Simbol | Nama | Keterangan |
|--------|------|------------|
| ![Actor] | **Aktor** | Pengguna yang berinteraksi dengan sistem (Owner, Admin, Gudang, dll) |
| ![Use Case] | **Use Case** | Fungsionalitas atau fitur yang disediakan sistem |
| ![Association] | **Association** | Garis penghubung antara aktor dengan use case |
| ![System Boundary] | **System Boundary** | Batasan sistem (kotak yang memisahkan sistem dari aktor eksternal) |

---

## 2. Daftar Aktor

| No | Aktor | Simbol | Deskripsi |
|----|-------|--------|-----------|
| 1 | **Owner** | `(O)` | Pemilik usaha dengan akses penuh ke seluruh fitur sistem |
| 2 | **Admin** | `(A)` | Administrator dengan akses hampir penuh (kecuali pengaturan toko & AI) |
| 3 | **Gudang** | `(G)` | Petugas gudang yang mengelola stok, barang masuk, PO, dan opname |
| 4 | **Penjualan** | `(P)` | Petugas penjualan yang mencatat stok keluar |
| 5 | **Kasir** | `(K)` | Petugas kasir yang mencatat pembayaran |
| 6 | **Pengiriman** | `(IRM)` | Petugas pengiriman yang mengupdate status pengiriman |

---

## 3. Daftar Use Case

| No | Kode | Use Case | Deskripsi |
|----|------|----------|-----------|
| 1 | UC1 | Login / Logout | Autentikasi masuk dan keluar sistem |
| 2 | UC2 | Lihat Dashboard Analitik | Melihat ringkasan data, aset, grafik penjualan |
| 3 | UC3 | Lihat Advanced Analytics | Melihat laporan analitik lanjutan |
| 4 | UC4 | Kelola Produk (Tambah/Edit/Hapus) | CRUD data barang |
| 5 | UC5 | Lihat Daftar Produk | Melihat daftar seluruh produk |
| 6 | UC6 | Kelola Kategori (Tambah/Edit/Hapus) | CRUD kategori barang |
| 7 | UC7 | Lihat Kategori | Melihat daftar kategori |
| 8 | UC8 | Kelola Satuan (Tambah/Edit/Hapus) | CRUD satuan barang |
| 9 | UC9 | Lihat Satuan | Melihat daftar satuan |
| 10 | UC10 | Kelola Supplier (Tambah/Edit/Hapus) | CRUD data supplier |
| 11 | UC11 | Lihat Supplier | Melihat daftar supplier |
| 12 | UC12 | Input Stok Masuk | Mencatat penerimaan barang dari supplier |
| 13 | UC13 | Lihat Stok Masuk | Melihat riwayat stok masuk |
| 14 | UC14 | Input Stok Keluar | Mencatat penjualan/pengeluaran barang |
| 15 | UC15 | Lihat Stok Keluar | Melihat riwayat stok keluar |
| 16 | UC16 | Buat Draft Purchase Order | Membuat draft PO untuk pemesanan barang |
| 17 | UC17 | Approve / Tolak PO | Menyetujui atau menolak draft PO |
| 18 | UC18 | Lihat Purchase Order | Melihat daftar PO |
| 19 | UC19 | Buat Draft Stock Opname | Membuat draft opname stok |
| 20 | UC20 | Approve / Reject Stock Opname | Menyetujui atau menolak hasil opname |
| 21 | UC21 | Lihat Riwayat Stock Opname | Melihat riwayat opname stok |
| 22 | UC22 | Catat Pembayaran | Mencatat transaksi pembayaran |
| 23 | UC23 | Lihat Pembayaran | Melihat riwayat pembayaran |
| 24 | UC24 | Update Status Pengiriman | Memperbarui status pengiriman barang |
| 25 | UC25 | Lihat Pengiriman | Melihat daftar pengiriman |
| 26 | UC26 | Kelola Pengguna (Tambah/Edit/Hapus) | CRUD data karyawan/pengguna sistem |
| 27 | UC27 | Lihat Audit Log | Melihat log aktivitas sistem |
| 28 | UC28 | Kelola Pengaturan Toko & AI Config | Mengatur profil toko, kop surat, dan konfigurasi AI |
| 29 | UC29 | Selesaikan Hutang / Rusak | Menandai selesai hutang atau barang rusak |
| 30 | UC30 | Lihat & Ekspor Laporan | Melihat dan mengekspor laporan ke Excel/PDF |
| 31 | UC31 | Chat Internal | Berkomunikasi via pesan internal antar pengguna |
| 32 | UC32 | Pencarian Global | Mencari data produk, transaksi, dll secara global |
| 33 | UC33 | Notifikasi Stok Menipis | Melihat daftar produk dengan stok di bawah batas reorder point (widget Dashboard & badge di Persediaan) |

---

## 4. Matriks Hak Akses Aktor vs Use Case

| No | Use Case | Owner | Admin | Gudang | Penjualan | Kasir | Pengiriman |
|----|----------|:-----:|:-----:|:------:|:---------:|:-----:|:----------:|
| 1 | UC1 — Login / Logout | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 2 | UC2 — Lihat Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 3 | UC3 — Advanced Analytics | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 4 | UC4 — Kelola Produk | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 5 | UC5 — Lihat Produk | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| 6 | UC6 — Kelola Kategori | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 7 | UC7 — Lihat Kategori | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| 8 | UC8 — Kelola Satuan | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 9 | UC9 — Lihat Satuan | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 10 | UC10 — Kelola Supplier | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 11 | UC11 — Lihat Supplier | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 12 | UC12 — Input Stok Masuk | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 13 | UC13 — Lihat Stok Masuk | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 14 | UC14 — Input Stok Keluar | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| 15 | UC15 — Lihat Stok Keluar | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| 16 | UC16 — Buat Draft PO | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 17 | UC17 — Approve / Tolak PO | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 18 | UC18 — Lihat PO | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 19 | UC19 — Buat Draft Opname | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 20 | UC20 — Approve / Reject Opname | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 21 | UC21 — Lihat Opname | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| 22 | UC22 — Catat Pembayaran | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| 23 | UC23 — Lihat Pembayaran | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| 24 | UC24 — Update Status Kirim | ✅ | ❌ | ✅ | ❌ | ❌ | ✅ |
| 25 | UC25 — Lihat Pengiriman | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ |
| 26 | UC26 — Kelola Pengguna | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 27 | UC27 — Audit Log | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 28 | UC28 — Pengaturan Toko & AI | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| 29 | UC29 — Selesaikan Hutang/Rusak | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 30 | UC30 — Laporan & Ekspor | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| 31 | UC31 — Chat Internal | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 32 | UC32 — Pencarian Global | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 33 | UC33 — Notifikasi Stok Menipis | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

**Keterangan:**
- ✅ = Memiliki akses
- ❌ = Tidak memiliki akses

**Relasi (Association):** `Aktor --- Use Case` — garis lurus yang menghubungkan aktor dengan use case yang dapat diaksesnya.
