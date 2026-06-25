<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreProfile;
use App\Models\TransactionItem;
use App\Models\StockOpnameDetail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

// Panggil MESIN ASLI Excel-nya langsung!
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanController extends Controller
{
    /**
     * FUNGSI HELPER: Menarik dan Menghitung Data Mutasi + Integrasi Opname
     */
    private function getLaporanData(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $kategori  = $request->kategori ?? null;

        $query = Product::query();
        if ($kategori) {
            $query->where('kategori_id', $kategori);
        }

        $laporan = $query->get()->map(function ($barang) use ($startDate, $endDate) {
            $currentStok = $barang->stok;
            $hpp = $barang->harga_beli ?? 0;

            // =========================================================
            // A. HITUNG STOK AWAL (Reverse Calculation)
            // =========================================================
            $masukSejakAwal = TransactionItem::where('product_id', $barang->id)
                ->whereHas('transaction', function($q) use ($startDate) {
                    $q->where('jenis_transaksi', 'masuk')->whereDate('tanggal', '>=', $startDate);
                })->sum('qty');

            $keluarSejakAwal = TransactionItem::where('product_id', $barang->id)
                ->whereHas('transaction', function($q) use ($startDate) {
                    $q->where('jenis_transaksi', 'keluar')->whereDate('tanggal', '>=', $startDate);
                })->sum('qty');

            $stokAwal = $currentStok - $masukSejakAwal + $keluarSejakAwal;

            // =========================================================
            // B. HITUNG MUTASI PERIODE INI (MURNI TANPA PENYESUAIAN OPNAME)
            // =========================================================
            $masukSekarang = TransactionItem::where('product_id', $barang->id)
                ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->where('jenis_transaksi', 'masuk')
                      ->whereBetween('tanggal', [$startDate, $endDate])
                      ->where(function($query) {
                          $query->whereNull('kategori_keluar')
                                ->orWhere('kategori_keluar', '!=', 'Stock Opname');
                      });
                })->sum('qty');

            $keluarSekarang = TransactionItem::where('product_id', $barang->id)
                ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                    $q->where('jenis_transaksi', 'keluar')
                      ->whereBetween('tanggal', [$startDate, $endDate])
                      ->where(function($query) {
                          $query->whereNull('kategori_keluar')
                                ->orWhere('kategori_keluar', '!=', 'Stock Opname');
                      });
                })->sum('qty');

            $stokSistemAkhir = $stokAwal + $masukSekarang - $keluarSekarang;

            // =========================================================
            // C. TARIK DATA FISIK (JIKA ADA STOCK OPNAME YANG APPROVED)
            // =========================================================
            $opnameDetail = StockOpnameDetail::where('product_id', $barang->id)
                ->whereHas('stockOpname', function($q) use ($startDate, $endDate) {
                    $q->where('status', 'approved')
                      ->whereBetween('tanggal', [$startDate, $endDate]);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            $stokFisik = $opnameDetail ? $opnameDetail->stok_fisik : $stokSistemAkhir;

            return (object) [
                'kode'       => $barang->barcode ?? $barang->sku,
                'nama'       => $barang->nama_barang,
                'satuan'     => $barang->satuan,
                'kategori'   => $barang->kategori ? $barang->kategori->nama_kategori : '-',
                'harga_pokok'=> $hpp,
                
                'stok_awal'  => $stokAwal,
                'masuk'      => $masukSekarang,
                'keluar'     => $keluarSekarang,
                'stok_akhir' => $stokSistemAkhir,
                'stok_fisik' => $stokFisik,
                
                'status'     => $stokSistemAkhir <= ($barang->stok_minimum ?? 5) ? 'Reorder' : 'Aman'
            ];
        });

        return compact('laporan', 'startDate', 'endDate', 'kategori');
    }

    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('laporan', $data);
    }

    /**
     * EXPORT EXCEL LANGSUNG DENGAN PHPSPREADSHEET (TWO-TIER HEADER & COLOR BANDING)
     */
    public function exportExcel(Request $request)
    {
        $data = $this->getLaporanData($request);
        $laporan = $data['laporan'];
        
        // Membentuk Teks Periode yang Cantik (Misal: 01 Mei 2026 s/d 31 Mei 2026)
        $teksPeriode = Carbon::parse($data['startDate'])->translatedFormat('d F Y') . ' s/d ' . Carbon::parse($data['endDate'])->translatedFormat('d F Y');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ==========================================
        // 1. KOP SURAT (JUDUL LAPORAN)
        // ==========================================
        $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI MUTASI & VALUASI PERSEDIAAN BARANG');
        $sheet->mergeCells('A1:R1');
        $sheet->setCellValue('A2', 'Toko Bangunan Mitra Usaha 2');
        $sheet->mergeCells('A2:R2');
        $sheet->setCellValue('A3', 'Periode: ' . $teksPeriode);
        $sheet->mergeCells('A3:R3');

        // Styling Kop Surat
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->getColor()->setARGB('FF555555');
        $sheet->getStyle('A1:R3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension('1')->setRowHeight(30);
        $sheet->getRowDimension('2')->setRowHeight(25);
        $sheet->getRowDimension('3')->setRowHeight(20);

        // ==========================================
        // 2. BIKIN HEADER (TWO-TIER / DUA TINGKAT)
        // ==========================================
        $rowHeader1 = 5;
        $rowHeader2 = 6;

        // --- Tier 1 (Kelompok Besar) ---
        // Basic Info
        $sheet->setCellValue('A'.$rowHeader1, 'No')->mergeCells("A{$rowHeader1}:A{$rowHeader2}");
        $sheet->setCellValue('B'.$rowHeader1, 'Kode / SKU')->mergeCells("B{$rowHeader1}:B{$rowHeader2}");
        $sheet->setCellValue('C'.$rowHeader1, 'Nama Barang & Spesifikasi')->mergeCells("C{$rowHeader1}:C{$rowHeader2}");
        $sheet->setCellValue('D'.$rowHeader1, 'Kategori')->mergeCells("D{$rowHeader1}:D{$rowHeader2}");
        $sheet->setCellValue('E'.$rowHeader1, 'Satuan')->mergeCells("E{$rowHeader1}:E{$rowHeader2}");
        $sheet->setCellValue('F'.$rowHeader1, 'Harga Modal (Rp)')->mergeCells("F{$rowHeader1}:F{$rowHeader2}");
        
        // Mutasi
        $sheet->setCellValue('G'.$rowHeader1, 'Persediaan Awal')->mergeCells("G{$rowHeader1}:H{$rowHeader1}");
        $sheet->setCellValue('I'.$rowHeader1, 'Barang Masuk (+)')->mergeCells("I{$rowHeader1}:J{$rowHeader1}");
        $sheet->setCellValue('K'.$rowHeader1, 'Barang Keluar (-)')->mergeCells("K{$rowHeader1}:L{$rowHeader1}");
        $sheet->setCellValue('M'.$rowHeader1, 'Sistem (Akhir)')->mergeCells("M{$rowHeader1}:N{$rowHeader1}");
        
        // Opname & Selisih
        $sheet->setCellValue('O'.$rowHeader1, 'Fisik (Opname)')->mergeCells("O{$rowHeader1}:P{$rowHeader1}");
        $sheet->setCellValue('Q'.$rowHeader1, 'Selisih')->mergeCells("Q{$rowHeader1}:R{$rowHeader1}");

        // --- Tier 2 (Pemisahan Qty & Nilai) ---
        $colSubHeaders = [
            'G' => 'Qty', 'H' => 'Nilai (Rp)', // Awal
            'I' => 'Qty', 'J' => 'Nilai (Rp)', // Masuk
            'K' => 'Qty', 'L' => 'Nilai (Rp)', // Keluar
            'M' => 'Qty', 'N' => 'Nilai (Rp)', // Sistem
            'O' => 'Qty', 'P' => 'Nilai (Rp)', // Fisik
            'Q' => 'Qty', 'R' => 'Nilai (Rp)', // Selisih
        ];

        foreach ($colSubHeaders as $col => $text) {
            $sheet->setCellValue($col . $rowHeader2, $text);
        }

        // --- Styling Header ---
        // Border & Font untuk semua header
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ];
        $sheet->getStyle("A{$rowHeader1}:R{$rowHeader2}")->applyFromArray($styleHeader);

        // Warna Background Header Tier 1
        $sheet->getStyle("A{$rowHeader1}:F{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD00000'); // Merah Dasar
        $sheet->getStyle("G{$rowHeader1}:H{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF607D8B'); // Abu Kebiruan
        $sheet->getStyle("I{$rowHeader1}:J{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2E7D32'); // Hijau Tua
        $sheet->getStyle("K{$rowHeader1}:L{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFC62828'); // Merah Tua
        $sheet->getStyle("M{$rowHeader1}:N{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1565C0'); // Biru Tua
        $sheet->getStyle("O{$rowHeader1}:P{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4527A0'); // Ungu Tua
        $sheet->getStyle("Q{$rowHeader1}:R{$rowHeader1}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE65100'); // Oranye Tua

        // Warna Background Header Tier 2 (Pastel) dengan Font Hitam
        $sheet->getStyle("G{$rowHeader2}:R{$rowHeader2}")->getFont()->getColor()->setARGB('FF000000');
        $sheet->getStyle("G{$rowHeader2}:H{$rowHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCFD8DC'); 
        $sheet->getStyle("I{$rowHeader2}:J{$rowHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFC8E6C9'); 
        $sheet->getStyle("K{$rowHeader2}:L{$rowHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFCDD2'); 
        $sheet->getStyle("M{$rowHeader2}:N{$rowHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFBBDEFB'); 
        $sheet->getStyle("O{$rowHeader2}:P{$rowHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD1C4E9'); 
        $sheet->getStyle("Q{$rowHeader2}:R{$rowHeader2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFE0B2'); 


        // ==========================================
        // 3. MASUKIN DATA KE DALAM TABEL
        // ==========================================
        $row = 7;
        $no = 1;
        foreach ($laporan as $item) {
            
            // Kalkulasi
            $hpp = $item->harga_pokok ?? 0;
            $qty_awal = $item->stok_awal ?? 0;
            $qty_masuk = $item->masuk ?? 0;
            $qty_keluar = $item->keluar ?? 0;
            $qty_akhir = $item->stok_akhir ?? 0;
            $qty_fisik = $item->stok_fisik ?? $qty_akhir; 
            $qty_selisih = $qty_fisik - $qty_akhir;

            // Masukkan Nilai
            $sheet->setCellValue('A' . $row, $no++);
            
            // PENTING: SKU di-set Explicit String agar tidak jadi format E+ di Excel
            $sheet->setCellValueExplicit('B' . $row, $item->kode ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); 
            
            $sheet->setCellValue('C' . $row, $item->nama);
            $sheet->setCellValue('D' . $row, $item->kategori);
            $sheet->setCellValue('E' . $row, $item->satuan);
            $sheet->setCellValue('F' . $row, $hpp);
            
            $sheet->setCellValue('G' . $row, $qty_awal);
            $sheet->setCellValue('H' . $row, "=G{$row}*F{$row}"); // Rumus Excel
            
            $sheet->setCellValue('I' . $row, $qty_masuk);
            $sheet->setCellValue('J' . $row, "=I{$row}*F{$row}");
            
            $sheet->setCellValue('K' . $row, $qty_keluar);
            $sheet->setCellValue('L' . $row, "=K{$row}*F{$row}");
            
            $sheet->setCellValue('M' . $row, $qty_akhir);
            $sheet->setCellValue('N' . $row, "=M{$row}*F{$row}");
            
            $sheet->setCellValue('O' . $row, $qty_fisik);
            $sheet->setCellValue('P' . $row, "=O{$row}*F{$row}");
            
            $sheet->setCellValue('Q' . $row, $qty_selisih);
            $sheet->setCellValue('R' . $row, "=Q{$row}*F{$row}");

            // --- Styling per Baris Data ---
            $sheet->getStyle("A{$row}:R{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFB0BEC5');
            
            // Perataan
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}:E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}:R{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Format Angka (Accounting Format)
            $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("N{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("P{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("R{$row}")->getNumberFormat()->setFormatCode('#,##0');

            // Warna Teks Data (Masuk=Hijau, Keluar=Merah)
            $sheet->getStyle("I{$row}:J{$row}")->getFont()->getColor()->setARGB('FF2E7D32'); 
            $sheet->getStyle("K{$row}:L{$row}")->getFont()->getColor()->setARGB('FFC62828'); 
            
            // Warna Teks Sistem & Fisik (Biru & Ungu) + Bold
            $sheet->getStyle("M{$row}:N{$row}")->getFont()->setBold(true);
            $sheet->getStyle("M{$row}:N{$row}")->getFont()->getColor()->setARGB('FF1565C0'); 
            
            $sheet->getStyle("O{$row}:P{$row}")->getFont()->setBold(true);
            $sheet->getStyle("O{$row}:P{$row}")->getFont()->getColor()->setARGB('FF4527A0'); 

            // Warna Selisih
            if ($qty_selisih < 0) {
                $sheet->getStyle("Q{$row}:R{$row}")->getFont()->setBold(true);
                $sheet->getStyle("Q{$row}:R{$row}")->getFont()->getColor()->setARGB('FFC62828');
            } elseif ($qty_selisih > 0) {
                $sheet->getStyle("Q{$row}:R{$row}")->getFont()->setBold(true);
                $sheet->getStyle("Q{$row}:R{$row}")->getFont()->getColor()->setARGB('FF2E7D32');
            }

            $row++;
        }

        // ==========================================
        // 4. AUTO SIZE COLUMNS
        // ==========================================
        foreach (range('A', 'R') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // ==========================================
        // 5. DOWNLOAD FILE .XLSX
        // ==========================================
        $fileName = 'Laporan_Mutasi_Opname_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getLaporanData($request);
        $tokoAktif = StoreProfile::where('is_active', true)->first();
        $data['profile'] = $tokoAktif ?? (StoreProfile::first() ?? new StoreProfile());

        $pdf = Pdf::loadView('exports.laporan_pdf', $data);
        return $pdf->setPaper('a4', 'portrait')->download('Mutasi_Opname_' . date('Ymd') . '.pdf');
    }
}