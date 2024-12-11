<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fornecedor;

class FornecedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->get('termo')){
            $fornecedores = Fornecedor::where('searchable', 'like', "%{$request->get('termo')}%")
            ->where('id_empresa', auth()->user()->id_empresa)
            ->get();
        }
        if($request->get('termo') == null){
            $fornecedores = Fornecedor::where('id_empresa', auth()->user()->id_empresa)->get();
        }
        return view('fornecedor.grid', compact('fornecedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fornecedor.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'cpf_cnpj.unique' => 'Fornecedor ja cadastrado por esse CNPJ',
            'cpf_cnpj.required' => 'Cnpj é obrigatório',
            'cpf_cnpj.min' => 'Precisa ter minimo de 11 ou maximo de 14',
            'nome.required' => 'Nome precisa ser inserido'
        ];

        $request->validate([
            'cpf_cnpj' => 'required|unique:fornecedor,cpf_cnpj,NULL,id,id_empresa,' . auth()->user()->id_empresa . '|min:11',
            'nome' => 'required',
        ], $message);

        $docFornecedor = str_replace(['.','/','-'], ['','',''], $request->get('cpf_cnpj'));
        $testeCnpjExiste = Fornecedor::where('cpf_cnpj', $docFornecedor)->where('id_empresa', auth()->user()->id_empresa)->get();

        if(count($testeCnpjExiste) !== 0){
            return redirect()->back()->with('error', 'Documento já existe na base de dados');
        }

        $data = $request->only('nome', 'cpf_cnpj', 'id_empresa', 'telefone');

        if(strlen($docFornecedor) >= 14){
            $autocomplete = new AutocompleteController();
            $dados = $autocomplete->consultaCnpj($request->cpf_cnpj);
            $empresa = json_decode($dados);
            $data['razao_social'] = $empresa->razao_social;
            $data['inscrica_estadual'] = isset($empresa->estabelecimento->inscricoes_estaduais[0]->inscricao_estadual) ? $empresa->estabelecimento->inscricoes_estaduais[0]->inscricao_estadual : null;
            $data['nome'] = isset($empresa->estabelecimento->nome_fantasia) ? $empresa->estabelecimento->nome_fantasia : $empresa->razao_social;
            $data['endereco'] = "{$empresa->estabelecimento->tipo_logradouro} {$empresa->estabelecimento->logradouro}";
            $data['numero'] = $empresa->estabelecimento->numero;
            $data['complemento'] = $empresa->estabelecimento->complemento;
            $data['bairro'] = $empresa->estabelecimento->bairro;
            $data['cep'] = $empresa->estabelecimento->cep;
            $data['cidade'] = $empresa->estabelecimento->cidade->nome;
            $data['forma_pagamento'] = $request->get('forma_pagamento');
            $data['observacao'] = $request->get('observacao');
            $data['id_empresa'] = auth()->user()->id_empresa;
            $data['cpf_cnpj'] = $empresa->estabelecimento->cnpj;
        }
        if(strlen($request->get('cpf_cnpj')) < 14){
            $data['cpf_cnpj'] = $docFornecedor;
            $data['cidade'] = null;
        }

        $fornecedor = Fornecedor::create($data);

        if(!$fornecedor){
            return redirect()->back()->with('error', 'Não foi possivel salvar, por favor contate o administrador');
        }
        if($request->get('modal_form')){
            return !$fornecedor ? redirect('processo/create')->with('error', "Não foi possivel adicionar, favor verificar com o administrador") : redirect('processo/create')->with('success', "Adicionado com sucesso a empresa {$data['nome']} CNPJ {$data['cpf_cnpj']}");
        }

        return redirect('fornecedor')->with('success', "Criado com sucesso {$data['nome']}, {$fornecedor->id}");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fornecedor = Fornecedor::find($id);
        return view('fornecedor.form', compact('fornecedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->except('_token', '_method');
        $fornecedor = Fornecedor::where('id', $id)->update($data);
        if(!$fornecedor){
            return redirect()->back()->with('error', 'Não foi possivel salvar, entre em contato com o administrador')->withInput();
        }
        return redirect('fornecedor')->with('success', "Alterado com sucesso {$data['nome']}, {$fornecedor}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
