<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use HasFactory;
    protected $fillable = [
        'produto',
        'tipo',
        'id_empresa',
        'codigo_produto',
        'valor',
        'valor_custo',
        'margem_value',
        'margem_percent',
        'descricao',
        'unidade_medida',
        'peso_liquido',
        'estoque_minimo',
        'peso_bruto',
        'comprimento',
        'largura',
        'altura',
        'valor_custo',
        'ean',
        'volume',
        'categoria',
        'files',
    ];

    public function produtos(){
        return $this->select('produtos.id', 'produtos.produto', 'produtos.codigo_produto', 
        'produtos.valor', 'produtos.valor_custo', 'produtos.margem_value', 'produtos.margem_percent',
        'produtos.tipo','estoque_atual.quantidade')
            ->leftJoin('estoque_atual', 'produtos.id', 'estoque_atual.produto_id')
            ->where('produtos.id_empresa', auth()->user()->id_empresa)
            ->get();
    }

    public function produtosPorId($id){
        return $this->select('produtos.*','estoque_atual.quantidade')
            ->leftJoin('estoque_atual', 'produtos.id', 'estoque_atual.produto_id')
            ->where('produtos.id_empresa', auth()->user()->id_empresa)
            ->where('produtos.id', $id)
            ->first();
    }
}
