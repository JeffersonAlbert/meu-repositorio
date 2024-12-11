<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        /* Estilos para o container principal */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        /* Estilos para o logo */
        .logo {
            width: 36px; /* Ajuste o tamanho conforme necessário */
            height: 43px;
            margin-bottom: 20px;
        }

        .logo_principal {
            width: 108px; /* Ajuste o tamanho conforme necessário */
            height: 21px;
            margin-bottom: 20px;
        }

        /* Estilos para o conteúdo */
        .content {
            background-color: #f2f2f2; /* Cor de fundo do conteúdo */
            padding: 20px;
        }

    </style>
</head>
<body style="background: #D8F3E6">
    <div class="container">
        <!-- Logo -->
        <img src="{{ asset('/img/static/logo_number-wt.png') }}" alt="Logo" class="logo">
        <img src="{{ asset('/img/static/logo_principal.png') }}" alt="Logo principa" class="logo_principal">

        <!-- Conteúdo -->
        <div class="content">
            <h1>Ola {{ $data['name'] }} {{ $data['last_name'] }}!</h1>
            <span>
                Para garantir a segurança de sua conta e proteger seus dados, solicitamos que você confirme sua identidade inserindo <a href="{{ route('nova-senha', ['token' => $data['token']]) }}">neste link</a> o código de validação abaixo:
            <span>
                <br><br>

            <span>Código de Validação: <b>{{ $data['codigo_verificacao'] }}</b></span>
            <br><br>

            <span>Por favor, insira este código na página de confirmação para continuar utilizando nossos serviços. Este código expirará em {{ date('d/m/Y H:m:s', $data['expiration_time']) }}, então por favor, conclua esta ação o mais rápido possível.

                Se você não solicitou este código ou se tiver alguma dúvida, entre em contato conosco imediatamente.

                Obrigado por sua cooperação.
            </span>
            <br><br>

            <span>Atenciosamente,</span><br>

            <span>Number.</span>
        </div>
    </div>
</body>
</html>
