<?php

namespace App\Exports;

use App\Http\Controllers\SYS\FinanceiroController;
use App\Models\DRE;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FluxoCaixaExports implements FromView
{
    protected $data;
    protected $result;
    public function __construct($data)
    {
        $this->data = $data;
        $this->result = (new FinanceiroController())->fluxoCaixa($data);
    }

    public function view() : View
    {
        return view('pdf.fluxo-caixa', [
            'data' => $this->result,
        ]);
    }
}
