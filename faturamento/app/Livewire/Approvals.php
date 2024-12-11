<?php

namespace App\Livewire;

use App\Helpers\FormatUtils;
use App\Helpers\UploadFiles;
use App\Models\ApprovedProcesso;
use App\Models\ObservacaoProcesso;
use App\Models\Pagamentos;
use App\Models\PendenciaProcesso;
use App\Models\Processo;
use App\Models\ProcessoHistorico;
use App\Models\ProcessoVencimentoValor;
use App\Models\User;
use App\Services\Account;
use App\Services\ApprovedAccounts;
use App\Services\FinancesService;
use App\Services\LogsNumber;
use App\Services\UploadService;
use App\Traits\Apportionment;
use App\Traits\Banks;
use App\Traits\SaveBillingType;
use App\Traits\CommonPropertiesAndMethods;
use App\Traits\SaveCenterCost;
use App\Traits\SaveFinanceCategoryExpense;
use App\Traits\SaveUpdateSupplier;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use function PHPUnit\Framework\isJson;

class Approvals extends Component
{
    use WithFileUploads;
    use WithPagination;
    use CommonPropertiesAndMethods;
    use SaveUpdateSupplier;
    use SaveFinanceCategoryExpense;
    use SaveBillingType;
    use SaveCenterCost;
    use Banks;
    use Apportionment;

    public $doc = null;
    public $fileName = 'Arquivos';
    public $docList = [];
    public $mimeType = null;

    #[Url]
    public $processId = null;

    #[Url]
    public $pvvDtv = null;

    public $errorMessages = null;

    // dados da query
    public $supplierCNPJ = null;
    public $supplierPaymentMethod = null;
    public $createdAt = null;
    public $installmentValue = null;
    public $userCreated = null;
    public $workflowId = null;
    public $notaFiscalNumber = null;
    public $emissionDate = null;
    public $userLastModification = null;
    public $workflowName = null;
    public $traceCode = null;
    public $competence = null;
    public $installmentDate = null;
    public $pvvId = null;

    // botoes de aprovacao
    public $approvals = [];
    public $allApproved = false;
    public $pay = false;
    public $inputs = [];

    public $paymentDate = null;
    public $paymentValue = null;
    public $files_type_description = null;
    public function mount()
    {
        $usersList = User::where('id_empresa', auth()->user()->id_empresa)
            ->get();
        $this->usersList = $usersList->toArray();
        $deleted = Processo::select('deletado')->where('id', $this->processId)->first();
        if($deleted->deletado == 1){
            return redirect()->route('payment-request.index')->with('error', 'Processo deletado');
        }
        $this->createHistory('Visualizou o processo');
    }

    public function createHistory($acao)
    {
        ProcessoHistorico::create(
            [
                'id_usuario' => auth()->user()->id,
                'id_processo' => $this->processId,
                'acao' => $acao,
            ]
        );
    }

    public function setPdf($name = null)
    {
        $this->fileName = $name;
        $this->doc = 'uploads/' . $name;
        $this->mimeType = substr(strtolower($name), -3) == 'pdf' ? 'application/pdf' : 'image/jpeg';
        $this->dispatch('docUpdated', $this->doc, $this->mimeType);
    }

    public function fileList($arrayFiles)
    {
        $arrayFiles = isJson($arrayFiles) ? json_decode($arrayFiles, true) : $arrayFiles;
        $files = [];
        if (is_null($arrayFiles) or empty($arrayFiles)) {
            return $files = ['Sem arquivos'];
        }
        foreach ($arrayFiles as $key => $file) {
            $files[] = $file['fileName'];
        }
        return $files;
    }

