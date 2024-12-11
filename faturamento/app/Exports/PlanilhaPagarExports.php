<?php

namespace App\Exports;

use App\Http\Controllers\SYS\ReportController;
use App\Services\ProcessService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class PlanilhaPagarExports implements FromView
{
    protected $data;
    protected $result;
    protected $processService;
    public function __construct($data, ProcessService $processService)
    {
        $this->processService = $processService;
        $this->data = $data;
        $this->result = (new ReportController($this->processService))
            ->gridContasPagar($data);
    }

    public function view() : View
    {
        return view('pdf.contas-pagar', [
            'relatorio' => json_decode($this->result->content()),
        ]);
    }
}
