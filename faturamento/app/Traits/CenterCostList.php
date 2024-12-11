<?php

namespace App\Traits;

use App\Models\CentroCusto;

trait CenterCostList
{
    public $centerCostList = [];
    public $queryCenterCost = null;
    public $pageCenterCostSearch = 1;
    public $limitCenterCost = 10;

    public function searchCenterCostByString()
    {
        $this->pageCenterCostSearch = 1;
        $this->centerCostList = $this->searchCenterCost();
    }

    public function loadMoreCenterCost()
    {
        $this->pageCenterCostSearch++;
        $oldCenterCostList = $this->centerCostList;
        return $this->centerCostList = array_merge($oldCenterCostList, $this->searchCenterCost());
    }

    public function searchCenterCost()
    {
        return $this->centerCostList = (new CentroCusto())->getCenterCostList(
            $this->queryCenterCost,
            $this->pageCenterCostSearch,
            $this->limitCenterCost
        );
    }
}
