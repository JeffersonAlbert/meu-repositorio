<?php

namespace App\Exports;

use App\Models\ContasReceber;
use App\Models\Processo;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ContasPagarExports implements FromCollection, WithHeadings, WithEvents
{
    public $data;
    private $contasPagar;

    public function __construct($data)
    {
        $this->data = $data;
        $this->contasPagar = new Processo();
    }

    public function  headings(): array
    {
        return [
            'Identificação',
            'Cliente',
            'Filial',
            'Categoria',
            'Contrato',
            'Vencimento',
            'Valor',
            'Data pagamento',
            'Codigo de referencia',
            'Produto',
            'Serviço',
            'Rateio',
            'Centro Custo',
            'Status',
            'Observação'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:O1')
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'ffffff'],
                        ]
                    ])
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('5f8769');

            }
        ];
    }

    public function collection()
    {
        $reportContasPagar = $this->contasPagar->allProcessos($this->data);
        return $reportContasPagar['result'];
    }
}
