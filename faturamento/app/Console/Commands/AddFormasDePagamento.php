<?php

namespace App\Console\Commands;

use App\Models\FormasPagamento;
use Illuminate\Console\Command;

class AddFormasDePagamento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-formas-de-pagamento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $formasPagamento = [
                ['nome'  => 'Boleto'],
                ['nome' => 'Transferência'],
                ['nome' => 'Pix'],
                ['nome' => 'Cartão Crédito'],
                ['nome' => 'Cartão Débito'],
                ['nome' => 'Outros'],
                ['nome' => 'Depósito Bancário'],
                ['nome' => 'Cashback'],
                ['nome' => 'Cheque'],
                ['nome' => 'Débito Automático'],
                ['nome' => 'Dinheiro'],
                ['nome' => 'Vale Presente']
        ];
        foreach($formasPagamento as $formaPagamento){
            FormasPagamento::create($formaPagamento);
        }

    }
}
