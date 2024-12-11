<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $fillable = [
        'razao_social',
        'inscricao_estadual',
        'nome',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'cidade',
        'id_empresa',
        'cpf_cnpj'
    ];

    public function getClientList($queryClient, $pageClientSearch, $limitClient)
    {
        $clientes = Clientes::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->where('nome', 'like', "%$queryClient%")
            ->skip(($pageClientSearch - 1) * $limitClient)
            ->take($limitClient)
            ->orderBy('nome')
            ->get();

        if($clientes->count() == 0){
            return $clienteList = [['id' => null, 'nome' => 'Nenhum fornecedor encontrado']];
        }
        if($clientes->count() >= 1 and $pageClientSearch == 1){
            return $clienteList = $clientes->toArray();
        }
        if($clientes->count() >= 1 and $pageClientSearch > 1){
            return $clientes->toArray();
        }
        return;

    }
}
