<?php

namespace App\Traits;

use App\Models\Bancos;
use Livewire\Attributes\Renderless;

trait Banks
{
    public $bankName;
    public $bankAgency;
    public $bankAccount;

    #[Renderless]
    public function saveBank()
    {
        $this->validate([
            'bankName' => 'required',
            'bankAgency' => 'required',
            'bankAccount' => 'required'
        ]);

        Bancos::create([
            'id_empresa' => auth()->user()->id_empresa,
            'nome' => $this->bankName,
            'agencia' => $this->bankAgency,
            'conta' => $this->bankAccount
        ]);

        $this->reset('bankName', 'bankAgency', 'bankAccount');
        $this->dispatch('closeModal', 'modalAddBank');
        return session()->flash('success', 'Banco cadastrado com sucesso');
    }
}
