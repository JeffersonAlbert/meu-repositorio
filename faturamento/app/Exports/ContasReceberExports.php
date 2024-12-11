<?php

namespace App\Exports;

use App\Models\ContasReceber;
use App\Models\User;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ContasReceberExports implements FromView
{
    protected $data;
    public function __construct($request)
    {
        $this->data = (new ContasReceber())->relatorioContasReceber($request);
    }

    public function view() : View
    {
        return view('pdf.contas-receber', [
            'relatorio' => $this->data,
        ]);
    }
}
