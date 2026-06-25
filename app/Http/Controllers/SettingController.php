<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\StoreProfile;
use App\Models\EmailSetting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // Menampilkan halaman pengaturan (Identitas Aplikasi & Daftar Profil Cabang)
    public function index()
    {
        // Mengurutkan berdasarkan ID (urutan pembuatan)
        $profiles = StoreProfile::orderBy('id', 'asc')->get();
        $emailSetting = EmailSetting::getSettings();
        
        return view('pengaturan', compact('profiles', 'emailSetting'));
    }

    // Menampilkan halaman Audit Log
    public function auditLog(Request $request)
    {
        $query = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('modul') && $request->modul != 'Semua Modul') {
            $query->where('module', $request->modul);
        }

        if ($request->filled('aktor')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->aktor . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('audit_log', compact('logs'));
    }

    // =========================================================
    // FUNGSI UNTUK UPDATE LOGO & NAMA SIDEBAR APLIKASI
    // =========================================================
    public function updateAplikasi(Request $request)
    {
        $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'tagline_aplikasi' => 'nullable|string|max:255',
            'logo_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 1. Tangani Upload Logo (Akan selalu ditimpa menjadi 'logo-utama.png')
        if ($request->hasFile('logo_utama')) {
            $file = $request->file('logo_utama');
            // PERBAIKAN: Gunakan disk 'public' agar tidak terjadi folder inception
            $file->storeAs('logos', 'logo-utama.png', 'public');
        }

        // 2. Simpan ke file config/aplikasi.php (AMAN - tidak pakai regex pada .env)
        $configPath = config_path('aplikasi.php');
        $configData = [
            'nama_aplikasi' => $request->nama_aplikasi,
            'tagline_aplikasi' => $request->filled('tagline_aplikasi') ? $request->tagline_aplikasi : env('APP_TAGLINE', 'Sistem Manajemen Inventaris'),
        ];

        $configContent = '<?php' . "\n\nreturn " . var_export($configData, true) . ";\n";
        file_put_contents($configPath, $configContent);

        // 3. Set runtime config agar langsung berlaku tanpa cache ulang
        config(['aplikasi.nama_aplikasi' => $request->nama_aplikasi]);
        if ($request->filled('tagline_aplikasi')) {
            config(['aplikasi.tagline_aplikasi' => $request->tagline_aplikasi]);
        }

        return redirect()->back()->with('success', 'Identitas Aplikasi & Sidebar berhasil diperbarui!');
    }

    // =========================================================
    // FUNGSI UNTUK CRUD PROFIL CABANG (KOP SURAT)
    // =========================================================

    // Menyimpan profil cabang baru
    public function store(StoreSettingRequest $request)
    {
        $data = $request->except(['logo']);

        // Logika Upload Foto Logo
        if ($request->hasFile('logo')) {
            $fileName = time() . '_' . str_replace(' ', '_', $request->file('logo')->getClientOriginalName());
            // PERBAIKAN: Gunakan disk 'public'
            $request->file('logo')->storeAs('logos', $fileName, 'public');
            $data['logo'] = $fileName;
        }

        // Karena fitur "is_active" sudah tidak relevan (semua cabang kedudukannya sama untuk laporan)
        // kita set default is_active false atau null jika kolomnya masih ada di database
        $data['is_active'] = false; 

        StoreProfile::create($data);

        return redirect()->back()->with('success', 'Profil Kop Surat baru berhasil ditambahkan!');
    }

    // Mengupdate profil cabang yang sudah ada
    public function update(UpdateSettingRequest $request, $id)
    {
        $profile = StoreProfile::findOrFail($id);

        $data = $request->except(['logo']);

        // Logika Ganti Foto Logo
        if ($request->hasFile('logo')) {
            // PERBAIKAN: Cek dan Hapus logo lama dari server menggunakan disk 'public'
            if ($profile->logo && Storage::disk('public')->exists('logos/' . $profile->logo)) {
                Storage::disk('public')->delete('logos/' . $profile->logo);
            }
            
            // Upload logo baru
            $fileName = time() . '_' . str_replace(' ', '_', $request->file('logo')->getClientOriginalName());
            $request->file('logo')->storeAs('logos', $fileName, 'public');
            $data['logo'] = $fileName;
        }

        $profile->update($data);

        return redirect()->back()->with('success', 'Profil Kop Surat berhasil diperbarui!');
    }

    // Menghapus profil cabang
    public function destroy($id)
    {
        $profile = StoreProfile::findOrFail($id);
        
        // PERBAIKAN: Hapus file fisik logo dari folder Storage menggunakan disk 'public'
        if ($profile->logo && Storage::disk('public')->exists('logos/' . $profile->logo)) {
            Storage::disk('public')->delete('logos/' . $profile->logo);
        }

        $profile->delete();
        return redirect()->back()->with('success', 'Profil Kop Surat berhasil dihapus.');
    }

    // Mengaktifkan profil cabang tertentu
    public function setActive($id)
    {
        $profile = StoreProfile::findOrFail($id);
        
        // Reset semua profil menjadi tidak aktif
        StoreProfile::query()->update(['is_active' => false]);
        
        // Set profil yang dipilih menjadi aktif
        $profile->update(['is_active' => true]);
        
        return response()->json(['success' => true, 'message' => 'Profil berhasil diaktifkan.']);
    }

    // =========================================================
    // FUNGSI UNTUK PENGATURAN EMAIL NOTIFIKASI
    // =========================================================
    public function updateEmailSettings(Request $request)
    {
        $setting = EmailSetting::getSettings();
        if (!$setting) {
            $setting = new EmailSetting();
        }

        $data = $request->except(['_token', '_method', 'logo_email']);

        if ($request->hasFile('logo_email')) {
            if ($setting->logo && Storage::disk('public')->exists('logos/' . $setting->logo)) {
                Storage::disk('public')->delete('logos/' . $setting->logo);
            }
            
            $fileName = time() . '_email_' . str_replace(' ', '_', $request->file('logo_email')->getClientOriginalName());
            $request->file('logo_email')->storeAs('logos', $fileName, 'public');
            $data['logo'] = $fileName;
        }

        $setting->fill($data);
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan template Email berhasil diperbarui!');
    }
}