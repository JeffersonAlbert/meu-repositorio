<?php
/**
 * Classe para interacao com o banco de dados responsavel por
 * consultar a base de dados do processo
 * php version 8.1
 *
 * @category Controller
 * @package  App\Http\Controllers\SYS;
 *
 * @author  Demostenes <demostenex@gmail.com>
 * @license https://app.number.app.br MIT
 * @link    https://app.number.app.br PHP 8.1
 */
namespace App\Http\Controllers\SYS;

use App\Helpers\FormatUtils;
use App\Http\Controllers\Controller;
use App\Models\Contratos;
use App\Models\Empresa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * Classe para interacao com o banco de dados responsavel por
 * consultar a base de dados do processo
 * php version 8.1
 *
 * @category Controller
 * @package  App\Http\Controllers\SYS;
 *
 * @author  Demostenes <demostenex@gmail.com>
 * @license https://app.number.app.br MIT
 * @link    https://app.number.app.br PHP 8.1
 */
class ContratoController extends Controller
{
    private $_path = 'uploads/contratos';
    private $_empresa = "";
    /**
     * Contrutor permite apenas autenticados
     */
    public function __construct()
    {
                $this->middleware('auth');
    }
    /**
     * Funcao para trazer dados da empresa que o contrato pertence
     *
     * @return object
     */
    public function empresa()
    {
        return $this->_empresa = Empresa::where('id', auth()->user()->id_empresa)->first();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contratos =  Contratos::select('contratos.id as id', 'contratos.nome as nome', 'clientes.nome as cliente_nome', 'fornecedor.nome as fornecedor_nome',
            'contratos.valor as valor', 'contratos.vigencia_inicial as vigencia_ini', 'contratos.vigencia_final as vigencia_fim',
            'contratos.vigencia as periodo')
            ->leftJoin('clientes', 'clientes.id', 'contratos.id_cliente')
            ->leftJoin('fornecedor', 'fornecedor.id', 'contratos.id_fornecedor')
            ->where('contratos.id_empresa', auth()->user()->id_empresa)
            ->paginate(10);

        return view('contratos.grid', compact('contratos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $indices = ["IGPM", "IPCA"];
        return view('contratos.form', compact('indices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'nome.required' => 'Precisa nomear o contrato',
            'cliente_contrato.required' => 'Precisa selecionar um cliente',
            'vigencia_inicial.required' => 'Precisa adicionar uma data inicial de vigencia',
            'vigencia_final.required' => 'Precisa adicionar uma final de vigencia',
        ];

        $validator = Validator::make(
            $request->all(), [
                'nome' => 'required',
                'cliente_contrato' => 'required',
                'vigencia_inicial' => 'required',
                'vigencia_final' => 'required'
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validaSeContratoExiste = Contratos::where('nome', $request->get('nome'))
            ->where('id_empresa', auth()->user()->id_empresa)
            ->count();

        if ($validaSeContratoExiste > 0) {
            return response()->json(['errors' => ['message' => ['Já existe esse contrato']]], 422);
        }
        $data = $request->except('_token');
        $idCliente = explode('-', $data['cliente_contrato']);
        $data['id_cliente'] = trim($idCliente[0]);
        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['valor'] = FormatUtils::formatMoneyDb($request->get('valor'));
        $data['indice'] = $request->has('indice') ? $request->get('indice') : null;

        $vigenciaInicio = Carbon::createFromFormat('Y-m-d', $data['vigencia_inicial']);
        $vigenciaFinal = Carbon::createFromFormat('Y-m-d', $data['vigencia_final']);

        $data['vigencia'] = $vigenciaFinal->diffInMonths($vigenciaInicio);

        if ($request->hasFile('files')) {
            $uploadFileContrato = $this->upload($data['files']);
            if ($uploadFileContrato['success'] !== true) {
                return response()->json(
                    [
                        'errors' => [
                            'message' => [
                                'Não foi possivel salvar os arquivos'
                            ]
                        ]
                    ], 422
                );
            }
            $data['files'] = json_encode($uploadFileContrato);
        }


        $criarContratos = Contratos::create($data);
        if ($criarContratos) {
            return response()->json(
                [
                    'success' =>
                    [
                        'message' => [
                            'Adicionado contrato com sucesso'
                        ],
                        'redirect' => [
                            route('contrato.index')
                        ]
                    ],
                    [
                        'id' =>[
                            $criarContratos->id
                            ]
                    ],
                    
                    
                ]
            );
        }

        return response()->json(['errors' => ['message' => ['Algum erro acnteceu favo informar o administrador']]], 422);
    }

    /**
     * Funcao para salvar o arquiv caso envie na pasta uploads/contratos
     *
     * @param $files recebe request do laravel file
     *
     * @return $array retorna [success => true or false, files => [nomes, dos, arquivos]]
     */
    public function upload($files)
    {
        $empresa = $this->empresa()->nome;
        $uploadSuccess = true;
        foreach ($files as $file) {
            $newFileName = time().$file->getClientOriginalName();
            $result = $file->move("{$this->_path}/{$empresa}/", $newFileName);
            $arrayFileName[] = $newFileName;
            if (!$result) {
                $uploadSuccess = false; // Se um upload falhar, definimos a flag como false.
            }
        }

        if ($uploadSuccess) {
            return ['success' => $uploadSuccess, 'files' => $arrayFileName];
        }

        return ['success' => $uploadSuccess];
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
        $indices = ["IGPM", "IPCA"];
        $contrato = Contratos::select('contratos.id as id', 'contratos.nome as nome', 'clientes.id as id_cliente', 'clientes.nome as cliente_nome', 
            'clientes.cpf_cnpj as cliente_cnpj', 'fornecedor.nome as fornecedor_nome', 'contratos.valor as valor', 'contratos.vigencia_inicial as vigencia_ini', 
            'contratos.files as files', 'contratos.vigencia_final as vigencia_fim', 'contratos.vigencia as periodo', 'contratos.indice as indice')
            ->leftJoin('clientes', 'clientes.id', 'contratos.id_cliente')
            ->leftJoin('fornecedor', 'fornecedor.id', 'contratos.id_fornecedor')
            ->find($id);
    
        return view('contratos.form', compact('contrato', 'indices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $messages = [
        'nome.required' => 'Precisa setar o nome do contrato',
        'nome.min' => 'O nome do contrato precisa ser maior que 5 caracteres',
        'cliente_contrato.required' => 'Precisa setar o cliente do contrato',
        'indice.required' => 'Precisa informar o indice do contrato',
        'vigencia_inicial.required' => 'Precisa setar a data do inicio vigencia',
        'vigencia_final.required' => 'Precisa setar a data do fim do contrato',
        'valor.required' => 'Precisa adicionar valor do contrato' 
       ];

       $validator = Validator::make($request->all(),[
        'nome' => 'required|min:5',
        'cliente_contrato' => 'required',
        'indice' => 'required',
        'vigencia_inicial' => 'required',
        'vigencia_final' => 'required',
        'valor' => 'required'
       ], $messages);

       if($validator->fails()){
        return response()->json(['errors' => $validator->errors()], 422);
       }
       
       $vigenciaInicio = Carbon::createFromFormat('Y-m-d', $request->get('vigencia_inicial'));
       $vigenciaFinal = Carbon::createFromFormat('Y-m-d', $request->get('vigencia_final'));
       $vigencia = $vigenciaFinal->diffInMonths($vigenciaInicio);

       $cliente = $request->get('cliente_contrato');
       $clienteParts = explode('-', $cliente);
       $idCliente = trim($clienteParts[0]);

       $updateContrato = Contratos::where('id', $id)->where('id_empresa', auth()->user()->id_empresa)->update([
            'id_cliente' => $idCliente,
            'vigencia_inicial' => $request->get('vigencia_inicial'),
            'vigencia_final' => $request->get('vigencia_final'),
            'indice' => $request->get('indice'),
            'valor' => FormatUtils::formatMoneyDb($request->get('valor')),
            'vigencia' => $vigencia,
            'nome' => $request->get('nome')
       ]);

       if(!$updateContrato){
        return response()->json(['errors' => ['messages' => ['Não foi possivel salvar no banco de dados, contacte o administrador!']]], 422);
       }

       return response()->json(['success' => ['messages' => ['Alterado com sucesso'], 'redirect' => [route('contrato.index')]]]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function baixarAquivo(Request $request)
    {
        $empresaPath = $this->empresa()->nome;
        $file = "uploads/contratos/{$empresaPath}/{$request->file}";
        if(file_exists($file)){
            // Determine o tipo MIME com base na extensão do arquivo
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            // Defina o tipo MIME com base na extensão do arquivo
            $mimeType = mime_content_type($file);


            // Defina os cabeçalhos apropriados para o download
            return response()->download($file, $request->file, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Content-Disposition' => 'attachment; filename=' . $request->file,
                'Expires' => '0',
                'Pragma' => 'public'
            ]);
        }
        return response()->json(['errors' => ['messages' => ['Arquivo não encontrado!']]], 422);
    }
}
