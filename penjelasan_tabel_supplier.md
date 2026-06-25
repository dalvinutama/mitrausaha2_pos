### Tabel 5.16 Spesifikasi Tabel Supplier

Tabel `suppliers` digunakan untuk menyimpan data seluruh pemasok barang (supplier) yang bekerja sama dengan toko. Tabel ini menjadi referensi utama dalam proses pengadaan barang, seperti pembuatan Purchase Order (PO) dan pencatatan Stok Masuk. Berikut adalah penjelasan masing-masing atribut:

1. **id** — Primary key yang secara otomatis di-generate oleh sistem sebagai identitas unik setiap supplier.

2. **nama_supplier** — Nama perusahaan atau perorangan supplier. Diisi dengan tipe data `Varchar(150)` karena panjang nama supplier umumnya tidak lebih dari 150 karakter.

3. **alamat** — Alamat lengkap supplier. Menggunakan tipe `Text` karena alamat bisa mencakup jalan, kelurahan, kecamatan, kota, dan provinsi yang memerlukan ruang penyimpanan lebih besar.

4. **nama_pic** — Nama lengkap *Person In Charge* (PIC) atau kontak person dari pihak supplier. Tipe `Varchar(75)` sudah cukup untuk menampung nama orang.

5. **no_hp** — Nomor telepon seluler PIC supplier dengan panjang maksimal 20 karakter, mencakup kode negara dan nomor utama.

6. **email** — Alamat email supplier (opsional). Tipe `Varchar(255)` mengikuti standar panjang maksimal alamat email global yaitu 254 karakter.

7. **kategori_suplai** — Jenis barang yang disuplai oleh supplier, misalnya "Elektronik", "Bahan Baku", atau "ATK". Bersifat opsional dengan tipe `Varchar(100)`.

8. **termin_default** — Ketentuan pembayaran standar yang digunakan saat bertransaksi dengan supplier ini. Nilai *default*-nya adalah "cash", namun dapat diisi dengan termin lain seperti "30 hari" atau "60 hari". Tipe `Varchar(50)` sudah mencukupi karena isinya berupa teks pendek.

9. **nama_bank** — Nama bank yang digunakan untuk pembayaran kepada supplier (opsional). Tipe `Varchar(100)` cukup untuk menampung nama bank di Indonesia.

10. **no_rekening** — Nomor rekening bank supplier (opsional). Tipe `Varchar(30)` digunakan karena nomor rekening bank di Indonesia umumnya berkisar antara 10 hingga 24 digit.

11. **catatan** — Catatan tambahan mengenai supplier, misalnya informasi khusus atau riwayat kerjasama. Menggunakan tipe `Text` karena dapat diisi dengan teks yang panjang.

12. **status** — Status keaktifan supplier, secara *default* bernilai "Aktif". Jika suatu saat supplier tidak lagi bekerja sama, status dapat diubah menjadi "Nonaktif". Tipe `Varchar(20)` sudah memadai karena hanya berisi teks pendek.

13. **created_at** — *Timestamp* yang mencatat kapan data supplier pertama kali dibuat.

14. **updated_at** — *Timestamp* yang mencatat kapan data supplier terakhir kali diperbarui.

Kedua atribut `created_at` dan `updated_at` dikelola secara otomatis oleh Laravel melalui fitur *timestamps* bawaan Eloquent ORM.
