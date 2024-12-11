<?php

namespace App\Traits;

use App\Models\WorkFlow;

trait WorkflowList
{
    public $workflowList = [];
    public $queryWorkflow = null;
    public $pageWorkflowSearch = 1;
    public $limitWorkflow = 10;

    public function searchWorkflowByString()
    {
        $this->pageWorkflowSearch = 1;
        $this->workflowList = $this->searchWorkflow();
    }

    public function loadMoreWorkflow()
    {
        $this->pageWorkflowSearch++;
        $oldWorkflowList = $this->workflowList;
        return $this->workflowList = array_merge($oldWorkflowList, $this->searchWorkflow());
    }

    public function searchWorkflow()
    {
        return $this->workflowList = (new Workflow())->getWorkFlowList(
            $this->queryWorkflow,
            $this->pageWorkflowSearch,
            $this->limitWorkflow
        );
    }

}
