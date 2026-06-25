<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StokMutasiExport implements FromView, ShouldAutoSize
{
    protected $laporan;
    protected $periode;

    public function __construct($laporan, $periode)
    {
        $this->laporan = $laporan;
        $this->periode = $periode;
    }

    // Mengubah Blade HTML menjadi File Excel!
    public function view(): View
    {
        return view('exports.laporan_excel', [
            'laporan' => $this->laporan,
            'periode' => $this->periode
        ]);
    }
}