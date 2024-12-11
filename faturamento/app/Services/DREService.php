<?php

namespace App\Services;

use App\Models\DRE;
use App\Models\SubCategoriaDRE;
use Illuminate\Support\Facades\Validator;

class DREService
{
    private $query;

    public function __construct()
    {
       $this->query = SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
           ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id');

    }

    public function getAllDREByType(string $type) : object
    {
         $lala = $this->query->where('sub_categoria_dre.id_empresa',
             auth()->user()->id_empresa)
             ->where('dre.tipo', $type)
             ->orWhere('dre.tipo', $type)
             ->whereNull('sub_categoria_dre.id_empresa')
             ->where('tipo', $type)
             ->get();
         return $lala;
    }

    public function getAllDREByString($type, $query, $dreLimit = null, $pageSearch = null)
    {
        $lala = $this->query->where('sub_categoria_dre.id_empresa',
            auth()->user()->id_empresa)
            ->where('dre.tipo', $type)
            ->where('sub_categoria_dre.descricao', 'like', '%'.$query.'%')
            ->orWhere('dre.tipo', $type)
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', $type)
            ->where('sub_categoria_dre.descricao', 'like', '%'.$query.'%');
        if($dreLimit != null && $pageSearch != null){
            $lala = $lala->skip(($pageSearch - 1) * $dreLimit)
                ->take($dreLimit);
        }
        $lala = $lala->get();
        return $lala;
    }

    public function storeDRE(array $data) : object
    {
        $validate = $this->validateArrayDre($data);

        if(!json_decode($validate)->success){
            return json_decode($validate);
        }

        $result = DRE::create([
            'nome' => $data['descricao'],
            'tipo' => $data['tipo'],
            'codigo' => '0',
            'id_empresa' => auth()->user()->id_empresa,
            'id_usuario' => auth()->user()->id,
            'editable' => true
        ]);

        return json_decode(json_encode([
            'success' => true,
            'message' => 'DRE criada com sucesso',
        ]));
    }
    public function validateArrayDre(array $data) : string
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Descrição é obrigatória',
            'id_dre.required' => 'DRE é obrigatória',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if($validator->fails()){
            return json_encode([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        $result = $validator->validate();
        $result['success'] = true;
        return json_encode($result);
    }
    public function storeSubDRE(array $data) : object
    {

        $result = SubCategoriaDRE::create([
            'descricao' => $data['descricao'],
            'id_dre' => $data['id_dre'],
            'id_empresa' => auth()->user()->id_empresa,
            'id_usuario' => auth()->user()->id,
            'editable' => true
        ]);

        return json_decode(json_encode(
            [
                'success' => true,
                'message' => 'Subcategoria DRE criada com sucesso',
            ]
        ));
    }
}
