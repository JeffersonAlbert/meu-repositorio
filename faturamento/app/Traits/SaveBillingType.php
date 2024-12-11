<?php

namespace App\Traits;

use App\Models\TipoCobranca;

trait SaveBillingType
{
    public $billingTypeName;
    public $billingType;
    public function saveBillingType()
    {
        if ($this->billingTypeName == null) {
            session()->flash('error', 'O campo tipo de cobrança é obrigatório');
            return;
        }

        TipoCobranca::create([
            'nome' => strtoupper($this->billingTypeName),
            'id_empresa' => auth()->user()->id_empresa,
            'md5_nome' => md5(strtoupper(substr($this->billingType, 0, 6)))
        ]);
        session()->flash('success', 'Tipo de cobrança cadastrado com sucesso');
        $this->dispatch('dataSaved');
        $this->render();
    }
}
