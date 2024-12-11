<?php

namespace App\Traits;

use App\Services\DREService;

trait SaveFinanceCategoryExpense
{
    public $dreCategory;
    public function saveDRE()
    {
        if ($this->dreCategory == 'despesa') {
            $dreServiceResult = (new DREService())->storeDRE([
                'descricao' => $this->dreDescription,
                'tipo' => $this->dreCategory,
                'codigo' => '0',
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }

        if ($this->dreCategory !== 'despesa') {
            $dreServiceResult = (new DREService())->storeSubDRE([
                'descricao' => $this->dreDescription,
                'id_dre' => $this->dreCategory,
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }
        if (!$dreServiceResult->success) {
            session()->flash('error', $dreServiceResult->message);
            return;
        }
        session()->flash('success', 'DRE cadastrado com sucesso');
        $this->dispatch('dataSaved');
    }
}
