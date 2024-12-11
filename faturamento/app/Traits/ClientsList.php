<?php

namespace App\Traits;

use App\Models\Clientes;

trait ClientsList
{
    public $clientsList = [];
    public $queryClient = null;
    public $pageClientSearch = 1;
    public $limitClient = 10;

    public function searchClientByString()
    {
        $this->pageClientSearch = 1;
        $this->clientsList = $this->searchClient();
        dd($this->clientsList);
    }

    public function loadMoreClient()
    {
        $this->pageClientSearch++;
        $oldClientsList = $this->clientsList;
        return $this->clientsList = array_merge($oldClientsList, $this->searchClient());
    }

    public function searchClient()
    {
        return $this->clientsList = (new Clientes())->getClientList(
            $this->queryClient,
            $this->pageClientSearch,
            $this->limitClient
        );
    }
}
