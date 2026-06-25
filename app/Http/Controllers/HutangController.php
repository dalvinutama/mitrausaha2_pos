<?php

namespace App\Http\Controllers;

use App\Http\Requests\BayarCicilanRequest;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
{
    public function index()
    {
        // Hanya bisa diakses admin/owner (bisa dihandle via middleware di web.php)
        
        $hutangAktif = Transaction::with(['supplier', 'payments'])
            ->where('jenis_transaksi', 'masuk')
            ->where('tipe_pembayaran', 'tempo')
            ->where('status_pembayaran', '!=', 'lunas')
            ->orderBy('created_at', 'desc')
            ->get();

        $hutangLunas = Transaction::with(['supplier', 'payments'])
            ->where('jenis_transaksi', 'masuk')
            ->where('tipe_pembayaran', 'tempo')
            ->where('status_pembayaran', 'lunas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hutang.index', compact('hutangAktif', 'hutangLunas'));
    }

    public function bayarCicilan(BayarCicilanRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaction::findOrFail($id);
            
            // Hitung total bayar sebelumnya
            $sudah_bayar = $transaksi->payments()->sum('nominal');
            $sisa_hutang = $transaksi->total_nilai - $sudah_bayar;

            if ($request->nominal > $sisa_hutang) {
                return redirect()->back()->with('error', 'Nominal bayar melebihi sisa hutang!');
            }

            // Upload Bukti
            $bukti_path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = 'hutang_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/payments'), $filename);
                $bukti_path = 'uploads/payments/' . $filename;
            }

            TransactionPayment::create([
                'transaction_id' => $transaksi->id,
                'nominal' => $request->nominal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $bukti_path,
                'tanggal_bayar' => date('Y-m-d'),
                'user_id' => Auth::id(),
            ]);

            // Cek lunas
            $sudah_bayar_baru = $transaksi->payments()->sum('nominal');
            if ($sudah_bayar_baru >= $transaksi->total_nilai) {
                $transaksi->update(['status_pembayaran' => 'lunas']);
                
                // Hapus tulisan [Pembayaran TEMPO...] dari catatan agar alarm mati
                $catatan = preg_replace('/\[Pembayaran TEMPO.*?\]/i', '[LUNAS]', $transaksi->catatan);
                $transaksi->update(['catatan' => $catatan]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Pembayaran Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencatat pembayaran. Silakan coba lagi.');
        }
    }

    public function batalkanPembayaran($id)
    {
        // Hanya owner yang boleh (bisa via middleware atau cek manual)
        if (Auth::user()->role !== 'owner') {
            return redirect()->back()->with('error', 'Hanya Owner yang dapat membatalkan pembayaran.');
        }

        DB::beginTransaction();
        try {
            $payment = TransactionPayment::findOrFail($id);
            $transaksi = Transaction::findOrFail($payment->transaction_id);

            // Hapus file gambar jika ada
            if ($payment->bukti_pembayaran && file_exists(public_path($payment->bukti_pembayaran))) {
                unlink(public_path($payment->bukti_pembayaran));
            }

            $payment->delete();

            // Kembalikan status jika ternyata jadi belum lunas
            $sudah_bayar = $transaksi->payments()->sum('nominal');
            if ($sudah_bayar < $transaksi->total_nilai) {
                $transaksi->update([
                    'status_pembayaran' => 'belum_lunas'
                ]);
                
                // Kembalikan catatan jika perlu (walau sulit kalau sudah ketimpa LUNAS),
                // Kita ganti lagi jadi TEMPO
                if (strpos($transaksi->catatan, '[LUNAS]') !== false) {
                    $catatan = str_replace('[LUNAS]', '[Pembayaran TEMPO (VOID)]', $transaksi->catatan);
                    $transaksi->update(['catatan' => $catatan]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Riwayat pembayaran dibatalkan (VOID).');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Batalkan Pembayaran Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membatalkan pembayaran. Silakan coba lagi.');
        }
    }

    public function printPayment($id)
    {
        $payment = TransactionPayment::with(['transaction.supplier', 'user'])->findOrFail($id);
        
        // Pastikan ini adalah pembayaran untuk transaksi masuk (Hutang)
        if ($payment->transaction->jenis_transaksi !== 'masuk') {
            abort(404);
        }

        return view('hutang.print_payment', compact('payment'));
    }
}
