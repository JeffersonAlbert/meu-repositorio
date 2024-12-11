<?php

namespace App\Http\Controllers\SYS;

use App\Helpers\UploadFiles;
use App\Http\Controllers\Controller;
use App\Models\Setup;
use Illuminate\Http\Request;

class SetupController extends Controller
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
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $validaSeEmpresaJaTemInfoAqui = Setup::where('id_empresa', auth()->user()->id_empresa)->first();

        if($validaSeEmpresaJaTemInfoAqui !== null && $validaSeEmpresaJaTemInfoAqui->count() > 0){
            $setup = $validaSeEmpresaJaTemInfoAqui;
            return view('setup.form', compact('setup'));
        }
        return view('setup.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validaSeEmpresaJaTemInfoAqui = Setup::where('id_empresa', auth()->user()->id_empresa)->get();
        if($validaSeEmpresaJaTemInfoAqui->count() > 0){
            return response()->json(['error' => ['message' => ['Algo de errado aqui, sua empresa ja tem esses dados no setup']]], 422);
        }
        $data = [];
        $data["id_empresa"] = auth()->user()->id_empresa;
        $data["exige_ordem_processo"] = $request->get('exige_ordem_processo') !== null ? true : false;
        $data["dias_antes_vencimento"] = $request->get('diasVencimento') !== null ? $request->get('diasVencimento') : 0;
        $data["dias_sem_movimentacao"] = $request->get('diasInatividade') !== null ? $request->get('diasInatividade') : 0;

        if($request->hasFile('logo')){
            $upload = new UploadFiles();
            $file = $request->file('logo');
            $filename = time() . $file->getClientOriginalName();
            $base64Content = file_get_contents($file->getRealPath());
            $upload->uploadToR2("logo/{$data["id_empresa"]}/{$filename}", $base64Content);
            $data['logo'] = $filename;
        }

        $setup = Setup::create($data);
        if($setup){
            return response()->json(['succes' => "enviado com sucesso"]);
        }


        return response()->json(['error' => ['message' => ['Não foi possivel salvar no banco de dados, favor entrar em contato com o administrador']]], 422);
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
        $data = [];
        $data["id_empresa"] = auth()->user()->id_empresa;
        $data["exige_ordem_processo"] = $request->get('exige_ordem_processo') !== null ? true : false;
        $data["dias_antes_vencimento"] = $request->get('diasVencimento') !== null ? $request->get('diasVencimento') : 0;
        $data["dias_sem_movimentacao"] = $request->get('diasInatividade') !== null ? $request->get('diasInatividade') : 0;

        if($request->hasFile('logo')){
            $upload = new UploadFiles();
            $file = $request->file('logo');
            $filename = time() . $file->getClientOriginalName();
            $base64Content = base64_encode(file_get_contents($file->getRealPath()));
            $upload->uploadToR2("logo/{$data["id_empresa"]}/{$filename}", $base64Content);
            $data['logo'] = $filename;
        }

        $setup = Setup::where('id_empresa', auth()->user()->id_empresa)->update($data);
        if($setup){
            return response()->json(['succes' => "enviado com sucesso"]);
        }
        return response()->json(['error' => ['message' => ['Não foi possivel salvar no banco de dados, favor entrar em contato com o administrador']]], 422);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
