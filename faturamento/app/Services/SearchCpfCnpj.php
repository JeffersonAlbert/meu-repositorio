<?php

namespace App\Services;

use App\Helpers\Utils;

class SearchCpfCnpj
{
    public function searchSupplierCpfCnpj($fornecedorCpfCnpj)
    {
        $autocomplete = new Autocomplete();

        if (Utils::isCnpj($fornecedorCpfCnpj)) {
            $result = $autocomplete->autocompleteSupplierData($fornecedorCpfCnpj);
        } elseif (Utils::isCpf($fornecedorCpfCnpj)) {
            $result = $autocomplete->autocompleteSupplierData($fornecedorCpfCnpj);
            return response()->json([
                'status' => 'error',
                'message' => json_decode($result->content())->message
            ], 402);
            //session()->flash('error', json_decode($result->content())->message);
        } else {
            //session()->flash('error', 'Documento inválido');
            return response()->json([
                'status' => 'error',
                'message' => 'Documento inválido'
            ], 402);
        }

        if ($result->status() == 402) {
            session()->flash('error', json_decode($result->content())->message);
            return response()->json([
                'status' => 'error',
                'message' => json_decode($result->content())->message
            ], 402);
        }

        if ($result->status() == 200) {
            $dataJson = json_decode($result->content())->data;
            $objectAutocompleteSupplier = json_decode($dataJson);
            return response()->json([
                'fornecedorEmail' => $objectAutocompleteSupplier->email,
                'fornecedorTelefone' => $objectAutocompleteSupplier->telefone,
                'fornecedorEndereco' => $objectAutocompleteSupplier->endereco,
                'fornecedorBairro' => $objectAutocompleteSupplier->bairro,
                'fornecedorCidade' => $objectAutocompleteSupplier->cidade,
                'fornecedorUf' => $objectAutocompleteSupplier->estado,
                'fornecedorCep' => $objectAutocompleteSupplier->cep,
                'fornecedorNumero' => $objectAutocompleteSupplier->numero,
                'fornecedorComplemento' => $objectAutocompleteSupplier->complemento,
                'fornecedorRazaoSocial' => $objectAutocompleteSupplier->razao_social,
                'fornecedorNomeFantasia' => $objectAutocompleteSupplier->nome_fantasia,
                'fornecedorInscricaoEstadual' => $objectAutocompleteSupplier->inscricao_estadual,
                'fornecedorInscricaoMunicipal' => $objectAutocompleteSupplier->inscricao_municipal,
            ]);
        }
    }
}
