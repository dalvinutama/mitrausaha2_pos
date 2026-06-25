<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockOpname;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $period = $request->query('period', 'this_month');

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        switch ($period) {
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'all_time':
                $startDate = Carbon::create(2000, 1, 1);
                $endDate = Carbon::now()->endOfDay();
                break;
        }

        [$totalAset, $detailAset] = $this->dashboardService->getAset();
        [$barangRusak, $detailRusak] = $this->dashboardService->getBarangRusak($startDate, $endDate);
        [$poAktif, $detailPO] = $this->dashboardService->getPoAktif($startDate, $endDate);
        [$hutangTempo, $detailHutang] = $this->dashboardService->getHutang();

        $transaksiTerbaru = Transaction::whereIn('jenis_transaksi', ['masuk', 'keluar'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')->take(5)->get();

        $kategoriTerlaris = $this->dashboardService->getKategoriTerlaris($startDate, $endDate);

        $deadStock = Product::where('stok', '>', 0)
            ->where('updated_at', '<', Carbon::now()->subMonths(6))
            ->orderBy('updated_at', 'asc')->take(5)->get();

        $trxHariIni = Transaction::whereIn('jenis_transaksi', ['masuk', 'keluar'])
            ->whereDate('tanggal', Carbon::today())->count();

        $qtyKeluarHariIni = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.jenis_transaksi', 'keluar')
            ->whereDate('transactions.tanggal', Carbon::today())
            ->sum('transaction_items.qty');

        [$chartDataSets, $chartMingguan, $chartBulanan] = $this->dashboardService->getChartData();

        $stokMenipis = Product::where('stok', '<', 5)->take(3)->get();

        $stockOpnameList = collect([]);
        if (\Illuminate\Support\Facades\Schema::hasTable('stock_opnames')) {
            $stockOpnameList = StockOpname::with('pembuat')
                ->orderBy('created_at', 'desc')->take(5)->get()
                ->map(fn($o) => tap($o, fn($o) => $o->user = $o->pembuat));
        }

        return view('dashboard', compact(
            'totalAset', 'detailAset',
            'barangRusak', 'detailRusak',
            'poAktif', 'detailPO',
            'hutangTempo', 'detailHutang',
            'transaksiTerbaru',
            'chartMingguan', 'chartBulanan', 'chartDataSets',
            'stokMenipis', 'kategoriTerlaris',
            'deadStock', 'trxHariIni', 'qtyKeluarHariIni',
            'stockOpnameList'
        ));
    }

    public function selesaiHutang($id)
    {
        $trx = Transaction::findOrFail($id);
        $catatanBaru = preg_replace('/\[Pembayaran TEMPO[^\]]+\]/', '[HUTANG LUNAS]', $trx->catatan);
        $trx->update(['catatan' => trim($catatanBaru)]);
        return redirect()->back()->with('success', 'Hutang Faktur ' . $trx->no_transaksi . ' berhasil ditandai Lunas.');
    }

    public function selesaiRusak($id)
    {
        $trx = Transaction::findOrFail($id);
        $trx->update(['catatan' => trim($trx->catatan) . ' [RETUR SELESAI]']);
        return redirect()->back()->with('success', 'Barang Retur/Rusak (' . $trx->no_transaksi . ') berhasil ditandai selesai.');
    }

    public function terimaPengganti($id)
    {
        DB::beginTransaction();
        try {
            $trxRusak = Transaction::with('items.product')->findOrFail($id);
            $noTransaksi = 'BM-GANTI-' . date('Ymd') . '-' . rand(100, 999);
            $trxMasuk = Transaction::create([
                'no_transaksi'    => $noTransaksi,
                'jenis_transaksi' => 'masuk',
                'tanggal'         => Carbon::now()->toDateString(),
                'user_id'         => Auth::id(),
                'catatan'         => "Penerimaan Barang Pengganti dari retur: " . $trxRusak->no_transaksi,
                'status'          => 'selesai',
                'total_nilai'     => 0,
            ]);

            foreach ($trxRusak->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->qty);
                    TransactionItem::create([
                        'transaction_id' => $trxMasuk->id,
                        'product_id'     => $item->product_id,
                        'qty'            => $item->qty,
                        'harga_satuan'   => 0,
                        'subtotal'       => 0,
                    ]);
                }
            }

            $trxRusak->update(['catatan' => trim($trxRusak->catatan) . ' [RETUR SELESAI]']);
            DB::commit();
            return redirect()->back()->with('success', 'Barang Pengganti berhasil diterima dan masuk ke stok gudang otomatis!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Barang Pengganti Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses penerimaan barang pengganti. Silakan coba lagi.');
        }
    }

    public function walkthrough()
    {
        return view('panduan');
    }
}
