<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;
    protected $table = 'fornecedor';
    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'id_empresa',
        'telefone',
        'cidade',
        'razao_social',
        'inscrica_estadual',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'forma_pagamento',
        'observacao'
    ];

    public function searchSupplier($querySupplier, $pageSupplierSearch, $limitSuppliers = 10)
    {
        $fornecedores = Fornecedor::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->where('nome', 'like', "%$querySupplier%")
            ->skip(($pageSupplierSearch - 1) * $limitSuppliers)
            ->take($limitSuppliers)
            ->orderBy('nome')
            ->get();

        if($fornecedores->count() == 0){
            return $supplierList = [['id' => null, 'nome' => 'Nenhum fornecedor encontrado']];
        }
        if($fornecedores->count() >= 1 and $pageSupplierSearch == 1){
            return $supplierList = $fornecedores->toArray();
        }
        if($fornecedores->count() >= 1 and $pageSupplierSearch > 1){
            return $fornecedores->toArray();
        }
        return;
    }
}
