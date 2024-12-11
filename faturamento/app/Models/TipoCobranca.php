<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCobranca extends Model
{
    use HasFactory;
    protected $table = 'tipo_cobranca';
    protected $fillable = [
        'nome',
        'id_empresa',
        'md5_nome'
    ];

    public function getBillingTypeList($queryBillingType, $pageBillingTypeSearch, $limitBillingType = 10)
    {
        $billingType = TipoCobranca::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->where('nome', 'like', "%$queryBillingType%")
            ->skip(($pageBillingTypeSearch - 1) * $limitBillingType)
            ->take($limitBillingType)
            ->get();

        if($billingType->count() == 0){
            return  [['id' => null, 'nome' => 'Nenhum tipo de cobrança encontrado']];
        }
        if($billingType->count() >= 1 and $pageBillingTypeSearch == 1){
            return $billingType->toArray();
        }
        if($billingType->count() >= 1 and $pageBillingTypeSearch > 1){
            return $billingType->toArray();
        }
        return;
    }
}
