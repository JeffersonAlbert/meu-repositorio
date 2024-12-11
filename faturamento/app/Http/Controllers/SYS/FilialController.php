<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Filial;
use Illuminate\Http\Request;

class FilialController extends Controller
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

    // Cria filial a partir de um reuisição de matriz

    public function createFilial(string $id)
    {
        return view('filial.form', compact('id'));
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
        $request->validate([
            'cnpj' => 'min:11|required|unique:filial',
            'nome' => 'required',
            'razao_social' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'cep' => 'required',
            'cidade' => 'required',
            'id_empresa' => 'required',
        ]);

        $data = $request->except('_token');
        $filial = Filial::create($data);
        if($filial){
            return redirect('empresa')->with('success', "Cadastrado com sucesso filia: {$data['nome']}, com id {$filial->id}");
        }

        return redirect()->back()->with('error', "Não foi possivel cadastrar no banco de dados, favor acionar administrador");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $filial = Filial::find($id);
        return view('filial.form', compact('filial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'cnpj' => 'min:11|required',
            'nome' => 'required',
            'razao_social' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'cep' => 'required',
            'cidade' => 'required',
            'id_empresa' => 'required',
        ]);

        $data = $request->except('_token', '_method');
        $filialUpdate = Filial::where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Feito a edição com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
