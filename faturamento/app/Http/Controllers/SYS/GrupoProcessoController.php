<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\GruposProcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrupoProcessoController extends Controller
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
        if(!auth()->user()->administrator){
            return redirect()->back()->with('error', 'Seu usuario não tem permissão de editar grupos');
        }

        $grupos = GruposProcesso::select(
            'grupo_processos.id as id',
            'grupo_processos.nome',
            'empresa.nome as empresa',
            'empresa.razao_social as empresa_rs'
        )
            ->join('empresa', 'empresa.id', '=', 'grupo_processos.id_empresa')
            ->where('empresa.id', auth()->user()->id_empresa)
            ->get();

        return view('grupoprocesso.grid', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->master){
            $empresas = Empresa::all();
        }

        if(!auth()->user()->master){
            $empresas = Empresa::where('id', auth()->user()->id_empresa)->get();
        }

        if(!auth()->user()->administrator){
            return redirect()->back()->with('error', 'Seu usuario não tem permissão de editar grupos');
        }

        $empresa = Empresa::where('id', auth()->user()->id_empresa)->first();

        return view('grupoprocesso.form', compact('empresas', 'empresa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|unique:grupo_processos',
            'id_empresa' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $checkBoxes = [
            'criar_usuario',
            'move_processo',
            'deleta_processo',
            'criar_fluxo'
        ];

        $checkBoxesValues = $this->validateCheckBoxes($request, $checkBoxes);

        $data = $request->only('nome', 'id_empresa');
        $data = array_merge($data, $checkBoxesValues);

        $create = GruposProcesso::create($data);
        if(!$create){
            return redirect()->back()->with('error', 'Não foi possivel salvar no banco, verificar com o administrador');
        }
        return redirect('grupoprocesso')->with('success', "Salvo com sucesso grupo {$data['nome']} id {$create->id}");
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
        $grupoprocesso = GruposProcesso::find($id);
        $empresa = Empresa::where('id', $grupoprocesso->id_empresa)->first();
        return view('grupoprocesso.form', compact('grupoprocesso', 'empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_empresa' => 'required',
        ]);

        $checkBoxes = [
            'criar_usuario',
            'move_processo',
            'deleta_processo',
            'criar_fluxo'
        ];

        $checkBoxesValues = $this->validateCheckBoxes($request, $checkBoxes);

        $data = $request->only('nome', 'id_empresa');
        $data = array_merge($data, $checkBoxesValues);

        $update = GruposProcesso::where('id', $id)->update($data);

        if(!$update){
            return redirect()->back()->with('error', 'Não foi possivel salvar no banco contate o administrador');
        }

        return redirect('grupoprocesso')->with('success', "Alterado com sucesso grupo : {$data['nome']} id : {$id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function validateCheckBoxes(Request $request, $checkBoxes)
    {
        $checkBoxesValues = [];

        foreach($checkBoxes as $checkBox){
            if($request->has($checkBox)){
                $checkBoxesValues[$checkBox] = true;
            } else {
                $checkBoxesValues[$checkBox] = false;
            }
        }

        return $checkBoxesValues;
    }
}