    public $detailedAccountData;
    public $pending = false;
    public $pendingErrors = null;
    public $pendingDate = null;
    public $selectedSupplier = false;
    public $supplierName = null;
    public function toProperties($processo)
    {
        // dd($processo);
        $this->supplierCNPJ = $processo->f_doc;
        $this->supplierId = $processo->f_id;
        $this->supplierPaymentMethod = $processo->fornecedor_forma_pagamento;
        $this->createdAt = $processo->created_at;
        $this->installmentValue = FormatUtils::formatMoney($processo->vparcela);
        $this->userCreated = $processo->u_name;
        $this->workflowId = $processo->p_workflow;
        $this->supplierName = $this->selectedSupplier ? $this->supplierName : $processo->fornecedor_name;
        $this->notaFiscalNumber = $processo->num_nota;
        $this->emissionDate = $processo->p_emissao;
        $this->userLastModification = $processo->u_last_modification;
        $this->workflowName = $processo->workflow_nome;
        $this->traceCode = $processo->trace_code;
        $this->competence = $processo->competencia;
        $this->installmentDate = $processo->pvv_dtv;
        $this->paymentDate = $processo->pago ? date('Y-m-d', strtotime($processo->a_data_pagamento)) : date('Y-m-d');
        $this->paymentValue = $processo->valor_pago == null ? $this->paymentValue : FormatUtils::formatMoney($processo->valor_pago);
        $this->bankName = $this->bankName ?? $processo->banco_nome;
        $this->paymentMethodName = $processo->forma_pagamento_nome;
        $this->observation = $processo->p_observacao;
        $this->pvvId = $processo->pvv_id;
        $this->docList = $this->fileList($processo->files_types_desc);
        $this->pay = $processo->pago;
        $this->pending = $processo->pendencia;
        $this->pendingErrors = $processo->pendencia_obs ?? null;
        $this->pendingDate = $processo->pendencia_data ?? null;
        $this->files_type_description = $processo->files_types_desc;
        $this->detailedAccountData = $processo->toArray();
    }

    public function setApprovals($processo)
    {
        $groups = (new ApprovedAccounts())->getGroupsForApproval(
            $processo->p_workflow,
        );

        $approved = (new ApprovedAccounts())->getApprovalsForGroup(
            $groups,
            $this->processId,
            $processo->pvv_id
        );
        $this->validateApprovals($groups, $approved);
    }

    public function saveCorrectedPendingIssue($groupId)
    {
        Processo::where('id', $this->processId)
            ->update(
                [
                    'pendencia' => false,
                ]
            );
        PendenciaProcesso::where('id_processo', $this->processId)
            ->where('id_processo_vencimento_valor', $this->pvvId)
            ->delete();
        ObservacaoProcesso::create(
            [
                'id_processo' => $this->processId,
                'id_usuario' => auth()->user()->id,
                'observacao' => 'Corrigiu pendência: ' . $this->pendingErrors
            ]
        );
        $this->createHistory('Corrigiu pendência: ' . $this->pendingErrors);
        $this->approve($groupId);
        $this->pending = false;
        $this->pendingErrors = null;
        $this->pendingDate = null;
    }

    public $correctPendingGroup = null;

    public function approve($groupId)
    {
        $users = User::where('id', auth()->user()->id)
            ->whereJsonContains('id_grupos', "$groupId")
            ->get();
        if ($users->count() == 0) {
            $this->errorMessages['invalidUser'] = [
                'Você não tem permissão para aprovar este processo'
            ];
            return;
        }

        if ($this->pending) {
            $this->correctPendingGroup = $groupId;
            $this->dispatch('showModal', 'modalPending');
            return;
        }
        ApprovedProcesso::create(
            [
                'id_processo' => $this->processId,
                'id_usuario' => auth()->user()->id,
                'id_grupo' => $groupId,
                'id_processo_vencimento_valor' => $this->pvvId
            ]
        );

        $this->createHistory('Aprovou o processo');
    }

    public function validateApprovals($groups, $approvals)
    {
        // dd($groups, $approvals);
        $this->approvals = [];
        foreach ($groups as $group) {
            foreach ($approvals as $key => $approval) {
                if ($approval->count() > 0) {
                    if ($group->id == $approval[0]->id_grupo) {
                        $this->approvals[$group->id] = [
                            'groupName' => $group->nome,
                            'approvedBy' => $approval[0]['u_nome'],
                            'approvedAt' => $approval[0]['created_at'],
                            'groupId' => $group->id,
                            'canApprove' => false,
                            'approved' => true
                        ];
                        break;
                    } else {
                        $this->approvals[$group->id] = [
                            'groupName' => $group->nome,
                            'approvedBy' => null,
                            'approvedAt' => null,
                            'groupId' => $group->id,
                            'canApprove' => $this->userCanApprove($group->id),
                            'approved' => false
                        ];
                    }
                } else {
                    $this->approvals[$group->id] = [
                        'groupName' => $group->nome,
                        'approvedBy' => null,
                        'approvedAt' => null,
                        'groupId' => $group->id,
                        'canApprove' => $this->userCanApprove($group->id),
                        'approved' => false
                    ];
                }
            }
        }
        $this->allApproved();
        if ($this->allApproved) {
            ProcessoVencimentoValor::where('id', $this->pvvId)
                ->update(['aprovado' => true]);
        }
        return;
    }

