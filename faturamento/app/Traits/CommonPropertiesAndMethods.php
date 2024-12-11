<?php

namespace App\Traits;

use App\Models\TipoCobranca;

trait CommonPropertiesAndMethods
{
    use SupplierList;
    use DREList;
    use BillingTypeList;
    use WorkflowList;
    use CenterCostList;
    use ApportionmentList;
    use AccountData;

    // propriedades para editar conta
    public $trace_code = null;
    public $paymentCondition = null;
    public $ufs = [];
    public $dreType = 'despesa';

    public function initializeProperties($trace_code)
    {
        $this->supplierList = $this->searchSupplier();
        $this->dreList = $this->searchDre();
        $this->billingTypeList = $this->searchBillingType();
        $this->workflowList = $this->searchWorkflow();
        $this->centerCostList = $this->searchCenterCost();
        $this->apportionmentList = $this->searchApportionment();
        $this->setPropertiesAccountData($trace_code);
    }
}
