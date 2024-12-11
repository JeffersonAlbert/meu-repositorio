<?php

namespace App\Exports;

use App\Http\Controllers\SYS\DREController;
use App\Models\DRE;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DREExports implements FromView
{
    protected $data;
    protected $periodo;
    protected $startYear;
    protected $endYear;

    public function __construct($periodo, $startYear, $endYear)
    {
        $this->periodo = $periodo;
        $this->startYear = $startYear;
        $this->endYear = $endYear;
        $this->data = (new DREController())->dreReport($periodo, $startYear, $endYear);
    }

    public function view() : View
    {
        return view('pdf.dre', [
            'data' => $this->data,
            'periodo' => $this->periodo,
            'startYear' => $this->startYear,
            'endYear' => $this->endYear
        ]);
    }
}