    /*public function allApproved()
    {
        $allTrue = true;
        $allFalse = true;

        foreach ($this->approvals as $approval) {
            if (!$approval['approved']) {
                $allFalse = false;
            }
            if ($approval['approved']) {
                $allTrue = false;
            }

            if (!$allTrue && !$allFalse) {
                $this->allApproved = 'partial';
                return;
            }
        }

        if($allTrue){
            $this->allApproved = true;
            return;
        }
        if($allFalse){
            $this->allApproved = false;
            return;
        }

        $this->allApproved = 'partial';
        return;

    }*/
    public function allApproved()
    {
        $this->allApproved = true;
        foreach ($this->approvals as $approval) {
            if (!$approval['approved']) {
                $this->allApproved = false;
            }
        }
    }

    private function userCanApprove($groupId)
    {
        $user = auth()->user();

        if ($user && $user->id_grupos) {
            $groupIds = json_decode($user->id_grupos, true);
            return in_array($groupId, $groupIds);
        }

        return false;
    }

    public $expensesHistoryQuartely = [];
    public $paidExpensesHistoryQuartely = [];
    public function render()
    {
        $getProcesso = new Processo();
        $processo = $getProcesso->getProcessoShow($this->processId, $this->pvvDtv);
        if ($processo->id_empresa != auth()->user()->id_empresa) {
            abort(403);
        }
        $processHistrory = $this->getProcessHistory();
        // dd($processHistrory);
        $this->setApprovals($processo);
        if($this->bankId == null){
            $this->toProperties($processo);
        }
        $this->allApproved();
        $this->dispatch('renderChart');
        $this->expensesHistoryQuartely = (new FinancesService(auth()->user()->id_empresa))
            ->expensesQuartelyHistoryBySupplierId($this->supplierId);
        $this->paidExpensesHistoryQuartely = (new FinancesService(auth()->user()->id_empresa))
            ->paidExpensesQuartelyHistoryBySupplierId($this->supplierId);
        if ($this->doc == null) {
            $this->setPdf($this->docList[0]);
        }

        return view(
            'livewire.approvals.approvals',
            [
                'processHistrory' => $processHistrory
            ]
        );
    }

    public $amountWithLateInterestAndDiscount = null;

    public function calculateAmountWithLateInterestAndDiscount()
    {
        $amount = FormatUtils::formatMoneyDb($this->installmentValue);
        $lateInterest = !is_string($this->interest) ? $this->interest : FormatUtils::formatMoneyDb($this->interest);
        $fine = !is_string($this->fine) ? $this->fine : FormatUtils::formatMoneyDb($this->fine);
        $discount = !is_string($this->discount) ? $this->discount : FormatUtils::formatMoneyDb($this->discount);
        $amountWithLateInterestAndDiscount = $amount + $lateInterest + $fine - $discount;
        $this->amountWithLateInterestAndDiscount = FormatUtils::formatMoney($amountWithLateInterestAndDiscount);
    }

    public function payBilling()
    {

        $this->calculateAmountWithLateInterestAndDiscount();
        $this->dispatch('payAccount', $this->processId, $this->pvvId);
    }

    public $interest = null;
    public $fine = null;
    public $discount = null;
    public $paymentMethodId = null;
    public $bankId = null;
    public $accountFiles = [];
    public $accountFilesType = [];
    public $accountFilesDescription = [];
    public $observation = null;

