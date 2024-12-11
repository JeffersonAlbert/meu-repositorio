<?php

namespace App\Traits;

use App\Models\TipoCobranca;

trait BillingTypeList
{
    public $billingTypeList = [];
    public $queryBillingType = null;
    public $pageBillingTypeSearch = 1;
    public $limitBillingType = 10;

    public function searchBillingTypeByString()
    {
        $this->pageBillingTypeSearch = 1;
        $this->billingTypeList = $this->searchBillingType();
    }

    public function loadMoreBillingType()
    {
        $this->pageBillingTypeSearch++;
        $oldBillingTypeList = $this->billingTypeList;
        return $this->billingTypeList = array_merge($oldBillingTypeList, $this->searchBillingType());
    }

    public function searchBillingType()
    {
        return $this->billingTypeList = (new TipoCobranca())->getBillingTypeList(
            $this->queryBillingType,
            $this->pageBillingTypeSearch,
            $this->limitBillingType
        );
    }

    public function selectBillingType($id, $name)
    {
        $this->billingTypeId = $id;
        $this->billingTypeName = $name;
    }
}
