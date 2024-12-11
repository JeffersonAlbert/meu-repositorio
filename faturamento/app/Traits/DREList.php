<?php

namespace App\Traits;

use App\Services\DREService;

trait DREList
{
    public $dreList = [];
    public $queryDre = null;
    public $pageDreSearch = 1;
    public $limitDre = 10;

    public function searchDreByString()
    {
        $this->pageDreSearch = 1;
        $this->dreList = $this->searchDre();
    }

    public function loadMoreDre()
    {
        $this->pageDreSearch++;
        $oldDreList = $this->dreList;
        return $this->dreList = array_merge($oldDreList, $this->searchDre());
    }

    public function searchDre()
    {
        $dre = (new DREService())->getAllDREByString(
            $this->dreType,
            $this->queryDre,
            $this->limitDre,
            $this->pageDreSearch);

        if($dre->count() == 0){
            return $this->dreList = [['sub_id' => null, 'sub_desc' => 'Nenhum dre encontrado']];
        }

        if($dre->count() >= 1 and $this->pageDreSearch == 1){
            return $this->dreList = $dre->toArray();
        }

        if($dre->count() >= 1 and $this->pageDreSearch > 1){
            return $dre->toArray();
        }
        return;
    }
}