    public function payAccount()
    {
        if($this->paymentMethodId == null){
            $this->errorMessages['emptyPaymentMethod'] = [
                'Favor selecionar uma forma de pagamento'
            ];
            return;
        }

        $upload = new UploadService();
        $filesArray = $upload->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );


        $filesArray = $upload->mergeFiles($this->files_type_description, $filesArray);
        $account = new Account();
        $result = $account->payAccount(
            [
                'id_processo' => $this->processId,
                'id_processo_vencimento_valor' => $this->pvvId,
                'valor_pago' => $this->amountWithLateInterestAndDiscount,
                'juros' => $this->interest,
                'multa' => $this->fine,
                'desconto' => $this->discount,
                'data_pagamento' => $this->paymentDate,
                'forma_pagamento' => $this->paymentMethodId,
                'id_banco' => $this->bankId,
                'files' => $filesArray,
                'observacao' => $this->observation
            ]
        );

        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if (isset($result->success)) {
            session()->flash('success', 'Conta paga com sucesso');
            $this->dispatch('closeModal', 'modalPayment');
            $this->createHistory('Pagou o processo');
            $this->render();
        }
    }

    public $bankName = null;
    public function selectBank($id, $name)
    {
        $this->bankName = $name;
        $this->bankId = $id;
    }

    public $pagamento_id = null;
    public $paymentMethodName = null;

    public function editPayment()
    {
        $account = $this->detailedAccountData;
        $this->pagamento_id = $account['id_pagamento'];
        $this->installmentValue = FormatUtils::formatMoney($account['vparcela']);
        $this->paymentDate = date('Y-m-d', strtotime($account['a_data_pagamento']));
        $this->paymentMethodId = $account['id_forma_pagamento'];
        $this->paymentMethodName = $account['forma_pagamento_nome'];
        $this->bankName = $account['banco_nome'];
        $this->bankId = $account['id_banco'];
        $this->amountWithLateInterestAndDiscount = FormatUtils::formatMoney($account['valor_pago']);
        $this->interest = FormatUtils::formatMoney($account['juros']);
        $this->fine = FormatUtils::formatMoney($account['multas']);
        $this->discount = FormatUtils::formatMoney($account['descontos']);
        $this->observation = $account['p_observacao'];
        $this->pay = $account['pago'];
        $this->dispatch('editPayment');
        $this->render();
    }

    public function updatePayment()
    {
        $uploads = new UploadService();
        $filesArray = $uploads->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );
        $filesArray = $uploads->mergeFiles($this->files_type_description, $filesArray);
        $account = new Account();
        $result = $account->updatePayment(
            [
                'id_pagamento' => $this->pagamento_id,
                'id_processo' => $this->processId,
                'id_processo_vencimento_valor' => $this->pvvId,
                'valor_pago' => $this->amountWithLateInterestAndDiscount,
                'juros' => $this->interest,
                'multa' => $this->fine,
                'desconto' => $this->discount,
                'data_pagamento' => $this->paymentDate,
                'forma_pagamento' => $this->paymentMethodId,
                'id_banco' => $this->bankId,
                'files' => $filesArray,
                'observacao' => $this->observation
            ]
        );

        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if (isset($result->success)) {
            $this->pay = null;
            session()->flash('success', 'Pagamento atualizado com sucesso');
            $this->dispatch('closeModal', 'modalUpdatePayment');
            $this->createHistory('Atualizou dados do processo');
            $this->render();
        }
    }

    public $processObservation = [];
    public $commentSortDirection = 'asc';
    public $searchUser = null;
    public $usersList = [];
    public $selectedUser = 'Usuários';

    public function selectUser($user, $userName)
    {
        $this->searchUser = $user == 0 ? null : $user;
        $this->selectedUser = $userName;
    }

    public function sortDirection()
    {
        $this->commentSortDirection = $this->commentSortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function showProcessComments()
    {
        $processObservation = ObservacaoProcesso::select('users.name as user_name', 'observacao_processo.observacao', 'observacao_processo.created_at')
            ->where('id_processo', $this->processId)
            ->join('users', 'users.id', 'observacao_processo.id_usuario')
            ->orderBy('observacao_processo.created_at', $this->commentSortDirection);

        if ($this->searchUser != null) {
            $processObservation = $processObservation->where('users.id', $this->searchUser);
        }

        $processObservation = $processObservation->get();

        $this->processObservation = $processObservation->toArray();

        $this->dispatch('showModal', 'modalProcessComment');
    }

    public $processComment = null;

    public function saveComment()
    {
        if ($this->processComment == null) {
            return $this->errorMessages['emptyComment'] = [
                'Comentário não pode ser vazio'
            ];
        }
        ObservacaoProcesso::create(
            [
                'id_processo' => $this->processId,
                'id_usuario' => auth()->user()->id,
                'observacao' => $this->processComment
            ]
        );
        $this->processComment = null;
        $this->createHistory('Adicionou comentário ao processo ' . $this->processComment);
        $this->showProcessComments();

        return;
    }

    public function showUploadFiles()
    {
        $this->dispatch('showModal', 'modalUploadFiles');
    }

    public function addInput()
    {
        $this->inputs[] = '';
    }

    public function removeInput($index)
    {
        unset($this->inputs[$index]);
        unset($this->accountFilesDescription[$index + 1]);
        $this->inputs = array_values($this->inputs);
        $this->accountFilesDescription = array_values($this->accountFilesDescription);
    }

    public $modalAccountFiles = [];

    public function uploadFiles()
    {
        $this->errorMessages['emptyFileType'] = null;
        $this->errorMessages['emptyFile'] = null;
        $upload = new UploadService();
        if (empty($this->accountFilesType)) {
            $this->errorMessages['emptyFileType'] = [
                'Tipo de arquivo não pode ser vazio'
            ];
        }
        if (empty($this->accountFiles)) {
            $this->errorMessages['emptyFile'] = [
                'Favor selecionar um arquivo'
            ];
        }
        if (isset($this->errorMessages['emptyFileType']) or isset($this->errorMessages['emptyFile'])) {
            return;
        }

        foreach ($this->accountFiles as $key => $file) {
            if ($file == '' or empty($file) or is_null($file)) {
                $this->errorMessages['emptyFile'][$key] = [
                    'Favor selecionar um arquivo'
                ];
            }
        }
        if (isset($this->errorMessages['emptyFile'])) {
            return;
        }
        foreach ($this->accountFilesType as $key => $type) {
            if ($type == '' or empty($type) or is_null($type)) {
                $this->errorMessages['emptyFileType'][$key] = [
                    'Tipo de arquivo não pode ser vazio'
                ];
            }
        }
        if (isset($this->errorMessages['emptyFileType']) or isset($this->errorMessages['emptyFile'])) {
            return;
        }
        $filesArray = $upload->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );
        $historyFiles = [];
        foreach ($filesArray as $key => $file) {
            $historyFiles[] = $file['fileName'];
        }

        $filesArray = $upload->mergeFiles($this->files_type_description, $filesArray);
        $processo = new Processo();
        $processo
            ->where('id', $this->processId)
            ->update(['files_types_desc' => $filesArray]);
        $this->createHistory('Adicionou arquivos ao processo ' . implode(', ', $historyFiles));
        $this->dispatch('closeModal', 'modalUploadFiles');
    }

    public $addToDoComment = null;
    public $processAddToDo = [];

    public function showAddToDo()
    {
        $processAddToDo = PendenciaProcesso::select(
            'users.name as user_name',
            'processo_pendencia.observacao',
            'processo_pendencia.id_usuario_email',
            'processo_pendencia.created_at'
        );
        if ($this->searchUser != null) {
            $processAddToDo = $processAddToDo->where('id_processo', $this->processId);
        }
        $processAddToDo = $processAddToDo
            ->join('users', 'users.id', 'processo_pendencia.id_usuario')
            ->orderBy('processo_pendencia.created_at', 'desc')
            ->get();
        $this->processAddToDo = $processAddToDo->toArray();
        $this->createHistory('Visualizou pendências do processo');
        $this->dispatch('showModal', 'modalAddToDo');
    }

    public $idUserEmail = [];

    public function saveAddToDo()
    {
        if ($this->addToDoComment == null) {
            return $this->errorMessages['emptyComment'] = [
                'Comentário não pode ser vazio'
            ];
        }

        ApprovedProcesso::where('id_processo', $this->processId)
            ->where('id_processo_vencimento_valor', $this->pvvId)
            ->delete();

        Processo::where('id', $this->processId)
            ->update(['pendencia' => true]);

        ProcessoVencimentoValor::where('id', $this->pvvId)
            ->update(['aprovado' => false]);

        PendenciaProcesso::create(
            [
                'id_processo' => $this->processId,
                'id_processo_vencimento_valor' => $this->pvvId,
                'id_usuario' => auth()->user()->id,
                'id_usuario_email' => json_encode($this->idUserEmail),
                'observacao' => $this->addToDoComment
            ]
        );

        ObservacaoProcesso::create(
            [
                'id_processo' => $this->processId,
                'id_usuario' => auth()->user()->id,
                'observacao' => $this->addToDoComment
            ]
        );

        $this->addToDoComment = null;
        $this->createHistory('Adicionou pendência ao processo ' . $this->addToDoComment);
        $this->showAddToDo();

        return;
    }

    public function showDeleteProcess()
    {
        $this->dispatch('showModal', 'modalDeleteProcess');
    }

    public function deleteProcess()
    {
        Processo::where('id', $this->processId)->update([
            'deletado' => true
        ]);

        //ProcessoVencimentoValor::where('id_processo', $this->pvvId)->delete();

        (new LogsNumber())->saveLog(
            [
                'id_empresa' => auth()->user()->id_empresa,
                'message' => 'Processo ' . $this->processId . ' foi deletado pelo usuario ' . auth()->user()->name
            ]
        );
        $this->createHistory('Deletou o processo');

        return redirect()->route('payment-request.index')->with('success', 'Processo deletado com sucesso');
    }

    public function showProcessHistory()
    {
        $this->dispatch('showModal', 'modalProcessHistory');
    }

    public function getProcessHistory()
    {
        $processHistory = ProcessoHistorico::select(
            'users.name as user_name',
            'processo_historico.acao',
            'processo_historico.created_at'
        )
            ->where('id_processo', $this->processId)
            ->join('users', 'users.id', 'processo_historico.id_usuario');
        if ($this->searchUser != null) {
            $processHistory = $processHistory->where('users.id', $this->searchUser);
        }
        $processHistory = $processHistory
            ->orderBy('processo_historico.created_at', $this->commentSortDirection)
            ->paginate(20);
        return $processHistory;
    }

    public function showEditAccount()
    {
        $this->initializeProperties($this->traceCode);
        $this->dispatch('showModal', 'modalEditAccount');
    }

    public $setToOpenId = null;
    public $setToOpenPvvId = null;

    public function askSetToOpen($id, $pvvId)
    {
        $this->setToOpenId = $id;
        $this->setToOpenPvvId = $pvvId;
        $this->dispatch('showModal', 'modalSetToOpen');
    }

    public function cancelAskSetToOpen()
    {
        $this->setToOpenId = null;
        $this->setToOpenPvvId = null;
    }

    public function setToOpen()
    {
        $account = new Account();
        $account->setToOpen($this->setToOpenId, $this->setToOpenPvvId);
        $this->createHistory('Removeu pagamento do processo '.$this->setToOpenId.' '.$this->setToOpenPvvId);
    }

    public $fileDeleteLineId = null;
    public $fileNameToDelete = null;
    public function askDeleteFile($fileName, $lineId)
    {
        $this->fileDeleteLineId = $lineId;
        $this->fileNameToDelete = $fileName;
        $this->dispatch('showModal', 'modalAskDeleteFile');
    }

    public function deleteFile()
    {
        $filesArray = json_decode($this->files_type_description, true);
        foreach($filesArray as $index => $file){
            if($file['fileName'] == $this->fileNameToDelete){
                unset($filesArray[$index]);
            }
        }
        $this->files_type_description = json_encode(array_values($filesArray));
        Processo::where('id', $this->id)->where('id_empresa', auth()->user()->id_empresa)->update([
            'files_types_desc' => $this->files_type_description
        ]);
        $this->dispatch('fileDeleted', $this->fileDeleteLineId);
    }
}
