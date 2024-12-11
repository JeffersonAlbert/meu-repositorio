<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\Fornecedor;
use Hamcrest\Util;
use Illuminate\Support\Facades\Http;

class Autocomplete
{
    public function __construct()
    {
        //
    }

    public function organizeSupplierDataDB($existsSupplier)
    {
        $supplier = [
            'razao_social' => $existsSupplier->razao_social ?? null,
            'nome_fantasia' => $existsSupplier->nome,
            'inscricao_estadual' => $existsSupplier->inscricao_estadual ?? null,
            'inscricao_municipal' => $existsSupplier->inscricao_municipal ?? null,
            'cep' => $existsSupplier->cep ?? null,
            'endereco' => $existsSupplier->endereco,
            'numero' => $existsSupplier->numero,
            'cidade' => $existsSupplier->cidade,
            'complemento' => $existsSupplier->complemento,
            'bairro' => $existsSupplier->bairro,
            'estado' => $existsSupplier->uf ?? null,
            'telefone' => $existsSupplier->telefone ?? null,
            'email' => $existsSupplier->email ?? null,
        ];

        return response()->json([
            'status' => 'success',
            'data' => json_encode($supplier),
            'message' => 'Dados do fornecedor encontrados'
        ]);
    }
    public function autocompleteSupplierData($cnpj)
    {
        $supplier = Utils::existsSupplier($cnpj);

        if($supplier){
            return response()->json([
                'status' => 'error',
                'data' => json_encode($supplier),
                'message' => 'Fornecedor já cadastrado'
            ], 402);
        }

        if(Utils::isCpf($cnpj)){
            return response()->json([
                'status' => 'error',
                'message' => 'CPF precisa ser cadastrado manualmente'
            ], 402);
        }

        $existsSupplier = Fornecedor::where('cpf_cnpj', $cnpj)
            ->first();

        if($existsSupplier){
            return $this->organizeSupplierDataDB($existsSupplier);
        }

        return $this->getSupplierData($cnpj);
    }

    public function getSupplierData($cnpj)
    {
        $url = "https://publica.cnpj.ws/cnpj/".str_replace(['/','-','.'], ['','',''], $cnpj);
        $response = Http::get($url);

        if ($response->successful()) {
            return $this->organizeSupplierDataJson($response->body());
        }
        if(!$response->successful()){
            $error = $response->status();
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao buscar dados do fornecedor'
            ], 402);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao buscar dados do fornecedor'
        ], 402);
    }

    public function organizeSupplierDataJson($dataJson)
    {
        $data = json_decode($dataJson);

        if(isset($data->error)){
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao buscar dados do fornecedor: '.$data->error
            ], 402);
        }

        $dataObject = json_decode($dataJson);
        $supplier = [
            'razao_social' => $dataObject->razao_social,
            'nome_fantasia' => $dataObject->estabelecimento->nome_fantasia ?? null,
            'inscricao_estadual' => $dataObject->estabelecimento->inscricoes_estaduais->inscricao_estadual ?? null,
            'inscricao_municipal' => $dataObject->estabelecimento->inscricoes_municipais->inscricao_municipal ?? null,
            'cep' => $dataObject->estabelecimento->cep,
            'endereco' => $dataObject->estabelecimento->tipo_logradouro.' '.$dataObject->estabelecimento->logradouro,
            'numero' => $dataObject->estabelecimento->numero,
            'cidade' => $dataObject->estabelecimento->cidade->nome,
            'complemento' => $dataObject->estabelecimento->complemento,
            'bairro' => $dataObject->estabelecimento->bairro,
            'estado' => $dataObject->estabelecimento->estado->sigla,
            'telefone' => $dataObject->estabelecimento->telefone1 ?? null,
            'email' => $dataObject->estabelecimento->email ?? null,
        ];

        return response()->json([
            'status' => 'success',
            'data' => json_encode($supplier),
            'message' => 'Dados do fornecedor encontrados'
        ]);
    }
}
