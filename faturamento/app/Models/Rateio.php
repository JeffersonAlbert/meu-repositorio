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


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

class Rateio extends Model
{
    use HasFactory;
    protected $table = 'rateio_setup';
    protected $fillable = [
        'nome',
        'centro_custo_id_percent',
        'id_empresa'
    ];
    /**
     * Consulta pelo nome e id da empresa se rateio ja existe;
     *
     * @param $data recebe o $arrayName = array(
     *              'nome' => 'nome_rateio',
     *              'id_empresa' => 'id_empresa'
     *              );
     *
     * @return bool
     */
    public function consultaRateio(array $data) : bool
    {
        $consultaSeRateioExiste = $this->where('id_empresa', auth()->user()->id)
            ->where('nome', $data['nome'])->count();

        if ($consultaSeRateioExiste == 0) {
            return false;
        }

        return true;
    }

    /**
     * Adiciona no banco de dados o rateio;
     *
     * @param $data recebe o $arrayName = array('nome' => 'nome_rateio',
     *              'id_empresa' => 'id_empresa',
     *              'centro_custo_id_percent' => [ 'centro_custo' =>
     *              ['id' => 'id_centro_custo', 'percent' => 'valor']]);
     *
     * @return array
     */
    public function adicionarRateio(array $data) : array
    {
        $result =$this->create(
            [
                'id_empresa' => auth()->user()->id_empresa,
                'nome' => $data['nome'],
                'centro_custo_id_percent' => $data['centro_custo_id_percent']
            ]
        );

        if ($result) {
            return ['message' => ['id' => $result->id]];
        }

        return ['message' => ['fail']];
    }

    public function getApportionmentList($queryRateio, $pageRateioSearch, $limitRateio = 10)
    {
        $rateio = Rateio::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->where('nome', 'like', "%$queryRateio%")
            ->skip(($pageRateioSearch - 1) * $limitRateio)
            ->take($limitRateio)
            ->get();

        if($rateio->count() == 0){
            return  [['id' => null, 'nome' => 'Nenhum rateio encontrado']];
        }
        if($rateio->count() >= 1 and $pageRateioSearch == 1){
            return $rateio->toArray();
        }
        if($rateio->count() >= 1 and $pageRateioSearch > 1){
            return $rateio->toArray();
        }
        return;
    }

}
