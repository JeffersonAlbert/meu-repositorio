<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroCusto;
class CentroCustoController extends Controller
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
        $ccs = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('centrocusto.grid', compact('ccs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('centrocusto.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validaSeExisteCentroCusto = CentroCusto::where('id_empresa', auth()->user()->id_empresa)
        ->where('nome', $request->get('nome'))->count();
        if($validaSeExisteCentroCusto !== 0){
            return response()->json([
                'errors' => [
                    'message' => [
                        'Já existe esse centro de custo'
                    ]
                ]
            ], 422);
        }
        $data = $request->only('nome', 'descricao');
        $data['id_empresa'] = auth()->user()->id_empresa;
        $cc = CentroCusto::create($data);
        if(!$cc){
            return redirect('response')->with('error', 'Não foi possivel salvar no banco de dados');
        }
        return response()->json([
            'success' => [
                'message' => [
                    'Salvo com sucesso!'
                ],
                'nome' => [
                    $request->get('nome')
                ],
                'id' => [
                    $cc->id
                ],
                'descricao' => [
                    $request->get('descricao')
                ]
            ],
        ]);
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
        $cc = CentroCusto::find($id);
        return view('centrocusto.form', compact('cc'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only('nome', 'descricao');
        $cc = CentroCusto::where('id', $id)->update($data);
        if(!$cc){
            return redirect()->back()->with('error', "Não foi possivel editar o {$data['nome']}");
        }

        return response()->json([
            'success' => [
                'message' => [
                    'Atualizado com sucesso'
                ],
                'nome' => [
                    $request->get('nome')
                ],
                'id' => [
                    $id
                ],
                'descricao' => [
                    $request->get('descricao')
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
