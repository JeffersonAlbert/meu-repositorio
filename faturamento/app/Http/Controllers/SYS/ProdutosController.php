<?php

namespace App\Http\Controllers\SYS;

use App\Helpers\FormatUtils;
use App\Helpers\UploadFiles;
use App\Http\Controllers\Controller;
use App\Models\EstoqueAtual;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdutosController extends Controller
{
    public $produtos;
    public function __construct()
    {
        $this->middleware('auth');
        $this->produtos = new Produtos();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = $this->produtos->produtos();
        return view('produtos.grid', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'nome.required' => 'O produto/serviço precisa de um nome',
            'nome.min' => 'O nome precisa de no minimo 5 caracteres',
            'codigo_produto.required' => 'O produto precisa de um codigo interno',
            'codigo_produto.min' => 'O codigo do produto precisa de no minimo 5 caracteres',
            'valor.required' => 'O produto/serviço precisa de um valor',
        ];

        $validate = Validator::make(
            $request->all(), [
            'codigo_produto' => 'required|min:5',
            'nome' => 'required|min:5',
            'valor' => 'required',
        ], $messages);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $verificaSeProdutoExiste = Produtos::where('id_empresa', auth()->user()->id_empresa)->where('produto', $request->get('nome'))->count();
        if($verificaSeProdutoExiste > 0){
            return response()->json(['errors' => ['messages' => ['Já existe esse produto cadastrado']]], 422);
        }

        $data = $request->except('_token');
        $data['produto'] = $request->get('nome');
        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['valor'] = FormatUtils::formatMoneyDb($request->get('valor'));
        $data['tipo'] = $request->has('servico') ? 'servico' : 'produto';

        if($request->hasFile('files')){
            $upload = new UploadFiles();
            $files = $request->file('files');

            foreach($files as $file){
                $filename = time() . $file->getClientOriginalName();
                $base64Content = file_get_contents($file->getRealPath());
                $upload->uploadToR2("produtos/{$filename}", $base64Content);
                $newFileNames[] = $filename;
            }

            $filesTypesAndDesc = $upload->fileDescAndType($newFileNames, null, $request->get('descricao_arquivo'));
            $data['files'] = json_encode($filesTypesAndDesc);
        }

        $produto = Produtos::create($data);

        if($request->has('quantidade') and !is_null($request->get('quantidade'))){
            $this->insereEstoque($produto->id, $request->get('quantidade'));
        }

        return response()->json([
            'success' => [
                'message' => [
                    'Salvo com sucesso!'
                ],
                'produto' => [
                    $request->get('nome')
                ],
                'id' => [
                    $produto->id
                ]
            ]
        ]);
    }

    public function insereEstoque($produto_id, $quantidade){
        $estoque = EstoqueAtual::where('produto_id', $produto_id)->first();
        if($estoque){
            $estoque->quantidade = $estoque->quantidade + $quantidade;
            $estoque->save();
        }else{
            $estoque = new EstoqueAtual();
            $estoque->produto_id = $produto_id;
            $estoque->quantidade = $quantidade;
            $estoque->save();
        }
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
        $produto = $this->produtos->produtosPorId($id);
        return view('produtos.form', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'nome.required' => 'O produto/serviço precisa de um nome',
            'nome.min' => 'O nome precisa de no minimo 5 caracteres',
            'codigo_produto.required' => 'O produto precisa de um codigo interno',
            'codigo_produto.min' => 'O codigo do produto precisa de no minimo 5 caracteres',
            'valor.required' => 'O produto/serviço precisa de um valor',
        ];

        $validate = Validator::make(
            $request->all(), [
            'codigo_produto' => 'required|min:5',
            'nome' => 'required|min:5',
            'valor' => 'required',
        ], $messages);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $data = $request->except('_token', '_method');
        $data['produto'] = $request->get('nome');
        $data['valor'] = FormatUtils::formatMoneyDb($request->get('valor'));
        $data['tipo'] = $request->has('servico') ? 'servico' : 'produto';
        unset($data['nome']);

        $produto = Produtos::where('id', $id)
            ->where('id_empresa', auth()->user()->id_empresa)
            ->update([
                'produto' => $data['produto'],
                'codigo_produto' => $data['codigo_produto'],
                'valor' => $data['valor'],
                'tipo' => $data['tipo'],
                'altura' => $data['altura'],
                'largura' => $data['largura'],
                'peso_bruto' => $data['peso_bruto'],
                'peso_liquido' => $data['peso_liquido'],
                'valor_custo' => $data['valor_custo'],
                'margem_value' => isset($data['margem_value']) ? $data['margem_value'] : 0,
                'margem_percent' => isset($data['margem_percent']) ? $data['margem_percent'] : 0,
                'descricao' => $data['descricao'],
                'unidade_medida' => $data['unidade_medida'],
                'estoque_minimo' => $data['estoque_minimo'],
                'ean' => $data['ean'],
                'estoque_maximo' => $data['estoque_maximo'],
                'comprimento' => $data['comprimento'],
                'categoria' => $data['categoria'],
                'volume' => $data['volume'],
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produtos = Produtos::where('id', $id)->where('id_empresa', auth()->user()->id_empresa)->delete();
        return redirect()->route('produto.index')->with('message', "Excluido com sucesso");
    }
}
