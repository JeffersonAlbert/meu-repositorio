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
                        <h3 class="font-regular-dt font-login">Cadastro feito com sucesso!</h3>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="font-regular-dt font-login" style="font-size: 18px">
                            Um email de confirmação foi enviado para {{ $dados['email'] }}.
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="font-regular-dt font-login" style="font-size: 18px">
                            Por favor confira esse email para alterarmos sua senha.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
