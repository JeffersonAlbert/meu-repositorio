<?php

namespace App\Traits;

use App\Models\CentroCusto;

trait SaveCenterCost
{
    public $centerCostName;
    public $centerCostDescription;
    public function saveCenterCost()
    {
        if ($this->centerCostName == null) {
            session()->flash('error', 'O campo centro de custo é obrigatório');
            return;
        }

        CentroCusto::create([
            'nome' => strtoupper($this->centerCostName),
            'descricao' => $this->centerCostDescription,
            'id_empresa' => auth()->user()->id_empresa,
        ]);

        session()->flash('success', 'Centro de custo cadastrado com sucesso');
        $this->dispatch('dataSaved');
        $this->render();
    }
}
