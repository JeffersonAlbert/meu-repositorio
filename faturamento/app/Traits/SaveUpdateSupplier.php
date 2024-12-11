<?php

namespace App\Traits;

use App\Helpers\Utils;
use App\Models\Fornecedor;
use App\Services\SearchCpfCnpj;

trait SaveUpdateSupplier
{
    public $fornecedorRazaoSocial;
    public $fornecedorNomeFantasia;
    public $fornecedorCpfCnpj;
    public $fornecedorInscricaoEstadual;
    public $fornecedorInscricaoMunicipal;
    public $fornecedorCep;
    public $fornecedorEndereco;
    public $fornecedorNumero;
    public $fornecedorComplemento;
    public $fornecedorBairro;
    public $fornecedorCidade;
    public $fornecedorUf;
    public $fornecedorTelefone;
    public $fornecedorEmail;
    public $fornecedorDadosPagamento;
    public $fornecedorObservacao;

    public function rulesSaveSupplier()
    {
        return [
            'fornecedorCpfCnpj' => 'required',
            'fornecedorRazaoSocial' => 'required',
        ];
    }

    public function messagesSaveSupplier()
    {
        return [
            'fornecedorCpfCnpj.required' => 'O campo CPF/CNPJ é obrigatório',
            'fornecedorRazaoSocial.required' => 'O campo razão social é obrigatório',
        ];
    }

    public function searchSupplierCpfCnpj()
    {
        $searchCpfCnpj = new SearchCpfCnpj();
        $searchResult = $searchCpfCnpj->searchSupplierCpfCnpj($this->fornecedorCpfCnpj);
        if($searchResult->status() == 402){
            session()->flash('error', json_decode($searchResult->content())->message);
            return;
        }

        $dataObjectSupplier = json_decode($searchResult->content());
        $this->fornecedorEmail = $dataObjectSupplier->fornecedorEmail;
        $this->fornecedorTelefone = $dataObjectSupplier->fornecedorTelefone;
        $this->fornecedorEndereco = $dataObjectSupplier->fornecedorEndereco;
        $this->fornecedorBairro = $dataObjectSupplier->fornecedorBairro;
        $this->fornecedorCidade = $dataObjectSupplier->fornecedorCidade;
        $this->fornecedorUf = $dataObjectSupplier->fornecedorUf;
        $this->fornecedorCep = $dataObjectSupplier->fornecedorCep;
        $this->fornecedorNumero = $dataObjectSupplier->fornecedorNumero;
        $this->fornecedorComplemento = $dataObjectSupplier->fornecedorComplemento;
        $this->fornecedorRazaoSocial = $dataObjectSupplier->fornecedorRazaoSocial;
        $this->fornecedorNomeFantasia = $dataObjectSupplier->fornecedorNomeFantasia;
        $this->fornecedorInscricaoEstadual = $dataObjectSupplier->fornecedorInscricaoEstadual;
        $this->fornecedorInscricaoMunicipal = $dataObjectSupplier->fornecedorInscricaoMunicipal;
    }
    public function saveSupplier()
    {
        $this->validate(
            $this->rulesSaveSupplier(),
            $this->messagesSaveSupplier()
        );
        if (Utils::existsSupplier($this->fornecedorCpfCnpj)) {
            session()->flash('error', 'Fornecedor já cadastrado');
            return;
        }

        $fornecedor = new Fornecedor();
        $fornecedor->razao_social = $this->fornecedorRazaoSocial;
        $fornecedor->nome = $this->fornecedorNomeFantasia ?? $this->fornecedorRazaoSocial;
        $fornecedor->cpf_cnpj = $this->fornecedorCpfCnpj;
        $fornecedor->inscrica_estadual = $this->fornecedorInscricaoEstadual ?? null;
        $fornecedor->inscricao_municipal = $this->fornecedorInscricaoMunicipal ?? null;
        $fornecedor->cep = $this->fornecedorCep;
        $fornecedor->endereco = $this->fornecedorEndereco;
        $fornecedor->numero = $this->fornecedorNumero;
        $fornecedor->complemento = $this->fornecedorComplemento ?? null;
        $fornecedor->bairro = $this->fornecedorBairro;
        $fornecedor->cidade = $this->fornecedorCidade;
        $fornecedor->uf = $this->fornecedorUf;
        $fornecedor->telefone = $this->fornecedorTelefone ?? null;
        $fornecedor->email = $this->fornecedorEmail;
        $fornecedor->forma_pagamento = $this->fornecedorDadosPagamento ?? null;
        $fornecedor->observacao = $this->fornecedorObservacao ?? null;
        $fornecedor->id_empresa = auth()->user()->id_empresa;
        $fornecedor->save();
        session()->flash('success', 'Fornecedor cadastrado com sucesso');
    }
}
