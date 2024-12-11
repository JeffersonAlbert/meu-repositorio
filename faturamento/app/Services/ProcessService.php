<?php

namespace App\Services;

use App\QueryBuilder\ProcessQueryBuilder;
use Illuminate\Support\Facades\Auth;

class ProcessService
{
    protected $processQueryBuilder;
    protected $user;

    public function __construct(ProcessQueryBuilder $processQueryBuilder)
    {
        $this->processQueryBuilder = $processQueryBuilder;
    }

    public function setUser($user)
    {
        $this->user = $user;
        $this->processQueryBuilder->setUser($user);
    }

    public function listAllProcess()
    {
        return $this->processQueryBuilder->get();
    }

    public function byTraceCode($traceCode)
    {
        return $this->processQueryBuilder->byTraceCode($traceCode);
    }

    public function isNotDisabled()
    {
        return $this->processQueryBuilder->isNotDisabled();
    }

    public function isDisabled()
    {
        return $this->processQueryBuilder->isDisabled();
    }

    public function isApproved()
    {
        return $this->processQueryBuilder->isApproved();
    }

    public function inProcess()
    {
        return $this->processQueryBuilder->inProcess();
    }

    public function isPendent()
    {
        return $this->processQueryBuilder->isPendent();
    }

    public function isPaid()
    {
        return $this->processQueryBuilder->isPaid();
    }

    public function isNotPaid()
    {
        return $this->processQueryBuilder->isNotPaid();
    }

    public function bySupplier($supplierText)
    {
        return $this->processQueryBuilder->bySupplier($supplierText);
    }

    public function byBillingType($billingTypeId)
    {
        return $this->processQueryBuilder->byBillingType($billingTypeId);
    }

    public function byBank($bankId)
    {
        return $this->processQueryBuilder->byBank($bankId);
    }

    public function byPaymentMethod($paymentMethodId)
    {
        return $this->processQueryBuilder->byPaymentMethod($paymentMethodId);
    }

    public function byPaymentDateRange($startDate, $endDate)
    {
        return $this->processQueryBuilder->byPaymentDateRange($startDate, $endDate);
    }

    public function byBillingDateRange($startDate, $endDate)
    {
        return $this->processQueryBuilder->byBillingDateRange($startDate, $endDate);
    }

    public function byApportioned($apportionedId)
    {
        return $this->processQueryBuilder->byApportioned($apportionedId);
    }

    public function byCenterCost($centerCostId)
    {
        return $this->processQueryBuilder->byCenterCost($centerCostId);
    }

    public function paginate()
    {
        return $this->processQueryBuilder->paginate(auth()->user()->linhas_grid);
    }

    public function list()
    {
        return $this->processQueryBuilder->get();
    }

    public function count()
    {
        return $this->processQueryBuilder->count();
    }

    public function showQuery()
    {
        return $this->processQueryBuilder->showQuery();
    }

    public function sortBy($field, $direction)
    {
        return $this->processQueryBuilder->sortBy($field, $direction);
    }

    public function first()
    {
        return $this->processQueryBuilder->first();
    }

    public function byId($id)
    {
        return $this->processQueryBuilder->byId($id);
    }
}
