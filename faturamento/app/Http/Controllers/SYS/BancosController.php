<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bancos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BancosController extends Controller
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
        $id_empresa = auth()->user()->id_empresa;
        $banco_agencia = $request->get('banco_agencia');
        $conta_agencia = $request->get('conta_agencia');
        $rules = [
            'banco_nome' => 'required|string',
            'banco_agencia' => [
                'required',
                'string',
                Rule::unique('bancos', 'agencia')->where(function ($query) use ($id_empresa, $conta_agencia) {
                    return $query->where('id_empresa', $id_empresa)->where('conta', $conta_agencia);
                })
            ],
            'banco_conta' => [
                'required',
                'string',
                Rule::unique('bancos', 'conta')->where(function ($query) use ($id_empresa, $banco_agencia) {
                    return $query->where('id_empresa', $id_empresa)->where('agencia', $banco_agencia);
                })
            ]
        ];

        $messages = [
            "banco_nome.required" => 'Requer nome',
            "banco_agencia.required" => 'Requer numero da agencia sem . ou -',
            "banco_conta.required" => 'Requer numero da conta sem . ou -',
            "banco_agencia.unique" => 'Agencia ja existe para essa empresa',
            "banco_conta.unique" => 'Conta ja exite para essa empresa'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $data = $request->except('_token');
        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['nome'] = $request->get('banco_nome');
        $data['agencia'] = $request->get('banco_agencia');
        $data['conta'] = $request->get('banco_conta');
        $bancos = Bancos::create($data);

        return response()->json([
            'success' => 'Banco cadastrado com sucesso', 
            'id' => $bancos->id,
            'nome' => $data['nome'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function select(Request $request)
    {
        $bancos = Bancos::where('id_empresa', $request->get('id_empresa'))->get();
        return response()->json($bancos);
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
        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['nome'] = $request->get('banco_nome');
        $data['agencia'] = $request->get('banco_agencia');
        $data['conta'] = $request->get('banco_conta');
        $update = Bancos::where('id', $id)->update($data);
        if($update){
            return response()->json(['success' => 'Banco alterado com sucesso', 'id' => $id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bancos = Bancos::where('id', $id)->update(['deletado' => true]);
        if($bancos){
            return response()->json(['success' => 'Banco removido com sucesso', 'id' => $id]);
        }
    }
}
