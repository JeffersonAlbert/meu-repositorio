<?php

namespace App\Traits;

use App\Models\Rateio;

trait ApportionmentList
{
    public $apportionmentList = [];
    public $queryApportionment = null;
    public $pageApportionmentSearch = 1;
    public $limitApportionment = 10;

    public function searchApportionmentByString()
    {
        $this->pageApportionmentSearch = 1;
        $this->apportionmentList = $this->searchApportionment();
    }

    public function loadMoreApportionment()
    {
        $this->pageApportionmentSearch++;
        $oldApportionmentList = $this->apportionmentList;
        return $this->apportionmentList = array_merge($oldApportionmentList, $this->searchApportionment());
    }

    public function searchApportionment()
    {
        return $this->apportionmentList = (new Rateio())->getApportionmentList(
            $this->queryApportionment,
            $this->pageApportionmentSearch,
            $this->limitApportionment
        );
    }
}
