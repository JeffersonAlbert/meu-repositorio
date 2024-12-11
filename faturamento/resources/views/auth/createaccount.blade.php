<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Number</title>
    <link rel="icon" href="{{ asset('img/static/favicon-wh.ico') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-number">

    <div class="container">
        <div class="row mt-3">
            <div class="col-6">
                <div class="row">
                    <div class="logo-principal"></div>
                </div>
                <div class="row mt-3">
                    <p class="font-registration-black font-regular-dt">A sua solução</p>
                </div>
                <div class="row">
                    <p class="font-registration-green font-regular-dt">em um só lugar.</p>
                </div>
                <div class="row">
                    <p class="font-registration-black font-regular-dt" style="font-size: 22px">A gestão de processos facilitada </p>
                </div>
                <div class="row">
                    <p class="font-registration-black font-regular-dt" style="font-size: 22px">como você nunca viu antes.</p>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-registration o-hidden border-0 shadow-lg">
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger">
                            <p>{!! session('error') !!}</p>
                        </div>
                        @endif
                        <div id="registration-msg" class="alert alert-danger" style="display: none">
                        </div>
                        <form id='registrationForm' action="{{ route('register.custom') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="sidebar-brand-icon logo-number-wt mr-2"></div>
                                <div class="logo-principal"></div>
                            </div>
                            <div id="registration-error" class="mt-1"></div>
                            <div class="row justify-content-center mt-2">
                                <h3 class="font-regular-dt font-login">Cadastro</h3>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-12">
                                    <div class="line-container-number ">
                                        <div class="line-number"></div>
                                        <div class="ainda-nao-number">Dados da empresa</div>
                                        <div class="line-number"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-6">
                                    <input id="cnpj" name="cpf_cnpj" type="text" class="input-login form-control" placeholder="Seu cnpj">
                                </div>
                                <div class="col-6">
                                    <input id="inscricao_estadual" name="inscricao_estadual" type="text" class="input-login form-control" placeholder="Sua I.E.">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-12">
                                    <input id="razao_social" name="razao_social" type="text" class="input-login form-control" placeholder="Sua razão social">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-12">
                                    <input id="nome_fantasia" name="nome_fantasia" type="text" class="input-login form-control" placeholder="Seu nome fantasia">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-4">
                                    <input id="cep" name="cep" type="text" class="input-login form-control" placeholder="Cep">
                                </div>
                                <div class="col-8">
                                    <input id="logradouro" name="logradouro" type="text" class="input-login form-control" placeholder="Sua rua">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-sm-3">
                                    <input id="numero" name="numero" type="text" class="input-login form-control" placeholder="N.º">
                                </div>
                                <div class="col-9">
                                    <input id="localidade" name="cidade" type="text" class="input-login form-control" placeholder="Sua cidade">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-6">
                                    <input id="bairro" name="bairro" type="text" class="input-login form-control" placeholder="Seu bairro">
                                </div>
                                <div class="col-6">
                                    <input id="complemento" name="complemento" type="text" class="input-login form-control" placeholder="Seu complemento">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-12">
                                    <div class="line-container-number ">
                                        <div class="line-number"></div>
                                        <div class="ainda-nao-number">Dados do usuário</div>
                                        <div class="line-number"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-12">
                                    <input id="name" name="name" type="text" class="input-login form-control" placeholder="Insira seu nome">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-12">
                                    <input id="last_name" name="last_name" type="text" class="input-login form-control" placeholder="Insira seu sobrenome">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-12">
                                    <input id="email" name="email" type="email" class="input-login form-control" placeholder="Insira seu e-mail">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-12">
                                    <input id="password" name="password" type="password" class="input-login form-control" placeholder="Insira a senha">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <button id="sendRegistro" class="font-regular-dt font-btn-login btn btn-success btn-success-number btn-lg w-100 form-control">Cadastrar</button>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="line-container-number ">
                                    <div class="line-number"></div>
                                    <div class="ainda-nao-number">Já tem uma conta?</div>
                                    <div class="line-number"></div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <a href="{{ route('login') }}" class="font-regular-dt btn btn-light btn-opaco-number btn-lg w-100 form-control">Entrar</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/functions-number.js"></script>

    @include('auth.js')
</body>

</html>
