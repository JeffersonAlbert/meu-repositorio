<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SYS\MessagesController;
use App\Mail\RecuperaSenha;
use App\Mail\Registro;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            User::userPermissions();
            User::userBranches();
            return redirect()->route('inicio');
        }
        return view('auth.newLogin');
    }


    public function customLogin(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = auth()->user();

            if (!$user->enabled) {
                return redirect('login')->withErrors($validator)->with('error', 'Conta disabilitada')->withInput();
            }
            $empresa = Empresa::where('id', $user->id_empresa)->first();
            if (!$empresa->validate) {
                return redirect('login')->withErrors($validator)->with('error', 'Conta não validada')->withInput();
            }

            User::userPermissions();
            User::userBranches();
            return redirect()->intended('dashboard')
                ->withSuccess('Signed in');
        }

        return redirect("login")->withErrors($validator)->with('error', 'Dados de login invalidos')->withInput();
    }



    public function registration()
    {
        return view('auth.createaccount');
    }


    public function customRegistration(Request $request)
    {
        $messages = [
            'razao_social.required' => "Precisa enviar a razão social da empresa",
            'nome_fantasia.required' => "Precisa enviar o nome fantasia da empresa",
            'email.required' => "Precisa enviar o email do usuário que vai utilizar o sistema",
            'email.unique' => "Esse email ja esta cadastrado no sistema",
            'email.email' => "Precisa enviar um email para o sistema",
            'password.required' => "Precisa cadastrar aa senha",
            'password.min' => "A senha tem de ter no minimo 6 caracteres",
            'cpf_cnpj.required' => "Precisa enviar cnpj",
            'cpf_cnpj.unique' => "Cnpj já cadastrado no sistema",
            'cpf_cnpj.min' => "Cnpj precisa ter no minimo 14 numeros",
            'cep.required' => "CEP é obrigatório",
            'cep.min' => "CEP precisa ter no minimo 8 números",
            'logradouro.required' => "Rua é obrigatório",
            'numero.required' => "Número da rua é obrigatório",
            'cidade.required' => "Cidade é obrigatório",
            'bairro.required' => "Bairro é obrigatório"
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'razao_social' => 'required',
                'nome_fantasia' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'cpf_cnpj' => 'required|unique:empresa|min:14',
                'cep' => 'required|min:8',
                'logradouro' => 'required',
                'numero' => 'required',
                'cidade' => 'required',
                'bairro' => 'required',
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                422
            );
        }

        $token = $this->createTokenWithExpiration();

        $dataEmpresa = $request->except('_token', 'email', 'password');
        $dataEmpresa['endereco'] = $request->get('logradouro');
        $dataEmpresa['nome'] = $request->get('nome_fantasia');
        $dataEmpresa['token'] = $token['token'];
        $dataEmpresa['expiration_time'] = date('Y-m-d H:i:s', $token['expiration_time']);

        $empresa = Empresa::create($dataEmpresa);
        $codigoVerificacao = $this->gerarCodigoVerificacao();
        $dataUser = [
            'name' => $request->get('name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'id_empresa' => $empresa->id,
            'token' => $codigoVerificacao,
            'administrator' => true
        ];

        if (!$empresa) {
            return response()->json(
                [
                    'errors' => [
                        'mensagem' => [
                            'Não foi possível salvar a empresa'
                        ]
                    ]
                ],
                422
            );
        }

        $check = User::create($dataUser);

        if (!$check) {
            return response()->json(
                [
                    'errors' => [
                        'mensagem' => [
                            'Não foi possível salvar o usuário'
                        ]
                    ]
                ],
                422
            );
        }
        $dados = [
            'email' => $request->get('email'),
            'token' => $token['token'],
            'expiration_time' => $token['expiration_time'],
            'name' => $request->get('name'),
            'last_name' => $request->get('last_name'),
            'codigo_verificacao' => $codigoVerificacao
        ];

        $email = new Registro($dados);
        Mail::to($request->get('email'))->send($email);

        return response()->json(
            [
                'success' => [
                    'mensagem' => [
                        'Incluído com sucesso'
                    ],
                    'email' => [
                        $request->get('email')
                    ]
                ]
            ]
        );
    }

    public function gerarCodigoVerificacao()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function validaCadastro($token)
    {
        $carbon = new Carbon();
        $data = $carbon->now();
        $dados = Empresa::select('empresa.razao_social as razao_social', 'users.name as nome', 'users.email as email', 'empresa.expiration_time')
            ->leftJoin('users', 'users.id_empresa', 'empresa.id')
            ->where('empresa.token', $token)
            ->where('validate', false)
            ->where('empresa.expiration_time', '>', $data)
            ->first();

        if (is_null($dados)) {
            $dados = Empresa::select(
                'empresa.razao_social as razao_social',
                'users.name as nome',
                'users.last_name',
                'users.email as email',
                'empresa.expiration_time'
            )
                ->leftJoin('users', 'users.id_empresa', 'empresa.id')
                ->where('empresa.token', $token)
                ->where('validate', false)
                ->first();

            if (is_null($dados)) {
                $dados = Empresa::select(
                    'empresa.razao_social as razao_social',
                    'users.name as nome',
                    'users.last_name',
                    'users.email as email',
                    'empresa.expiration_time'
                )
                    ->leftJoin('users', 'users.id_empresa', 'empresa.id')
                    ->where('empresa.token', $token)
                    ->where('validate', true)
                    ->first();

                if (is_null($dados)) {
                    return "erro";
                }

                return redirect('login')->with('error', 'registro já validado');
            }

            return view('auth.revalidartoken', compact('token', 'dados'));
        }

        return view('auth.registrationvalidator', compact('token', 'dados'));
    }

    public function revalidarToken(Request $request)
    {
        $token = $this->createTokenWithExpiration();
        $update = Empresa::where('token', $request->token)->update([
            'token' => $token['token'],
            'expiration_time' => date('Y-m-d H:i:s', $token['expiration_time'])
        ]);

        $codigoVerificacao = $this->gerarCodigoVerificacao();

        $dados = [
            'email' => $request->get('email'),
            'token' => $token['token'],
            'expiration_time' => $token['expiration_time'],
            'name' => $request->get('nome'),
            'last_name' => $request->get('last_name'),
            'codigo_verificacao' => $codigoVerificacao
        ];

        $updateUser = User::where('email', $request->get('email'))->update(['token' => $codigoVerificacao]);

        $email = new Registro($dados);
        Mail::to($request->get('email'))->send($email);

        return redirect()->route('post-registration', ['email' => $request->get('email')]);
    }

    public function tokenValidator(Request $request)
    {
        $dados = Empresa::select('empresa.id as id', 'empresa.razao_social as razao_social', 'users.name as nome', 'users.email as email')
            ->leftJoin('users', 'users.id_empresa', 'empresa.id')
            ->where('empresa.token', $request->get('token'))
            ->where('users.token', implode($request->get('codigo')))
            ->where('validate', false)
            ->where('empresa.expiration_time', '>', date('Y-m-d H:i:s'))
            ->first();

        if (is_null($dados)) {
            return redirect()->back()->with('error', 'Token invalido');
        }

        Empresa::where('id', $dados->id)->update([
            'validate' => true
        ]);

        return redirect('login');
    }

    public function postRegistration($email)
    {
        return view('auth.successregistration', compact('email'));
    }

    public function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    // Função para criar um token e definir o tempo de expiração
    public function createTokenWithExpiration($expirationMinutes = 30)
    {
        // Gerar um token único
        $token = $this->generateToken();

        // Definir o tempo de expiração
        $expirationTime = time() + ($expirationMinutes * 60);

        // Retornar um array contendo o token e o tempo de expiração
        return [
            'token' => $token,
            'expiration_time' => $expirationTime
        ];
    }


    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'administrator' => true
        ]);
    }


    public function dashboard()
    {
        if (Auth::check()) {
            return redirect('inicio');
        }

        return redirect("login");
    }


    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

    public function esqueceuSenha()
    {
        return view('auth.recuperarsenha');
    }

    public function recuperarSenha(Request $request)
    {
        $conta = User::where('email', $request->get('email'))->first();

        if (is_null($conta)) {
            return view('auth.errorenviosenha');
        }

        $token = $this->createTokenWithExpiration();
        $codigoVerificacao = $this->gerarCodigoVerificacao();

        $dados = [
            'name' => $conta->name,
            'last_name' => $conta->last_name,
            'email' => $conta->email,
            'codigo_verificacao' => $codigoVerificacao,
            'token' => $token['token'],
            'expiration_time' => $token['expiration_time']
        ];

        $email = new RecuperaSenha($dados);
        Mail::to($request->get('email'))->send($email);

        $update = User::where('email', $request->get('email'))->update([
            'token_url' => $token['token'],
            'token_password' => $codigoVerificacao,
            'expiration_time'  => date('Y-m-d H:i:s', $token['expiration_time'])
        ]);

        return view('auth.successsenviosenha', compact('dados'));
    }

    public function novaSenha($token)
    {
        $user = User::where('token_url', $token)
            ->where('expiration_time', '>', date('Y-m-d H:i:s'))
            ->first();

        if (is_null($user)) {
            $user  = User::where('token_url', $token)
                ->first();
            return view('auth.reenviartokensenha', compact('user'));
        }

        return view('auth.novasenha', compact('user'));
    }

    public function revalidarTokenUsuario(Request $request)
    {
        $user = User::where('token_url', $request->get('token_url'))
            ->where('token_password', $request->get('token_password'))
            ->where('email', $request->get('email'))
            ->first();

        if (is_null($user)) {
            return 'não existe esse usuario';
        }

        $token = $this->createTokenWithExpiration();
        $codigoVerificacao = $this->gerarCodigoVerificacao();

        $dados = [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'codigo_verificacao' => $codigoVerificacao,
            'token' => $token['token'],
            'expiration_time' => $token['expiration_time']
        ];

        $email = new RecuperaSenha($dados);
        Mail::to($request->get('email'))->send($email);

        $update = User::where('email', $request->get('email'))->update([
            'token_url' => $token['token'],
            'token_password' => $codigoVerificacao,
            'expiration_time'  => date('Y-m-d H:i:s', $token['expiration_time'])
        ]);

        return  view('auth.successreenviotokenuser', compact('user'));
    }

    public function enviarNovaSenha(Request $request)
    {
        $messages = [
            'password.min' => 'Senhas precisa pelo menos de 6 caracteres!',
            'password.required' => 'Senha precisa ser preenchida',
            'password.regex' => 'Senha precisa de ao menos A-Z a-z 0-9 e um caracter especial!',
            're-password.same' => 'Confirmação de senha precisa ser igual a senha',
            're-password.required'  => 'Confirmação precisa pelo menos de 6 caracteres!',
            'token_password' => 'Precisa enviar o  token recebido no e-mail'
        ];

        $request->validate([
            'password' => [
                'required',
                'min:6',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%*()?&]/'
            ],
            're-password' => [
                'required',
                'same:password',
            ],
            'token_password' => [
                'required'
            ],
        ], $messages);

        $update = User::where('token_url', $request->get('token'))
            ->where('token_password', $request->get('token_password'))
            ->where('email', $request->get('email'))
            ->update([
                'password' => bcrypt($request->get('password'))
            ]);

        if (!$update) {
            return redirect()->back()->with('error', 'Token invalido');
        }

        return redirect()->route('login')->with('success', 'Senha alterada com sucesso!');
    }
}
