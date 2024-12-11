<?php
namespace App\Services;

use App\Helpers\FormatUtils;
use App\Models\Pagamentos;
use App\Models\Processo;
use App\Models\ProcessoHistorico;
use App\Models\ProcessoVencimentoValor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Account
{
    public $tax = false;
    public function __construct()
    {
        //
    }

    public function rules()
    {
        $rules = [
            'trace_code' => 'required',
            'id_usuario' => 'required',
            'id_empresa' => 'required',
            'id_workflow' => 'required',
            'numero_nota' => 'required',
            'emissao_nota' => 'required',
            'valor' => 'required',
            'tipo_cobranca' => 'required',
            'condicao' => 'required',
            'parcelas' => 'required',
            'id_sub_dre' => 'required',
            'competencia' => 'required',
            ''
        ];
        if($this->tax == false){
            $rules['id_fornecedor'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'trace_code.required' => 'O campo trace_code é obrigatório',
            'id_usuario.required' => 'O campo id_usuario é obrigatório',
            'id_empresa.required' => 'O campo id_empresa é obrigatório',
            'id_workflow.required' => 'O campo Workflow é obrigatório',
            'numero_nota.required' => 'O campo Numero nota fiscal é obrigatório',
            'emissao_nota.required' => 'O campo Emissão nota é obrigatório',
            'valor.required' => 'O campo Valor total da nota é obrigatório',
            'tipo_cobranca.required' => 'O campo Tipo de cobrança é obrigatório',
            'condicao.required' => 'O campo Condição é obrigatório',
            'parcelas.required' => 'O campo Valor da 1ª parcela é obrigatório',
            'id_sub_dre.required' => 'O campo Categoria financeira é obrigatório',
            'competencia.required' => 'O campo Competência é obrigatório',
        ];

        if($this->tax == false){
            $messages['id_fornecedor.required'] = 'O campo Fornecedor é obrigatório';
        }
        return $messages;
    }

    public function data2Db($data, $update = false)
    {
        $data = [
            'trace_code' => $data['trace_code'],
            'id_user' => $data['id_usuario'],
            'id_empresa' => $data['id_empresa'],
            'id_workflow' => $data['id_workflow'],
            'numero_nota' => $data['numero_nota'],
            'emissao_nota' => $data['emissao_nota'],
            'valor' => $data['valor'],
            'tipo_cobranca' => $data['tipo_cobranca'],
            'condicao' => $data['condicao'],
            'parcelas' => $data['parcelas'],
            'id_sub_dre' => $data['id_sub_dre'],
            'competencia' => $data['competencia'],
            'id_fornecedor' => $data['impostos'] == false ? $data['id_fornecedor'] : 0,
            'files_types_desc' => $data['files_types_desc'],
            'dt_parcelas' => $data['dt_parcelas'],
            'user_ultima_alteracao' => $data['id_usuario'],
            'doc_name' => json_encode([null]),
            'created_user' => $data['id_usuario'],
            'logs' => json_encode([null]),
            'observacao' => $data['observacao'] ?? null,
        ];

        return $data;
    }
    public function updateAccount(array $data)
    {
        !$data['impostos'] ? $this->tax = false : $this->tax = true;

        $rules = $this->rules();
        $messages = $this->messages();
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return (object)[
                'errors' => $validator->errors()->toArray()
            ];
        }

        $data2Db = $this->data2Db($data);
        $account = Processo::where('id', $data['id'])->update($data2Db);

        /*$this->updateInstallments(
            $data['id'],
            $data['dt_parcelas'],
            $data['pay'],
            true
        );*/

        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Processo '.$data['id'].' foi atualizado'
        ]);

        return (object)[
            'success' => true
        ];
    }

    public function updateInstallments($qtd, $ids, $dates, $values, $processId)
    {
        foreach(range(0, ($qtd-1)) as $key){
            if(isset($ids[$key]) and isset($dates[$key]) and isset($values[$key])){
                ProcessoVencimentoValor::where('id', $ids[$key])->update([
                    'data_vencimento' => $dates[$key],
                    'price' => FormatUtils::formatMoneyDb($values[$key])
                ]);
            }
            if($ids[$key] == null and isset($dates[$key]) and isset($values[$key])){
                ProcessoVencimentoValor::create([
                    'id_processo' => $processId,
                    'data_vencimento' => $dates[$key],
                    'price' => FormatUtils::formatMoneyDb($values[$key])
                ]);
            }
        }
       return;
    }

    public function storeAccount(array $data)
    {
        !$data['impostos'] ? $this->tax = false : $this->tax = true;

        $rules = $this->rules();
        $messages = $this->messages();
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return (object)[
                'errors' => $validator->errors()->toArray()
            ];
        }

        $data2Db = $this->data2Db($data);
        $account = Processo::create($data2Db);

        $this->storeInstallments($account->id, $data['dt_parcelas'], $data['pay'], false);

        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Processo '.$account->id.' foi cadastrado'
        ]);

        return (object)[
            'success' => true
        ];
    }

    public function storeInstallments($id, $installmentsDatesAndValues, $paid, $update)
    {
        if($update){
            ProcessoVencimentoValor::where('id_processo', $id)
                ->whereNull('pago')
                ->orWhere('pago', false)
                ->delete();
            (new LogsNumber())->saveLog([
                'id_empresa' => auth()->user()->id_empresa,
                'message' => 'Parcelas do processo '.$id.' foram atualizadas'
            ]);
        }

        !$paid ? $paid = 0 : $paid = 1;
        foreach(json_decode($installmentsDatesAndValues, true) as $key => $installment){
            ProcessoVencimentoValor::create([
                'id_processo' => $id,
                'data_vencimento' => $installment["data{$key}"],
                'price' => $installment["valor{$key}"],
                'pago' => $paid,
            ]);
        }
        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Parcelas do processo '.$id.' foram cadastradas'
        ]);
        return;
    }

    public function deleteAccount($id)
    {
        Processo::where('id', $id)->update(['deletado' => true]);
        ProcessoVencimentoValor::where('id_processo', $id)->delete();
        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Processo '.$id.' foi deletado'
        ]);
        return (object)[
            'success' => true
        ];
    }

    public function deleteInstallment($id, $pvvId)
    {
        $dtParcelas = Processo::select('dt_parcelas')->find($id);
        $pvvDtv = ProcessoVencimentoValor::where('id', $pvvId)->first()->data_vencimento;
        $result = [];
        foreach(json_decode($dtParcelas->dt_parcelas, true) as $key => $value){
            if($value["data{$key}"] !== date('Y-m-d', strtotime($pvvDtv))){
                //dd($value);
                $result[] = $value;
            }
        }

        foreach($result as $key => $value){
            foreach($value as $k => $v){
                if(strstr($k, 'data')){
                    $newDtParcelas[$key]['data' . $key] = $v;
                }
                if(strstr($k, 'valor')) {
                    $newDtParcelas[$key]['valor' . $key]  = $v;
                }
            }
        }

        ProcessoVencimentoValor::where('id_processo', $id)->where('id', $pvvId)->delete();
        $valorTotal = ProcessoVencimentoValor::where('id_processo', $id)->sum('price');
        Processo::where('id', $id)->update([
            'dt_parcelas' => json_encode($result),
            'valor' => $valorTotal
            ]);
        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Parcela do processo '.$id.' foi deletada  com vencimento'
        ]);

        return (object)[
            'success' => true
        ];
    }

    public function rulesPayment()
    {
        $rules = [
            'id_processo' => 'required',
            'id_processo_vencimento_valor' => 'required',
            'valor_pago' => 'required',
            'data_pagamento' => 'required',
            'id_banco' => 'required',
        ];

        return $rules;
    }

    public function messagesPayment()
    {
        $messages = [
            'id_processo.required' => 'O campo id_processo é obrigatório',
            'id_processo_vencimento_valor.required' => 'O campo id_processo_vencimento_valor é obrigatório',
            'valor_pago.required' => 'O campo valor_pago é obrigatório',
            'data_pagamento.required' => 'O campo data_pagamento é obrigatório',
            'id_banco.required' => 'Nescessário informar o banco',
        ];

        return $messages;
    }
    public function payAccount($arrayPayment)
    {
        $rules = $this->rulesPayment();

        $messages = $this->messagesPayment();

        $validator = Validator::make($arrayPayment, $rules, $messages);
        if($validator->fails()){
            return (object)[
                'errors' => $validator->errors()->toArray()
            ];
        }

        $paymentExists = Pagamentos::where('id_processo', $arrayPayment['id_processo'])
            ->where('id_processo_vencimento_valor', $arrayPayment['id_processo_vencimento_valor'])
            ->count();

        if($paymentExists != 0) {
            return (object)[
                'errors' => [
                    'id_processo_vencimento_valor' => 'Pagamento já cadastrado'
                ]
            ];
        }
        Pagamentos::create([
            'id_empresa' => auth()->user()->id_empresa,
            'id_processo' => $arrayPayment['id_processo'],
            'id_processo_vencimento_valor' => $arrayPayment['id_processo_vencimento_valor'],
            'valor_pago' => FormatUtils::formatMoneyDb($arrayPayment['valor_pago']),
            'data_pagamento' => $arrayPayment['data_pagamento'],
            'id_banco' => $arrayPayment['id_banco'],
            'juros' => FormatUtils::formatMoneyDb($arrayPayment['juros'] ?? 0),
            'multa' => FormatUtils::formatMoneyDb($arrayPayment['multa'] ?? 0),
            'desconto' => FormatUtils::formatMoneyDb($arrayPayment['desconto'] ?? 0),
            'forma_pagamento' => $arrayPayment['forma_pagamento'] ?? null,
            'observacao' => $arrayPayment['observacao'] ?? null,
        ]);

        ProcessoVencimentoValor::where('id', $arrayPayment['id_processo_vencimento_valor'])->update([
            'pago' => 1
        ]);

        Processo::where('id', $arrayPayment['id_processo'])->update([
            'files_types_desc' => json_encode($arrayPayment['files']),
        ]);
        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Pagamento do processo '.$arrayPayment['id_processo'].' foi cadastrado pelo usuario '.auth()->user()->name
        ]);
        return (object)[
            'success' => true
        ];
    }

    public function updatePayment($arrayPayment)
    {
        $rules = $this->rulesPayment();

        $messages = $this->messagesPayment();

        $validator = Validator::make($arrayPayment, $rules, $messages);
        if($validator->fails()){
            return (object)[
                'errors' => $validator->errors()->toArray()
            ];
        }

        Pagamentos::where('id', $arrayPayment['id_pagamento'])->update([
            'valor_pago' => FormatUtils::formatMoneyDb($arrayPayment['valor_pago']),
            'data_pagamento' => $arrayPayment['data_pagamento'],
            'id_banco' => $arrayPayment['id_banco'],
            'juros' => FormatUtils::formatMoneyDb($arrayPayment['juros'] ?? 0),
            'multa' => FormatUtils::formatMoneyDb($arrayPayment['multa'] ?? 0),
            'desconto' => FormatUtils::formatMoneyDb($arrayPayment['desconto'] ?? 0),
            'forma_pagamento' => $arrayPayment['forma_pagamento'] ?? null,
            'observacao' => $arrayPayment['observacao'] ?? null,
        ]);

        Processo::where('id', $arrayPayment['id_processo'])->update([
            'files_types_desc' => json_encode($arrayPayment['files']),
        ]);

        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Pagamento do processo '.$arrayPayment['id_processo'].' foi alterado pelo usuario '.auth()->user()->name
        ]);

        return (object)[
            'success' => true
        ];
    }

    public function getAccountsPayable($startDate, $endDate, $openedOnly=null)
    {
        $totalVencidos = DB::table('processo')
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', '=', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', '=', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', '=', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', '=', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', '=', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', '=', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', '=', 'processo.id_contrato')
            ->leftJoin('pagamentos', function($join) {
                $join->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('bancos', 'bancos.id', '=', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', '=', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', '=', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', '=', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', '=', 'processo.id_sub_dre')
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', '=', 'pagamentos.forma_pagamento')
            ->whereBetween('processo_vencimento_valor.data_vencimento', [$startDate, date('Y-m-d', strtotime('-1 day'))])
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->where('processo.deletado', '!=', true)
            ->where(function($query) {
                $query->where('processo_vencimento_valor.pago', false)
                    ->orWhereNull('processo_vencimento_valor.pago');
            })
            ->sum('processo_vencimento_valor.price');

        $totalVencemHoje = DB::table('processo')
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', '=', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', '=', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', '=', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', '=', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', '=', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', '=', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', '=', 'processo.id_contrato')
            ->leftJoin('pagamentos', function($join) {
                $join->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('bancos', 'bancos.id', '=', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', '=', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', '=', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', '=', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', '=', 'processo.id_sub_dre')
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', '=', 'pagamentos.forma_pagamento')
            ->whereBetween('processo_vencimento_valor.data_vencimento', [date('Y-m-d'), date('Y-m-d')])
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->where('processo.deletado', '!=', true)
            ->where(function($query) {
                $query->where('processo_vencimento_valor.pago', false)
                    ->orWhereNull('processo_vencimento_valor.pago');
            })
            ->sum('processo_vencimento_valor.price');

        $totalAVencer = DB::table('processo')
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', '=', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', '=', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', '=', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', '=', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', '=', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', '=', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', '=', 'processo.id_contrato')
            ->leftJoin('pagamentos', function($join) {
                $join->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('bancos', 'bancos.id', '=', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', '=', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', '=', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', '=', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', '=', 'processo.id_sub_dre')
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', '=', 'pagamentos.forma_pagamento')
            ->whereBetween('processo_vencimento_valor.data_vencimento', [date('Y-m-d', strtotime('+1 day')), $endDate])
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->where('processo.deletado', '!=', true)
            ->where(function($query) {
                $query->where('processo_vencimento_valor.pago', false)
                    ->orWhereNull('processo_vencimento_valor.pago');
            })
            ->sum('processo_vencimento_valor.price');

        $totalPagos = DB::table('processo')
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', '=', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', '=', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', '=', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', '=', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', '=', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', '=', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', '=', 'processo.id_contrato')
            ->leftJoin('pagamentos', function($join) {
                $join->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('bancos', 'bancos.id', '=', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', '=', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', '=', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', '=', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', '=', 'processo.id_sub_dre')
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', '=', 'pagamentos.forma_pagamento')
            ->whereBetween('processo_vencimento_valor.data_vencimento', [$startDate, $endDate])
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->where('processo.deletado', '!=', true)
            ->where(function($query) {
                $query->where('processo_vencimento_valor.pago', true);
            })
            ->sum('processo_vencimento_valor.price');

        return (object)[
            'totalVencidos' => $totalVencidos,
            'totalVencemHoje' => $totalVencemHoje,
            'totalAVencer' => $totalAVencer,
            'totalPagos' => $totalPagos = $openedOnly == true ? 0 : $totalPagos,
            'totalGeral' => $totalVencidos + $totalVencemHoje + $totalAVencer + $totalPagos
        ];
    }

    public function setToOpen($id, $pvvId)
    {
        ProcessoVencimentoValor::where('id_processo', $id)
            ->where('id', $pvvId)
            ->update(
                [
                    'pago' => false
                ]
            );
        Pagamentos::where('id_processo', $id)
            ->where('id_processo_vencimento_valor', $pvvId)
            ->delete();

        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Usuario '.auth()->user()->name.' removeu pagamento do processo ' . $id,
        ]);
    }
}
