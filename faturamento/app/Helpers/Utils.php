<?php

namespace App\Helpers;

use App\Models\ApprovedProcesso;
use App\Models\Empresa;
use App\Models\Fornecedor;
use App\Models\WorkFlow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Random;

use function PHPUnit\Framework\isJson;

class Utils
{
    public static function findFilialIdInArraySession($array, $search)
    {
        if (!isJson($array)) {
            return false;
        }

        foreach ($array as $data) {
            if ($data->f_id === $search) {
                return true;
            }
        }
        return false;
    }

    public static function isDateFromDatabaseLessThanToday($dataBanco)
    {
        // Converte a data do banco de dados para um objeto Carbon
        $dataBanco = Carbon::parse($dataBanco);

        // Data atual
        $dataAtual = Carbon::today();

        // Compara as datas
        return $dataBanco->lt($dataAtual);
    }

    public static function monthNoValueList($monthList)
    {
        $months = collect(range(1, 12))->map(
            function ($month) {
                return Carbon::create(null, $month, 1)->format('Y-m');
            }
        );

        $list = $months->mapWithKeys(
            function ($month) use ($monthList) {
                return [$month => $monthList->get($month, 0)];
            }
        );

        $total = $list->sum();

        return $list->push($total);
    }

    public static function yearValueList($years, $range)
    {
        // Extrair apenas os anos do intervalo fornecido
        $startYear = (int) substr($range[0], 0, 4);
        $endYear = (int) substr($range[1], 0, 4);

        // Transformar o array de anos em uma coleção com os valores associados aos anos
        $yearsCollection = collect($years);

        // Mapear o range de anos e associar os valores correspondentes ou 0 se não existir
        $list = collect(range($startYear, $endYear))->mapWithKeys(
            function ($year) use ($yearsCollection) {
                return [$year => $yearsCollection->get($year, 0)];
            }
        );

        // Calcular o total dos valores
        $total = $list->sum();

        // Adicionar o total ao final da lista
        $list->put('total', $total);

        return $list;
    }

    public static function sumBimonthly($meses)
    {
        foreach ($meses as $key => $categoria) {
            $bimestres[$key] = self::sumGroups(2, $categoria);
        }
        return $bimestres;
    }

    public static function sumGroups(int $groups, $months)
    {
        is_array($months) ?: $months = $months->toArray();
        $result = [];
        $chunkMonths = array_chunk($months, $groups, true);
        $counter = 1;
        $generalTotal = 0;
        foreach ($chunkMonths as $group) {
            $total = array_sum($group);
            $result["group{$counter}"] = $total;
            $generalTotal += $total;
            $counter++;
            if ($groups == 2) {
                if ($counter > 6) {
                    break;
                }
            }
            if ($groups == 3) {
                if ($counter > 4) {
                    break;
                }
            }
            if ($groups == 6) {
                if ($counter > 2) {
                    break;
                }
            }
        }
        $result['total'] = $generalTotal;
        return $result;
    }

    public static function sumQuarterly($meses)
    {
        foreach ($meses as $key => $categoria) {
            $quarterly[$key] = self::sumGroups(3, $categoria);
        }
        return $quarterly;
    }

    public static function sumSemester($meses)
    {
        foreach ($meses as $key => $categoria) {
            $semester[$key] = self::sumGroups(6, $categoria);
        }
        return $semester;
    }

    public static function isCnpj($value)
    {
        return preg_match('/^[0-9]{14}$/', $value);
    }

    public static function isCpf($value)
    {
        return preg_match('/^[0-9]{11}$/', $value);
    }

    public static function existsSupplier($cnpj)
    {
        $supplier = Fornecedor::where('id_empresa', auth()->user()->id_empresa)
            ->where('cpf_cnpj', $cnpj)
            ->get();

        if ($supplier->count() > 0) {
            return true;
        }

        return false;
    }

    public static function calculateDetailPayment($parcelas)
    {
        foreach ($parcelas as $parcela) {
            $valorPago[] = $parcela->pago ? $parcela->vparcela : 0;
            $valorPagar[] = $parcela->pago ? 0 : $parcela->vparcela;
            $jurosPago[] = $parcela->juros ?? 0;
            $multasPago[] = $parcela->multas ?? 0;
            $descontosPago[] = $parcela->descontos ?? 0;
        }

        $result['valorPago'] = array_sum($valorPago);
        $result['valorPagar'] = array_sum($valorPagar);
        $result['jurosPago'] = array_sum($jurosPago);
        $result['multasPago'] = array_sum($multasPago);
        $result['descontosPago'] = array_sum($descontosPago);

        return $result;
    }

    public static function identifyPayment($parcelas)
    {
        $installments = [];
        foreach ($parcelas as $key => $parcela) {
            $installments[$key] = [
                'data' => date('Y-m-d', strtotime($parcela->pvv_dtv)),
                'valor' => FormatUtils::formatMoney($parcela->vparcela),
                'status' => $parcela->pago ?? false,
                'id' => $parcela->pvv_id,
                'markedToDelete' => false
            ];
        }
        return $installments;
    }

    public static function calculateApprovedAccounts($accounts)
    {
        $approval_progress = [];
        foreach ($accounts as $account) {
            $distinctCombinations = DB::table('approved_processo')
                ->select('id_usuario', 'id_grupo')
                ->where('id_processo', $account->id)
                ->where('id_processo_vencimento_valor', $account->pvv_id)
                ->distinct();

            $accountApproved = DB::table(DB::raw("({$distinctCombinations->toSql()}) as distinct_combinations"))
                ->mergeBindings($distinctCombinations)  // Necessário para mesclar os bindings da subquery
                ->count();

            $workflow = WorkFlow::select('id_grupos')
                ->where('id', $account->p_workflow_id)
                ->first();

            $accountWorkflowGroupsCount = count(json_decode($workflow->id_grupos));

            if ($accountWorkflowGroupsCount > 0) {
                $approval_progress[$account->id . ' ' . date('Y-m-d', strtotime($account->pvv_dtv))] = [
                    'approved' => $accountApproved,
                    'total' => $accountWorkflowGroupsCount,
                    'percentual' => ($accountApproved / $accountWorkflowGroupsCount) * 100
                ];
            } else {
                $approval_progress[$account->id . ' ' . date('Y-m-d', strtotime($account->pvv_dtv))] = [
                    'approved' => $accountApproved,
                    'total' => $accountWorkflowGroupsCount,
                    'percentual' => ($accountApproved / $accountWorkflowGroupsCount) * 100
                ];
            }
        }

        return $approval_progress;
    }

    public static function whoApproved($account)
    {
        $usersAndGroups = ApprovedProcesso::select('users.name as user_name', 'grupo_processos.nome as group_name')
            ->leftJoin('users', 'users.id', 'approved_processo.id_usuario')
            ->leftJoin('grupo_processos', 'grupo_processos.id', 'approved_processo.id_grupo')
            ->where('id_processo', $account->id)
            ->where('id_processo_vencimento_valor', $account->pvv_id)
            ->distinct()
            ->get();
        return $usersAndGroups;
    }
}
