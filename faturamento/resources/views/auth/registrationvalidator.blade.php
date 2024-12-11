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
            <div class="card card-login o-hidden border-0 shadow-lg my-5 w-100">
                <div class="card-body">
                    <form id="gerarCodigo" action="{{ route('validate-token') }}" method="POST">
                        @csrf
                        <input type="hidden" name="expiration_time" value="{{ $dados->expiration_time }}">
                        <input type="hidden" name="token" value="{{ $token }}">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <p>{!! session('error') !!}</p>
                            </div>
                        @endif
                        <div class="row justify-content-center">
                            <div class="sidebar-brand-icon logo-number-wt mr-2"></div>
                            <div class="logo-principal"></div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <h3 class="font-regular-dt font-login">Validação do cadastro</h3>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="font-regular-dt font-login" style="font-size: 18px">{{ $dados->razao_social }}</div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="font-regular-dt font-login" style="font-size: 18px">
                                Prezado {{ $dados->nome }} insira o codigo de 6 digitos enviado no e-mail
                                {{ $dados->email }} nos campos abaixo.
                            </div>
                            <div class="row justify-content-center w-100 mt-3">
                                <div class="col-2">
                                    <input id="codigo" name="codigo[]" type="text" maxlength="1" class="input-login form-control input-codigo">
                                </div>
                                <div class="col-2">
                                    <input id="codigo" name="codigo[]" type="text" maxlength="1" class="input-login form-control input-codigo">
                                </div>
                                <div class="col-2">
                                    <input id="codigo" name="codigo[]" type="text" maxlength="1" class="input-login form-control input-codigo">
                                </div>
                                <div class="col-2">
                                    <input id="codigo" name="codigo[]" type="text" maxlength="1" class="input-login form-control input-codigo">
                                </div>
                                <div class="col-2">
                                    <input id="codigo" name="codigo[]" type="text" maxlength="1" class="input-login form-control input-codigo">
                                </div>
                                <div class="col-2">
                                    <input id="codigo" name="codigo[]" type="text" maxlength="1" class="input-login form-control input-codigo">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <button class="btn btn-lg btn-success btn-success-number">Validar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('js/functions-number.js') }}"></script>
    @include('auth.js')
</body>

</html>
