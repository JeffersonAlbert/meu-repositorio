<?php

namespace App\Livewire;

use App\Helpers\FormatUtils;
use App\Models\CentroCusto;
use App\Models\Fornecedor;
use App\Models\Rateio;
use App\Models\TipoCobranca;
use App\Models\WorkFlow;
use App\QueryBuilder\RequestPaymentQueryBuilder;
use App\Services\ApprovedAccounts;
use App\Services\DREService;
use App\Services\Periods;
use App\Traits\Banks;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PaymentRequest extends AccountsPayable
{
    use WithPagination;
    use WithFileUploads;

    public $page = 'payment-request';
    public $billingDateRangeStart;
    public $billingDateRangeEnd;
    public $queryPeriod = "Este mês";
    public $period = null;
    public $approval_progress_request_payment;
    public $aproveds;
    public $needsMyApproval = false;
    public $allRequests;
    //propriedades de pesquisa
    //public $supplierName;
    //public $supplierId;
    public $traceCode;
    public $billingType;
    public $bank;
    public $paymentMethod;
    public $centerCost;
    public $apportioned;
    public $paymentDateRangeStart;
    public $paymentDateRangeEnd;
    //public $sortField = 'pvv_dtv';
    //public $sortDirection = 'asc';
    public $showType = null;
    //dados do formulario
    public $paymentCondition;
    public $accountFilesDescription = [];
    public $dreType = 'despesa';
    //public $newTraceCode;

    public function mount()
    {
        $this->billingDateRangeStart = date('Y-m-01');
        $this->billingDateRangeEnd = date('Y-m-t');
        $this->getRequestPayments();
    }

    public function render()
    {
        if($this->period == null){
            $this->setPeriod();
        }

        $requestPayments = $this->getRequestPayments();

        if($requestPayments->count() > 0){
            $this->approval_progress_request_payment = (new ApprovedAccounts())->calculateApprovedAccounts($requestPayments);
        }

        $this->getBadgeNeedsMyApproval();
        $this->getBadgePendent();
        $this->searchSupplier();
        $this->searchBillingType();
        $this->searchWorkflow();
        $this->searchCenterCost();
        $this->searchApportionment();
        $this->categorizePayablesByStatus();
        $this->searchDre();
        !$this->update ? $this->getInstallments($this->id) : null;
        return view('livewire.payment-request.payment-request', [
            'requestPayments' => $requestPayments
        ]);
    }

    public $payableByStatus = [];
    public function categorizePayablesByStatus()
    {
        $vencidos = (new RequestPaymentQueryBuilder())
            ->isNotDisabled()
            ->isNotApproved()
            ->billingDateRange($this->billingDateRangeStart, date('Y-m-d', strtotime('-1 day')))
            ->count();
        $venceHoje = (new RequestPaymentQueryBuilder())
            ->isNotDisabled()
            ->isNotApproved()
            ->billingDateRange(date('Y-m-d'), date('Y-m-d'))
            ->count();
        $aVencer = (new RequestPaymentQueryBuilder())
            ->isNotDisabled()
            ->isNotApproved()
            ->billingDateRange(date('Y-m-d', strtotime('+1 day')), $this->billingDateRangeEnd)
            ->count();
        $aprovados = (new RequestPaymentQueryBuilder())
            ->isNotDisabled()
            ->isApproved()
            ->billingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd)
            ->count();
        $total = $vencidos + $venceHoje + $aVencer + $aprovados;


        $this->payableByStatus= [
            'totalOverdue' => $vencidos,
            'totalDueToday' => $venceHoje,
            'totalDue' => $aVencer,
            'totalApproved' => $aprovados,
            'total' => $total
        ];
    }

    public $totalNeedsMyApproval;
    public function getBadgeNeedsMyApproval()
    {
        $query = (new RequestPaymentQueryBuilder())
            ->isNotDisabled()
            ->billingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd)
            ->needsMyApproval()
            ->list();
        $this->totalNeedsMyApproval = count($query);
    }

    public function getBadgePendent()
    {
        $query = (new RequestPaymentQueryBuilder())
            ->isNotDisabled()
            ->billingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd)
            ->isPending()
            ->list();
        $this->totalPendent = count($query);
    }

    public function setType($period)
    {
        if ($period == 'totalOverdue') {
            $this->showType = 'totalOverdue';
        }
        if ($period == 'totalDueToday') {
            $this->showType = 'totalDueToday';
        }
        if ($period == 'totalDue') {
            $this->showType = 'totalDue';
        }
        if ($period == 'totalApproved') {
            $this->showType = 'totalApproved';
        }
        if ($period == 'all') {
            $this->showType = null;
        }
        $this->resetPage();
        $this->render();
    }

    public function setRows($rows)
    {
        auth()->user()->linhas_grid = $rows;
        auth()->user()->save();
        $this->render();
    }

    public function searchBy()
    {
        $this->supplierId = $this->supplierId ?? null;
        $this->traceCode = $this->traceCode ?? null;
        $this->billingType = $this->billingTypeId ?? null;
        $this->bank = $this->bankId ?? null;
        $this->paymentMethod = $this->paymentMethodId ?? null;
        $this->centerCost = $this->centerCostId ?? null;
        $this->apportioned = $this->apportionmentId ?? null;
        $this->getRequestPayments();
    }
    public $isPaid = false;
    public function getRequestPayments()
    {
         $query = (new RequestPaymentQueryBuilder())
            ->isNotDisabled();
         if ($this->showType == null) {
             $query = $query->billingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd);
         } elseif($this->showType == 'totalOverdue'){
             $query = $query->billingDateRange($this->billingDateRangeStart, date('Y-m-d', strtotime('-1 day')))
                ->isNotPaid();
                //->isNotApproved();
         } elseif ($this->showType == 'totalDueToday'){
             $query = $query->billingDateRange(date('Y-m-d'), date('Y-m-d'));
                //->isNotApproved();
         } elseif ($this->showType == 'totalDue'){
             $query = $query->billingDateRange(date('Y-m-d', strtotime('+1 day')), $this->billingDateRangeEnd);
                //->isNotApproved();
         } elseif ($this->showType == 'totalApproved'){
             $query = $query->billingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd);
                //->isApproved();
         }

         if($this->traceCode !== null and $this->traceCode !== ''){
            $query = $query->byTraceCode($this->traceCode);
         }

         if($this->supplierId !== null and $this->supplierId !== 'all'){
             $query = $query->bySupplier($this->supplierId);
         }

         if($this->isPaid){
             $query = $query->isPaid();
         }

         if($this->needsMyApproval){
             $query = $query->needsMyApproval();
         }

        if($this->onlyOpened == true){
            $query = $query->isNotPaid();
        }

        if($this->pendent == true){
            $query = $query->isPending();
        }

         $query = $query->sortBy($this->sortField, $this->sortDirection)
            //->showQuery();
            ->paginate(auth()->user()->linhas_grid);

         return $query;
    }

    public function setMyApproval()
    {
        $this->needsMyApproval = !$this->needsMyApproval;
        $this->render();
    }
    public function setPaid()
    {
        $this->isPaid = !$this->isPaid;
        $this->render();
    }

    public $pendent = false;
    public function setPendent()
    {
        $this->pendent = !$this->pendent;
        $this->render();
    }

    public function sortBy($field)
    {
        if ($field == $this->sortField) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function removeMyApproved($query, $aproveds)
    {
        foreach($aproveds as $aproved){
            foreach($query as $key => $value) {
                if ($aproved['id_processo'] == $value->id and $aproved['id_processo_vencimento_valor'] == $value->pvv_id) {
                    unset($query[$key]);
                }
            }
        }
        return $query;
    }



    public function setPeriod($period = 'thisMonth')
    {
        $periodArray = (new Periods())->setPeriod($period);

        $this->billingDateRangeStart = $periodArray['startDate'];
        $this->billingDateRangeEnd = $periodArray['endDate'];
        $this->queryPeriod = $periodArray['queryPeriod'];
        $this->period = $periodArray;
        $this->resetPage();

        $this->render();
    }

    public function redirectToDesktop($id, $pvv_dtv)
    {
        return redirect()->route('approvals.index', ['processId' => $id, 'pvvDtv' => $pvv_dtv]);
    }
}
