<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCusto extends Model
{
    use HasFactory;
    protected $table = 'centro_custos';
    protected $fillable = [
        'nome',
        'descricao',
        'id_empresa'
    ];

    public function getCenterCostList($queryCostCenter, $pageCostCenterSearch, $limitCostCenter = 10)
    {
        $costCenter = CentroCusto::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->where('nome', 'like', "%$queryCostCenter%")
            ->skip(($pageCostCenterSearch - 1) * $limitCostCenter)
            ->take($limitCostCenter)
            ->get();

        if($costCenter->count() == 0){
            return  [['id' => null, 'nome' => 'Nenhum centro de custo encontrado']];
        }
        if($costCenter->count() >= 1 and $pageCostCenterSearch == 1){
            return $costCenter->toArray();
        }
        if($costCenter->count() >= 1 and $pageCostCenterSearch > 1){
            return $costCenter->toArray();
        }
        return;
    }
}
