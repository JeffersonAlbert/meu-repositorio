<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoriaDRE extends Model
{
    use HasFactory;
    protected $table = 'sub_categoria_dre';

    protected $fillable = [
        'id_dre',
        'descricao',
        'vinculo_dre',
        'editable',
        'id_empresa',
        'id_usuario'
    ];

    public function dreReceita()
    {
        return SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
            ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id')
            ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
            ->where('dre.tipo', 'receita')
            ->orWhere('dre.tipo', 'receita')
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', 'receita')
            ->get();
    }

    public function dreDespesa()
    {
        return SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
            ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id')
            ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
            ->where('dre.tipo', 'despesa')
            ->orWhere('dre.tipo', 'despesa')
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', 'despesa')
            ->get();
    }
}
