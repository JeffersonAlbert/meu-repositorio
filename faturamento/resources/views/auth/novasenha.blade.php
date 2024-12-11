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
            <div class="card card-login o-hidden border-0 shadow-lg my-5">
                <div class="card-body">
                    <form action="{{ route('enviar-nova-senha') }}" method="POST">
                        @csrf
                        <input type="hidden" name='token' value="{{ $user->token_url }}">
                        <input type="hidden" name='email' value="{{ $user->email }}">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <p>{!! session('error') !!}</p>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row justify-content-center">
                            <div class="sidebar-brand-icon logo-number-wt mr-2"></div>
                            <div class="logo-principal"></div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <h3 class="font-regular-dt font-login">Insira sua nova senha</h3>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <input name="password" type="password" class="input-login form-control" placeholder="Sua senha">
                        </div>
                        <div class="row justify-content-center mt-3">
                            <input name="re-password" type="password" class="input-login form-control" placeholder="Confirme">
                        </div>
                        <div class="row justify-content-center mt-3">
                            <input name="token_password" type="text" class="input-login form-control" placeholder="Token">
                        </div>
                        <div class="row justify-content-center mt-3">
                            <button class="btn btn-success btn-success-number">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
