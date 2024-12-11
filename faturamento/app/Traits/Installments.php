<?php

namespace App\Traits;

trait Installments
{
    public $installmentsInputs = [];
    public function addInstallmentInput()
    {
        $this->installmentsInputs[] = '';
    }

    public function removeInstallmentInput($index)
    {
        unset($this->installmentsInputs[$index-1]);
        unset($this->installments[$index]);
        $this->installmentsInputs = array_values($this->installmentsInputs);
        $this->installments = array_values($this->installments);
    }

    public function addInstallmentsQtd($qtde)
    {
        $this->installmentsInputs = [];
        for($i = 1; $i < $qtde; $i++) {
            $this->installmentsInputs[] = '';
        }
    }
}
