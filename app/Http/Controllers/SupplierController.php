<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Models\Transaction; // Wajib diaktifkan untuk menghitung metrik dinamis
use Carbon\Carbon;

class SupplierController extends Controller
{
    /**
     * 1. Menampilkan Halaman Direktori Supplier (READ)
     */
    public function index()
    {
        // A. Ambil semua data dari tabel suppliers urut dari yang paling baru
        $suppliers = Supplier::latest()->get();

        // B. Hitung total metrik untuk Kartu Ringkasan
        $totalSupplier = Supplier::count();
        
        // Waktu saat ini
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // C. Tarik Data Pengiriman (Barang Masuk) Bulan Ini yang Nyata
        try {
            $pengirimanBulanIni = Transaction::where('jenis_transaksi', 'masuk')
                ->whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->count();
        } catch (\Exception $e) {
            $pengirimanBulanIni = 0; // Jaga-jaga jika tabel transactions belum ada/error
        }

        // D. Tarik Data Estimasi Hutang yang Nyata (Otomatis 0 jika belum ada data)
        try {
            $totalHutang = Transaction::where('jenis_transaksi', 'masuk')
                ->where(function($query) {
                    $query->where('status_pembayaran', 'Tempo')
                          ->orWhere('status_pembayaran', 'Belum Lunas');
                })->sum('total_nilai'); // Sesuaikan 'total_nilai' dengan kolom harga di tabelmu
        } catch (\Exception $e) {
            $totalHutang = 0; 
        }

        // E. Kirim data ke tampilan (View)
        return view('supplier', compact('suppliers', 'totalSupplier', 'pengirimanBulanIni', 'totalHutang'));
    }

    /**
     * 2. Menyimpan Data Supplier Baru ke Database (CREATE)
     */
    public function store(StoreSupplierRequest $request)
    {
        // B. Eksekusi Simpan ke Database
        Supplier::create([
            'nama_supplier'   => $request->nama_supplier,
            'alamat'          => $request->alamat,
            'nama_pic'        => $request->nama_pic,
            'no_hp'           => $request->no_hp,
            'email'           => $request->email,
            'kategori_suplai' => $request->kategori_suplai,
            'termin_default'  => $request->termin_type === 'kredit' ? $request->termin_number : 'cash',
            'nama_bank'       => $request->nama_bank,
            'no_rekening'     => $request->no_rekening,
            'catatan'         => $request->catatan,
            'status'          => 'Aktif', // Default saat baru ditambah pasti Aktif
        ]);

        // C. Kembali ke halaman tadi dengan pesan sukses
        return redirect()->back()->with('success', 'Data Supplier berhasil ditambahkan!');
    }

    /**
     * 3. Memperbarui Data Supplier (UPDATE)
     */
    public function update(UpdateSupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $data = $request->all();
        if ($request->has('termin_type')) {
            $data['termin_default'] = $request->termin_type === 'kredit' ? $request->termin_number : 'cash';
        }
        
        $supplier->update($data);

        return redirect()->back()->with('success', 'Data Supplier berhasil diperbarui!');
    }

    /**
     * 4. Menghapus Data Supplier (DELETE)
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Opsional: Cek dulu apakah supplier ini sedang dipakai di tabel transaksi
        // Jika ya, lebih baik di-nonaktifkan statusnya, bukan dihapus permanen.
        
        $supplier->delete();

        return redirect()->back()->with('success', 'Data Supplier berhasil dihapus!');
    }
}