<?php

namespace App\Console\Commands;

use App\Http\Controllers\SYS\AutocompleteController;
use App\Models\Fornecedor;
use Illuminate\Console\Command;

class CadastraFornecedor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cadastra-fornecedor';

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
        $autocomplete = new AutocompleteController();
        $fornecedores = Fornecedor::whereNull('endereco')->get();
        foreach($fornecedores as $fornecedor){
            if(strlen($fornecedor->cpf_cnpj) >= 14){
                $lala = $autocomplete->consultaCnpj($fornecedor->cpf_cnpj);
                $empresa = json_decode($lala);
                print_r($empresa);
                $data['razao_social'] = $empresa->razao_social;
                $data['inscrica_estadual'] = isset($empresa->estabelecimento->inscricoes_estaduais[0]->inscricao_estadual) ? $empresa->estabelecimento->inscricoes_estaduais[0]->inscricao_estadual : null;
                $data['nome'] = isset($empresa->estabelecimento->nome_fantasia) ? $empresa->estabelecimento->nome_fantasia : $empresa->razao_social;
                $data['endereco'] = "{$empresa->estabelecimento->tipo_logradouro} {$empresa->estabelecimento->logradouro}";
                $data['numero'] = $empresa->estabelecimento->numero;
                $data['complemento'] = $empresa->estabelecimento->complemento;
                $data['bairro'] = $empresa->estabelecimento->bairro;
                $data['cep'] = $empresa->estabelecimento->cep;
                $data['cidade'] = $empresa->estabelecimento->cidade->nome;
                Fornecedor::where('id', $fornecedor->id)->update($data);
            }
            sleep(60);
        }
    }
}
