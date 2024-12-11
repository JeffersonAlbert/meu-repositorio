<?php
/**
 * Controller da funcao do financeiro
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

use App\Http\Controllers\Controller;
use App\Models\Rateio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
/**
 * Controller da funcao do financeiro
 * php version 8.1
 *
 * @category Controller
 * @package  App\Http\Controllers\SYS;
 *
 * @author  Demostenes <demostenex@gmail.com>
 * @license https://app.number.app.br MIT
 * @link    https://app.number.app.br PHP 8.1
 */
class RateioController extends Controller
{
    /**
     * Exige que o usuario esteja logado
     */
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
     *
     * @param $request recebe formulario de inserir rateio
     *
     * @return json
     */
    public function store(Request $request) : JsonResponse
    {
        $rateio = new Rateio();
        $exist = $rateio->consultaRateio($request->all());
        if ($exist) {
            return response()->json(
                [
                    'errors' => [
                        'message' => [
                            'Já existe centro de custo com esse nome!'
                        ]
                    ]
                ], 422
            );
        }

        $data = $request->except('percentual_rateio', 'id_centro_custo', 'token');
        $centrosCusto = $request->get('id_centro_custo');
        $percentualRateio = $request->get('percentual_rateio');
        $centroCustoIdPercent = null;
        foreach ($centrosCusto as $key => $centroCusto) {
            $centroCustoIdPercent[] = [
                'id' => $centroCusto,
                'percentual' => $percentualRateio[$key]
            ];
        }
        $data['centro_custo_id_percent'] = json_encode($centroCustoIdPercent);
        $result = $rateio->adicionarRateio($data);
        return response()->json(
            [
                'success' => [
                    'message' => [
                        'Inserido com sucesso'
                    ],
                    'id' => [
                        $result['message']['id']
                    ],
                    'nome' => [
                        $data['nome']
                    ],
                    'descricao' => [
                        null
                    ]
                ]
            ]
        );
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
