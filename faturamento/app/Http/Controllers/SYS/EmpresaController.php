<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\User;

class EmpresaController extends Controller
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
        if(!auth()->user()->master){
            return redirect()->back()->with('error', 'Seu usuario não tem permissão para acessar aqui');
        }
        $empresas = Empresa::all();
        return view('empresa.grid', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         if(!auth()->user()->master){
            return redirect()->back()->with('error', 'Seu usuario não tem permissão para acessar aqui');
        }

        return view('empresa.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'min:11|required|unique:empresa',
            'razao_social' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'cep' => 'required',
            'cidade' => 'required'
        ]);
        $data = $request->except('_token');
        $data['cpf_cnpj'] = str_replace(['.', '/', '-'], '', $data['cpf_cnpj']);
        $empresa = Empresa::create($data);
        if(!$empresa){
            return redirect()->back()->with('error', 'Não conseguimos salvar no banco contate o administrador');
        }
        return redirect('empresa')->with("Cadastrado com sucesso {$data['razao_social']} id: {$empresa->id}");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instance = new Empresa();
        $empresa = $instance->find($id);
        $workflow = $instance->workflowPorEmpresa($id);
        $grupos = $instance->gruposPorEmpresa($id);
        $usuarios = User::select('users.*', 'empresa.nome')
            ->leftJoin('empresa', 'empresa.id', 'users.id_empresa')
            ->where('id_empresa', $id)->get();
        $filiais = Filial::where('id_empresa', $id)->get();
        $departamentos = Departamento::where('id_empresa', $id)->get();
        return view('empresa.show', compact('empresa', 'filiais', 'departamentos', 'grupos', 'usuarios', 'workflow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empresa = Empresa::find($id);
        return view('empresa.form', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $data = $request->except('_method', '_token');
        //$data['cpf_cnpj'] = str_replace(['.', '/', '-'], '', $data['cpf_cnpj']);
        $empresa = Empresa::where('id', $id)->update($data);
        if(!$empresa){
            return redirect()->back()->with('error', 'Não foi alterar no banco de dados, favor entre em contado com o administrador');
        }
        return redirect()->back()->with('success', "Alterado no com sucesso id: {$id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
