<?php

namespace App\Traits;

use App\Helpers\Utils;
use App\Models\Fornecedor;

trait SupplierList
{
    public $supplierList = [];
    public $querySupplier = null;
    public $pageSupplierSearch = 1;
    public $limitSuppliers = 10;


    public function loadMoreSupplier()
    {
        $this->pageSupplierSearch++;
        $oldSupplierList = $this->supplierList;
        return $this->supplierList = array_merge($oldSupplierList, $this->searchSupplier());
    }

    public function searchSupplierByString()
    {
        $this->pageSupplierSearch = 1;
        $this->supplierList = $this->searchSupplier();
    }

    private function searchSupplier()
    {
        return $this->supplierList = (new Fornecedor())->searchSupplier(
            $this->querySupplier,
            $this->pageSupplierSearch,
            $this->limitSuppliers
        );
    }

    public function selectSupplier($id, $name)
    {
        $this->supplierName = $name;
        $this->supplierId = $id;
        $this->selectedSupplier = true;
    }


}
