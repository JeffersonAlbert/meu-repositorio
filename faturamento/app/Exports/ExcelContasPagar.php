<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelContasPagar implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view() : View
    {
        return view('pdf.contas-pagar', [
            'relatorio' => $this->data,
        ]);
    }
}
