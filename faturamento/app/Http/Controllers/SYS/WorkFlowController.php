<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\GrupoOrderFluxo;
use App\Models\GruposProcesso;
use App\Models\WorkFlow;
use Illuminate\Http\Request;

class WorkFlowController extends Controller
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
        $workflows = new WorkFlow();
        $workflows = $workflows->getWorkFlow();
        return view('workflow.grid', compact('workflows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupos = GruposProcesso::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('workflow.form', compact('grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => "required|unique:workflow",
            'grupo' => "required"
        ]);

        $data = $request->except('_token');
        $data['id_grupos'] = json_encode($data['grupo']);
        unset($data['grupo']);

        $workflow = WorkFlow::create($data);
        if(!$workflow){
            return redirect()->back()->with('error', 'Não foi possivel salvar no banco de dados, por favor entre em contado com o administrador');
        }
        $data['id_grupos'] = json_decode($data['id_grupos']);
        $this->updateGrupoOrderFluxo($data, $workflow->id);
        return redirect('workflow')->with('success', "Salvo com sucesso fluxo nome: {$data['nome']} id: {$workflow->id}");
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
        $workflow = new WorkFlow();
        $workflow = $workflow->getWorkFlowById($id);
        $grupos = GruposProcesso::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('workflow.form', compact('workflow', 'grupos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "nome" => "required",
            "grupo" => "required"
        ]);

        $data = $request->except("_token", "_method");
        $data['id_grupos'] = $data['grupo'];
        unset($data['grupo']);
        $workflow = WorkFlow::where('id', $id)->update($data);
        $this->updateGrupoOrderFluxo($data, $id);
        if(!$workflow){
            return redirect()->back()->with('error', "Não foi possivel salvar no banco de dados");
        }
        return redirect('workflow')->with('success', "Salvo com sucesso {$data['nome']} id: {$id}");

    }

    public function updateGrupoOrderFluxo(array $data, string $id)
    {
        $grupoOrderFluxo = [];
        foreach($data['id_grupos'] as $idGrupo){
            array_push($grupoOrderFluxo, [
                'id_grupo' => $idGrupo,
                'id_fluxo' => $id,
                'ativo' => true,
                'id_user' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        GrupoOrderFluxo::where('id_fluxo', $id)->update(['ativo' => false]);

        GrupoOrderFluxo::insert($grupoOrderFluxo);

        return;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
