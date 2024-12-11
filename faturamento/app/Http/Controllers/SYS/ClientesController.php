<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clienteDoc = str_replace(['.', '/', '-'], [''], $request->get('cpf_cnpj'));
        if(strlen($clienteDoc) == 14){
            return $this->clienteCreatePJ($request);
        }
        $this->clienteCreatePF($request);
    }

    public function clienteCreatePF($request)
    {
        $cliente = Clientes::create([
            'cpf_cnpj' => $request->get('cpf_cnpj'),
            'nome' => $request->get('nome'),
            'id_empresa' => auth()->user()->id_empresa,
            'razao_social' => $request->get('nome'),
        ]);

        if($cliente){
            return response()->json(['success' => true, 'message' => 'Cliente inserido com sucesso']);
        }
        return response()->json(['success' => false, 'message' => 'Não foi possivel salvar o usuario favor falar com o administrado']);
    }

    public function clienteCreatePJ($request)
    {
        $autocomplete = new AutocompleteController();
        $dados = $autocomplete->consultaCnpj($request->get('cpf_cnpj'));
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
        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['telefone'] = $request->get('telefone');
        $data['cpf_cnpj'] = $request->get('cpf_cnpj');
        $cliente = Clientes::create($data);
        if($cliente){
            return response()->json(['success' => true, 'message' => 'Cliente inserido com sucesso']);
        }
        return response()->json(['success' => false, 'message' => 'Não foi possivel salvar o usuario favor falar com o administrado']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
