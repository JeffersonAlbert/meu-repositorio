<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\FormasPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormaPagamentoController extends Controller
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
            return redirect()->back()->with('error', 'Seu usuário não tem permissão de estar aqui');
        }
        $formasPagamento = FormasPagamento::all();
        return view('setup.pagamento.grid', compact('formasPagamento'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!auth()->user()->master){
            return redirect()->back()->with('error', 'Seu usuário não tem permissão de estar aqui');
        }
        return view('setup.pagamento.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'nome.required' => 'Precisa conter o nome da forma do pagamento'
        ];

        $validator = Validator::make($request->all(),[
            'nome' => 'required'
        ], $message);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $request->except('_token');
        $create = FormasPagamento::create($data);
        if(!$create){
            return response()->json(['error' => ["msg" => ["Não foi possivel criar a forma de pagamento"]]], 422);
        }
        return response()->json(['redirect' => route('forma-pagamento.index')]);
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
        $formaPagamento = FormasPagamento::find($id);
        return view('setup.pagamento.form', compact('formaPagamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        FormasPagamento::where('id', $id)->update(['nome' => $request->get('nome')]);
        return response()->json(['redirect' => route('forma-pagamento.index')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
