<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAlerts(Request $request)
    {
        return Messages::where('id_empresa', auth()->user()->id_empresa)
        ->where('id_usuario', auth()->user()->id)
        ->where('visto', false)->get();
    }

    public function setRead(Request $request)
    {
        $messages = Messages::where('id', $request->get('id'))->update(['visto' => true]);
        if($messages){
            return response()->json(["message" => "Atualizado com sucesso"]);
        }
        return response()->json(["error" => ["message" => ["Não foi possivel fazer a atualização"]]], 402);
    }
}
