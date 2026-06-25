# Panduan Deployment Laravel ke Coolify

Panduan ini berisi langkah-langkah untuk melakukan *deploy* aplikasi `TbMitraUsaha2` ke VPS yang menggunakan **Coolify**.

## Prasyarat
1. VPS sudah terinstall Coolify dan Anda memiliki akses admin ke dashboard Coolify.
2. Domain/Subdomain sudah diarahkan (A record) ke IP Address VPS Anda.
3. Repository kode sudah berada di GitHub (`https://github.com/dalvinutama/mitrausaha2_pos.git`).

---

## Langkah-Langkah Deployment

### 1. Hubungkan GitHub ke Coolify (Jika Belum)
1. Buka dashboard Coolify Anda.
2. Pergi ke menu **Sources** (atau *Git Sources*).
3. Klik **+ Add** dan pilih **GitHub**.
4. Ikuti instruksi autentikasi (instal *Coolify GitHub App* di akun Anda) lalu berikan akses spesifik ke repository `mitrausaha2_pos`.

### 2. Buat Project & Environment
1. Buka menu **Projects** di sidebar kiri.
2. Klik **+ Add** untuk membuat project baru (misal beri nama `TbMitraUsaha`).
3. Pilih environment yang tersedia, secara default Anda akan diarahkan ke environment `Production`.

### 3. Tambahkan Resource (Aplikasi)
1. Di dalam dashboard project tersebut, klik tombol **+ Add Resource**.
2. Pilih sumber kode: klik **Private Repository** (via GitHub App) atau **Public Repository** (jika status repo Anda public).
3. Pilih repository `dalvinutama/mitrausaha2_pos` dan pilih branch `main`.
4. Pilih metode deployment. Karena kita sudah menyiapkan konfigurasi lengkap, sangat disarankan untuk memilih tipe **Docker Compose**. Ini akan menjalankan aplikasi Laravel (`app`) beserta databasenya (`db`) dalam satu kesatuan.

### 4. Konfigurasi Environment Variables (.env)
Aplikasi Laravel membutuhkan *environment variables*. File `.env` tidak di-*push* ke GitHub, sehingga Anda harus memasukkannya di Coolify.
1. Masuk ke halaman *Resource* yang baru saja dibuat.
2. Buka tab **Environment Variables**.
3. Gunakan mode *Advanced/Text Editor* untuk menempelkan isi file `.env` lokal Anda.
4. **Variabel Penting yang WAJIB disesuaikan untuk Production:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.com  # Ganti dengan domain asli
   
   # Konfigurasi Database (Sesuaikan dengan yang ada di docker-compose.yml)
   DB_CONNECTION=mysql
   DB_HOST=db                       # PENTING: Gunakan nama service 'db' sesuai compose file
   DB_PORT=3306
   DB_DATABASE=mitrausaha
   DB_USERNAME=root
   DB_PASSWORD=secret               # Ganti dengan password yang lebih kuat
   ```
5. Simpan pengaturan (*Save*).

### 5. Konfigurasi Domain & HTTPS
1. Buka tab **General** atau **Configuration**.
2. Pada bagian *Domains*, masukkan nama domain atau subdomain untuk aplikasi Anda.
   *(Contoh: `https://pos.mitrausaha.com` atau `https://mitrausaha.domain.com`)*
3. Coolify akan secara otomatis mencoba men-generate sertifikat SSL (HTTPS) menggunakan Let's Encrypt saat aplikasi di-*deploy*.

### 6. Mulai Deployment
1. Di pojok kanan atas, klik tombol **Deploy**.
2. Buka tab **Deployments** / **Logs** untuk memantau prosesnya.
3. Proses ini akan menjalankan `Dockerfile`:
   - Mengunduh library Node.js & mem-build aset Vite.
   - Mengunduh library PHP via Composer.
   - Menyiapkan environment Apache dan PHP 8.2.
   - Membangun container database.

### 7. Post-Deployment (Otomatis & Manual)
1. Karena kita telah menyiapkan `docker/entrypoint.sh`, ketika container `app` selesai dibangun dan berjalan, sistem akan **secara otomatis**:
   - Menjalankan `php artisan migrate --force`.
   - Mengoptimalkan *cache* route, view, dan config.
2. **(Opsional) Menjalankan Seeder:** Jika Anda ingin mengisi data awal ke database yang baru, Anda bisa mengeksekusi seeder secara manual.
   - Buka tab **Terminal** pada *resource* Coolify.
   - Jalankan perintah:
     ```bash
     php artisan db:seed --force
     ```

---

## Troubleshooting

- **500 Server Error:** Periksa tab *Logs* di Coolify. Biasanya disebabkan oleh kredensial database yang salah di Environment Variables. Pastikan `DB_HOST` bernilai `db`.
- **Aset (CSS/JS) tidak termuat:** Pastikan nilai `APP_URL` di *Environment Variables* sudah sama persis dengan URL domain HTTPS Anda dan jangan menggunakan `/` di akhir URL.
- **Tampilan Putih Blank:** Pastikan perintah migrasi sudah berjalan sempurna. Jika ragu, jalankan `php artisan migrate --force` lewat Terminal Coolify.
