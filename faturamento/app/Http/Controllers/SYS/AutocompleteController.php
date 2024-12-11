<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\ApprovedProcesso;
use App\Models\CentroCusto;
use App\Models\Clientes;
use App\Models\Contratos;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\FormasPagamento;
use App\Models\Fornecedor;
use App\Models\GruposProcesso;
use App\Models\Produtos;
use App\Models\SubCategoriaDRE;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AutocompleteController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('auth')->except('consultaCnpjRegistro');
    }

    public function produtoTypeAhead(Request $request)
    {
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->where('produto', 'like', "%{$request->get('term')}%")->get();
        foreach($produtos as $produto){
            $json[] = "{$produto->id} - {$produto->produto} - {$produto->codigo_produto}";
        }
        return response()->json($json);
    }

    public function contratoTypeAhead(Request $request)
    {
        $contratos = Contratos::select('contratos.id as id', 'clientes.nome as cliente', 'contratos.nome as nome')
            ->leftJoin('clientes', 'clientes.id', 'contratos.id_cliente')
            ->where('contratos.id_empresa', auth()->user()->id_empresa)
            ->where('contratos.nome', 'like', "%{$request->get('term')}%")
            ->get();
        foreach($contratos as $contrato){
            $json[] = "{$contrato->id} - {$contrato->nome} - {$contrato->cliente}";
        }
        return response()->json($json);

    }

    public function completeEmpresa(Request $request)
    {
        $message = [
            "cnpj.required" => "O cnpj/cpf é um campo de preecimento obrigatorio",
            "cnpj.min" => "O cnpj/cpf deve conter no minimo 11 carateres"
        ];
        $request->validate([
            'cnpj' => 'required|min:11',
        ], $message);

        $cnpjSemCaracterEspecial = str_replace(['/','-','.'], ['','',''], $request->get('cnpj'));
        if(substr($cnpjSemCaracterEspecial, -3, 1) != 1){
            $message = 'CNPJ/CPF é de filial favor cadastrar a matriz primeiro';
            return json_encode([
                'result' => 'error',
                'message' => $message,
                'estabelecimento' => ['tipo' => 'filial']
            ]);
        }

        $empresa = Empresa::where('cpf_cnpj', $cnpjSemCaracterEspecial)->get();

        if($empresa->count() > 0){
            $message = 'CNPJ/CPF já cadastrado!';
            return json_encode([
                'result' => 'error',
                'message' => $message,
                'estabelecimento' => ['tipo' => 'matriz']
            ]);
        }

        $consultaCnpj = $this->consultaCnpj($cnpjSemCaracterEspecial);

        if(stristr($consultaCnpj, "Ocorreu um erro")){
            return json_encode([
                'result' => 'error',
                'message' => 'Erro do CNPJ/CPF verifique se esta correto',
                'estabelecimento' => 'error'
            ]);
        }

        return $consultaCnpj;
    }

    public function completeFilial(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|min:11',
        ]);

        $cnpjSemCaracterEspecial = str_replace(['/','-','.'], ['','',''], $request->get('cnpj'));

        if(substr($cnpjSemCaracterEspecial, -3, 1) == 1){
            $message = 'CNPJ/CPF é de matriz favor cadastrar no menu empresa';
            return json_encode([
                'result' => 'error',
                'message' => $message,
                'estabelecimento' => ['tipo' => 'filial']
            ]);
        }

        $filial = Filial::where('cnpj', $cnpjSemCaracterEspecial)->get();

        if($filial->count() > 0){
            $message = 'CNPJ/CPF já cadastrado!';
            return json_encode([
                'result' => 'error',
                'message' => $message,
                'estabelecimento' => ['tipo' => 'matriz']
            ]);
        }

        $consultaCnpj = $this->consultaCnpj($cnpjSemCaracterEspecial);

        if(stristr($consultaCnpj, "Ocorreu um erro")){
            return json_encode([
                'result' => 'error',
                'message' => 'Erro do CNPJ/CPF verifique se esta correto',
                'estabelecimento' => 'error'
            ]);
        }

        return $consultaCnpj;
    }

    public function consultaCnpj($cnpj)
    {
        $url = "https://publica.cnpj.ws/cnpj/".str_replace(['/','-','.'], ['','',''], $cnpj);
        $response = Http::get($url);
        if ($response->successful()) {
            return $response->body();
        }
        if(!$response->successful()){
            $error = $response->status();
            return "Ocorreu um erro: {$error}";
        }
    }

    public function consultaCnpjRegistro(Request $request)
    {
        $cnpj = str_replace(['.','-','/'], ['', '', ''], $request->get('cnpj'));
        if(strlen($cnpj) !== 14){
            return;
        }
        return $this->consultaCnpj($request->get('cnpj'));
    }

    //consultar tablea grupo_processos
    public function completeGrupo(Request $request)
    {
        $results = GruposProcesso::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->where('nome', 'like', "%{$request->get('term')}%")
            ->get();
        foreach($results as $result){
            $json[] = $result->id." - ".$result->nome;
        }

        return response()->json($json);
    }

    public function findGrupoById($data)
    {
        return ApprovedProcesso::select(
            'approved_processo.id_processo as a_processo',
            'users.name as u_nome',
            'grupo_processos.id as id',
            'grupo_processos.nome as nome',
            'approved_processo.created_at'
        )
        ->leftJoin('grupo_processos', 'approved_processo.id_grupo', 'grupo_processos.id')
        ->leftJoin('users', 'users.id', 'approved_processo.id_usuario')
        ->where('grupo_processos.id', $data['id'])
        ->where('approved_processo.id_processo', $data['id_processo'])
        ->where('approved_processo.id_processo_vencimento_valor', $data['id_processo_vencimento_valor'])
        ->first();
    }

    public function searchGrupoById(Request $request)
    {
        $results = $this->findGrupoById([
            'id' => $request->get('id'),
            'id_processo' => $request->get('id_processo'),
            'id_processo_vencimento_valor' => $request->get('id_processo_vencimento_valor')
        ]);

        if(!$results){
            $results = GruposProcesso::find($request->get('id'));
        }
        return response()->json($results);
    }

    public function completeFornecedor(Request $request)
    {
        if($request->get('term')){
            $results = Fornecedor::where("nome", "like", "%{$request->get('term')}%")
                ->where('id_empresa', auth()->user()->id_empresa)
            ->orWhere('cpf_cnpj', "like", "%{$request->get('term')}%")
            ->where('id_empresa', auth()->user()->id_empresa)
            ->get();
            foreach($results as $result){
                $json[] = $result->id." - ".$result->nome." - ".$result->cpf_cnpj;
            }
            ($results->count() > 0) ? $json = $json : $json = 'nada aqui';
            return response()->json($json);
        }

        if( ($request->get('doc') && strlen($request->get('doc')) == 11) or ($request->get('doc') && strlen($request->get('doc')) == 14) ){
            $results = Fornecedor::where("cpf_cnpj", "{$request->get('doc')}")
            ->where('id_empresa', $request->get('id_empresa'))
            ->first();
            return $results !== null ? true : 0;
        }

        return null;
    }

    public function consultaCliente(Request $request)
    {
        $result = Clientes::where("cpf_cnpj", $request->get('doc'))->where('id_empresa', auth()->user()->id_empresa)->first();
        return $result !== null ? true : 0;
    }

    public function completeUsuario(Request $request)
    {
        $usuarios = User::select('id', 'email')->where('name', 'like', "%{$request->term}%")
        ->where('id_empresa', auth()->user()->id_empresa)
        ->orWhere('email', 'like', "%{$request->term}%")
        ->where('id_empresa', auth()->user()->id_empresa)
        ->get();
        foreach($usuarios as $usuario){
            $json[] = "{$usuario->id} - {$usuario->email}";
        }
        return response()->json($json);
    }

    public function clientesTypeAhead(Request $request)
    {
        $clientes = Clientes::where('id_empresa', auth()->user()->id_empresa)
        ->where('searchable', 'like', "%{$request->get('term')}%")->get();
        foreach($clientes as $cliente){
            $json[] = "{$cliente->id} - {$cliente->cpf_cnpj} - {$cliente->razao_social}";
        }
        return response()->json($json);
    }

    public function usuarioPorGrupo(Request $request)
    {
        $users = User::select('id', 'name', 'last_name', 'email')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->whereNotNull(DB::raw("JSON_SEARCH(id_grupos, 'one', {$request->get('id')})"))
            ->get();
        return $users;
    }

    public function getSubDreName(Request $request){
        $subDre = SubCategoriaDRE::where('id_empresa', auth()->user()->id_empresa)
            ->orWhereNull('id_empresa')
            ->find($request->get('subDre'));
        return response()->json($subDre);
    }

    public function getCentroCusto(Request $request)
    {
        $centroCusto = CentroCusto::where('id_empresa', auth()->user()->id_empresa)
            ->find($request->get('centroCusto'));
        return response()->json($centroCusto);
    }

    public function getClient(Request $request)
    {
        $client = Clientes::where('id_empresa', auth()->user()->id_empresa)
            ->find($request->get('clientId'));
        return response()->json($client);
    }

    public function getProducts(Request $request)
    {
        foreach($request->get('produtosId') as $produtoId){
            $products[] = Produtos::where('id_empresa', auth()->user()->id_empresa)
                ->find($produtoId);
        }
        return response()->json($products);
    }
}
