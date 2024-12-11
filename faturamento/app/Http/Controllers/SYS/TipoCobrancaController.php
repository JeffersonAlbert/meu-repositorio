<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\TipoCobranca;
use Illuminate\Http\Request;

class TipoCobrancaController extends Controller
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
        if($request->get('nome_cobranca')  == null){
            return response()->json(json_encode(['success' => false, 'message' => 'Nome não pode ser vazio']));
        }

        $tipoCobranca = TipoCobranca::where('id_empresa', auth()->user()->id_empresa)
        ->where('md5_nome', md5(strtoupper(substr($request->get('nome_cobranca'), 0, 6))))
        ->first();

        if($tipoCobranca !== null){
            return response()->json(json_encode(['success' => false, 'message' => 'Já existe esse tipo de cobranca']));
        }

        $data['nome'] = strtoupper($request->get('nome_cobranca'));
        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['md5_nome'] = md5(strtoupper(substr($request->get('nome_cobranca'), 0, 6)));

        $tipoCobranca = TipoCobranca::create($data);

        return response()->json(json_encode(['success' => true, 'message' => 'Salvo no banco de dados', 'id' => $tipoCobranca->id, 'nome' => $data['nome']]));
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
