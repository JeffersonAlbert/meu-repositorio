<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\GruposProcesso;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
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
        if (!auth()->user()->master && !auth()->user()->administrator) {
            return redirect()->back()->with('error', "Seu usuario não e administrador");
        }

        if (auth()->user()->master == true) {
            $users = User::all();
        }

        if (is_null(auth()->user()->master) || auth()->user()->master == false) {
            $users = User::select(
                'users.id',
                'users.name as name',
                'users.email as email',
                'users.enabled as enabled',
                'users.updated_at as updated_at',
                'filial.nome as filial_nome'
            )
                ->leftJoin('filial', function ($join) {
                    $join->on(
                        DB::raw("JSON_UNQUOTE(JSON_EXTRACT(users.id_filiais, '$[0]'))"),
                        '=',
                        'filial.id'
                    );
                })
                ->where('users.id_empresa', auth()->user()->id_empresa)
                ->get();
        }

        return view('usuarios.grid', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->master && !auth()->user()->administrator) {
            return redirect()->back()->with('error', "Seu usuario não e administrador");
        }
        $ccsChecked = [];
        $ccs = []; //Departamento::all();
        $empresas = (auth()->user()->master) ? Empresa::all() : Empresa::find(auth()->user()->id_empresa);
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        $grupos = GruposProcesso::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('usuarios.form', compact('ccs', 'ccsChecked', 'empresas', 'filiais', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_empresa = $request->get('id_empresa');
        $email = $request->get('email');
        $mensagens = [
            'name.required' => 'O campo nome é obrigatorio!',
            'email.required' => 'O campo e-mail é obrigatório!',
            'email.email' => 'Digite um e-mail valido',
            'email.unique' => 'Esse email ja existe por favor escolha outro!',
            'confirm-password.same' => 'Senhas devem ser iguais!',
            'regex' => 'Senha precisa de ao menos A-Z a-z 0-9 e um caracter especial!',
            'min' => 'Senhas precisa pelo menos de 6 caracteres!',
            'cep.required' => 'Cep é um campo obrigatorio'
        ];

        $request->validate([
            'name' => 'required',
            //'email' => 'required|email|unique:users',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'id_empresa')->where(function ($query) use ($email, $id_empresa) {
                    return $query->where('email', $email)->where('id_empresa', $id_empresa);
                })
            ],
            'password' => [
                'required',
                'min:6',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*()?&]/'
            ],
            'confirm-password' => [
                'required',
                'min:6',
                'same:password',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*&()?]/'
            ],
        ], $mensagens);

        $data = $request->only('name', 'email', 'password', 'last_name', 'id_empresa');

        $data['financeiro'] = is_null($request->get('financeiro')) ? false : true;

        $idCentroCusto = $request->except('_token', 'name', 'email', 'password', 'last_name', 'confirm-password', 'grupo');

        $centrocusto = [];

        foreach ($idCentroCusto as $id) {
            array_push($centrocusto, $id);
        }
        $data['password'] = Hash::make($data['password']);
        $data['centrocustos'] = json_encode($centrocusto);
        $data['id_grupos'] = $request->get('grupo') !== null ? json_encode($request->get('grupo')) : null;

        if ($request->get('receber') !== null) {
            $data['receber_contas'] = $request->get('receber');
        }

        $addUser = User::create($data);
        User::userPermissions();
        if (!$addUser) {
            return redirect()->back()->with('error', "Não foi possivel criar o usuario {$data['email']}");
        }

        return redirect('usuarios')->with('success', "Usuario {$data['email']} criado com sucesso id: {$addUser->id}");
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
        $user = User::select('users.*', 'empresa.razao_social as empresa_name')->leftJoin('empresa', 'empresa.id', 'users.id_empresa')->find($id);

        if (auth()->user()->master == true) {
            $empresas = Empresa::all();
        }

        if (auth()->user()->master == false) {
            $empresas = Empresa::where('id', $user->id_empresa)->get();
        }

        $grupo_processos = [];
        if (!is_null($user->id_grupos)) {
            $grupo_processos = GruposProcesso::select('id', 'nome')
                ->whereIn('id', json_decode($user->id_grupos))
                ->get();
        }

        $centroCustosIds = json_decode($user->centrocustos);

        if (!is_null($centroCustosIds)) {
            $ccsChecked = Departamento::select('id', 'nome')
                ->whereIn('id', $centroCustosIds)
                ->get();
            $ccs = Departamento::select('id', 'nome')
                ->whereNotIn('id', $centroCustosIds)
                ->get();
        }

        if (is_null($centroCustosIds)) {
            $ccsChecked = [];
            $ccs = Departamento::select('id', 'nome')->get();
        }

        $grupos = GruposProcesso::where('id_empresa', auth()->user()->id_empresa)->get();

        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();

        return view('usuarios.form', compact('user', 'ccs', 'ccsChecked', 'empresas', 'grupo_processos', 'filiais', 'grupos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mensagens = [
            'name.required' => 'O campo nome é requerido!',
            'email.required' => 'O campo e-mail é obrigatorio!',
            'confirm-password.same' => 'Senhas precisam ser iguais',
            'confirm-password.regex' => 'Para manter a senha antiga deixe em branco ou crie uma nova com 8 caracteres com pelo menos 1 maiusculo 1 especial 1 numero',
            'password.regex' => 'Para manter a senha antiga deixe em branco ou crie uma nova com 8 caracteres com pelo menos 1 maiusculo 1 especial 1 numero',
            'password.min' => 'Senha precisa conter ao menos 8 caracteres',
            'confirm-password.min' => 'Confirmação de senha precisa ter ao menos 8 carcteres'
        ];
        $request->validate([
            'confirm-password' => [
                'same:password',
                'nullable',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*&()?]/'
            ],
            'email' => 'required|email',
            'name' => 'required',
            'password' => [
                'nullable',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*&()?]/'
            ],
        ], $mensagens);

        $data = is_null($request->get('password')) ? $request->only('name', 'last_name', 'email', 'id_empresa') : $request->only('name', 'last_name', 'email', 'password', 'id_empresa');

        $data['financeiro'] = is_null($request->get('financeiro')) ? false : true;

        $idCentroCusto = $request->except('_method', '_token', 'name', 'email', 'password', 'last_name', 'confirm-password', 'grupo', 'filiais');

        $centrocusto = [];

        foreach ($idCentroCusto as $cc) {
            array_push($centrocusto, $cc);
        }

        $data['master'] = $request->get('master') == null ? false : true;
        $data['administrator'] = $request->get('administrator') == null ? false : true;
        $data['id_filiais'] = $request->get('filiais') == null ? null : json_encode($request->get('filiais'));

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->get('grupo') !== null) {
            $data['id_grupos'] = json_encode($request->get('grupo'));
        }

        if ($request->get('grupo') == null) {
            $data['id_grupos'] = null;
        }

        $data['receber_contas'] = $request->get('receber') == 1 ? true : false;

        $data['centrocustos'] = json_encode($centrocusto);

        $update = User::where('id', $id)->update($data);
        User::userPermissions();
        User::userBranches();

        if ($update) {
            return redirect('usuarios')->with("Alterado com sucesso usuario {$data['name']}, id {$id}");
        }

        return redirect()->back()->with("Não foi possivel alterar o usuario {$data['name']}, id {$id}, contate o administrado");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function profile(string $id)
    {
        $user = User::select('users.id as id', 'users.name as name', 'users.last_name as last_name', 'users.email', 'users.perfil_img', 'empresa.nome as empresa')
            ->leftJoin('empresa', 'empresa.id', 'users.id_empresa')
            ->where('users.id', auth()->user()->id)
            ->first();
        return view('usuarios.profile', compact('user'));
    }

    public function uploadImgPerfil(Request $request)
    {
        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $fileName = time() . $file->getClientOriginalName();
            $file->move(public_path('img/static/perfil/'), $fileName);

            $user = User::find(auth()->user()->id);
            $user->perfil_img = $fileName;
            $user->save();
            return response()->json(['success' => 'Arquivo enviado com sucesso', 'img' => asset('img/static/perfil/' . $fileName)]);
        }
        return response()->json(['error' => ['message' => ['Nenhum arquivo foi enviado']]]);
    }

    public function updatePass(Request $request)
    {
        $messages = [
            'password.min' => 'Senha precisa ter no minimo 8 caracteres',
            'password.regex' => 'Precisa conter pelo menos 1 caracter maiusculo 1 numerico e um caracter especial',
            'confirmPass.min' => 'Senha precisa ter no minimo 8 caracteres',
            'confirmPass.regex' => 'Precisa conter pelo menos 1 caracter maiusculo 1 numerico e um caracter especial',
        ];

        $validator = Validator::make($request->all(), [
            'password' => [
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*&()?]/'
            ],
            "confirmPass" => [
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*&()?]/'
            ]

        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->get('id_user'));
        $user->password = Hash::make($request->get('password'));
        $user->save();
        return response()->json(['success' => "Alterado com sucesso"]);
    }

    public function disable($id, $tipo)
    {
        $user = auth()->user();
        if ($id == $user->id) {
            return response()->json(['error' => ['message' => ['Você não pode bloquear seu proprio usuario']]]);
        }

        $enabled = $tipo == 'enable' ? true : false;
        $user = User::where('id', $id)->update(['enabled' => $enabled]);

        return response()->json(['success' => ['message' => ['Usúario desabilitado']]]);
    }

    public function gridLines(Request $request)
    {
        $user = auth()->user();
        $userUpdate = User::where('id', $user->id)->update(['linhas_grid' => $request->get('lines')]);
        if ($userUpdate) {
            return;
        }
    }
}
