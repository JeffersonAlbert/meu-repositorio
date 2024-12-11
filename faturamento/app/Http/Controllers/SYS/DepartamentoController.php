<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Filial;

class DepartamentoController extends Controller
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

    }

    public function createDepartamento(string $id)
    {
        $id_empresa = $id;
        $filiais = Filial::where('id_empresa', $id)->get();
        return view('departamento.form', compact('id_empresa', 'filiais'));
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
        $data = $request->only('nome', 'descricao', 'id_empresa');
        $filiaisRequest = $request->except('_token', 'nome', 'descricao', 'id_empresa');
        $filiais = [];
        foreach($filiaisRequest as $filial){
            array_push($filiais, $filial);
        }
        $data['id_filiais'] = json_encode($filiais);

        $departamento = Departamento::create($data);
        if(!$departamento){
            return redirect()->back()->with('error', 'Não foi possivel salvar no banco de dados, favor entrar em contato com o administrador');
        }
        return redirect('empresa')->with('success', "Salvo com sucesso o departamento {$data['nome']}, sob o id: {$departamento->id}");
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
        $departamento = Departamento::find($id);
        $filiais = Filial::where('id_empresa', $departamento->id_empresa)->get();
        return view('departamento.form', compact('departamento', 'filiais'));
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
