<?php

namespace App\Traits;

trait Apportionment
{
    public $inputsApportionment = [];
    public $apportionmentValue = [];
    public $apportionmentPercent = [];

    public function addInputApportionment()
    {
        $this->inputsApportionment[] = '';
    }

    public function removeInputApportionment($index)
    {
        unset($this->inputsApportionment[$index]);
        $this->inputsApportionment = array_values($this->inputsApportionment);
    }
}
