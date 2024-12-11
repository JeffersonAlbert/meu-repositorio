<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoriasReceber;

class CategoriasController extends Controller
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
        $verificaSeCategoriaExiste = CategoriasReceber::where('id_empresa', auth()->user()->id_empresa)
        ->where('categoria', $request->get('categoria'))->count();
        if($verificaSeCategoriaExiste !== 0){
            return response()->json([
                'errors' => [
                    'message' => [
                        'Categoria ja existe!'
                    ]
                ]
            ], 422);
        }

        $data = $request->except('_token');
        $data['id_empresa'] = auth()->user()->id_empresa;
        $cr = CategoriasReceber::create($data);
        if(!$cr){
            return response()->json([
                'errors' => [
                    'message' => [
                        'Ocorreu algum erro favor informar o administrador!'
                    ]
                ]
            ], 422);
        }

        return response()->json([
            'success' => [
                'message' => [
                    'Inserido com sucesso'
                ],
                'id' => [
                    $cr->id
                ],
                'descricao' => [
                    $request->get('descricao')
                ],
                'categoria' => [
                    $request->get('categoria')
                ]
            ]
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cr = CategoriasReceber::where('id', $id)->update([
            'categoria' => $request->get('categoria'),
            'descricao' => $request->get('descricao'),
        ]);

        if(!$cr){
            return response()->json([
                'errors' => [
                    'message' => [
                        'Não foi possivel altera a categoria'
                    ]
                ]
            ]);
        }
        return response()->json([
            'success' => [
                'message' => [
                    'Alterado com sucesso!'
                ],
                'id' => [
                    $id
                ],
                'descricao' => [
                    $request->get('descricao')
                ],
                'categoria' => [
                    $request->get('categoria')
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
