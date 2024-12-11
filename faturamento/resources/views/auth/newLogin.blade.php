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
        <div class="row justify-content-center">

            <!-- <img src="{{ asset('/img/static/one-left.svg') }}" alt="Imagem Esquerda" class="left-background-image">
            <img src="{{ asset('/img/static/one-right.svg') }}" alt="Imagem Direita" class="right-background-image"> -->

            <div class="card card-login o-hidden border-0 shadow-lg my-5">
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <p>{!! session('error') !!}</p>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            <p>{!! session('success') !!}</p>
                        </div>
                    @endif
                    <form action="{{ route('login.custom') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="sidebar-brand-icon logo-number-wt mr-2"></div>
                            <div class="logo-principal"></div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <h3 class="font-regular-dt font-login">Seja bem vindo(a)!</h3>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <input id="email" name="email" type="email" class="input-login form-control" placeholder="Insira seu e-mail">
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <input id="password" name="password" type="password" class="input-login form-control" placeholder="Insira a senha">
                            </div>
                        </div>
                        <div class="row  mt-2">
                            <div class="form-check">
                                <input class="form-check-input login-checkbox" type="checkbox" name="remember" id="flexCheckDefault">
                                <label class="form-check-label login-texto-checkbox" for="flexCheckDefault">
                                Lembrar senha
                                </label>
                            </div>
                            <div class="form-check text-right ml-auto">
                                <label class="form-check-label login-texto-checkbox" for="flexCheckDefault">
                                <a id="esqueceu_senha" href="{{ route('esqueceu-senha') }}">Esqueceu a senha?</a>
                                </label>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <button type="submit" class="font-regular-dt font-btn-login btn btn-success btn-success-number btn-lg w-100 form-control">Acessar conta!</button>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="line-container-number ">
                                    <div class="line-number"></div>
                                    <div class="ainda-nao-number">Ainda não é um Number?</div>
                                    <div class="line-number"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <a href="{{ route('register-user') }}" class="font-regular-dt btn btn-light btn-opaco-number btn-lg w-100 form-control">Criar conta</a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="text-left mr-auto">
                                <label class="form-check-label login-texto-checkbox" for="flexCheckDefault">
                                    <a id='termos_uso' href="#">Termos de uso</a>
                                </label>
                            </div>
                            <div class="text-right ml-auto">
                                <label class="form-check-label login-texto-checkbox" for="flexCheckDefault">
                                    <a id="central_ajuda" href="#">Central de ajuda</a>
                                </label>
                            </div>
                        </div>
                    </form>
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

</body>

</html>
