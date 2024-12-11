<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Orçamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }
        .container {
            width: 100%;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            border: 1px solid #000; /* Borda preta */
            padding: 10px;
            vertical-align: top;
        }
        .logo {
            width: 90px;
            height: 90px;
        }
        .company-details {
            text-align: left;
            vertical-align: top;
        }
        .client-details {
            border: 1px solid #000; /* Borda preta */
            padding: 10px;
            width: 100%;
        }
        .client-table {
            width: 100%;
            border-collapse: collapse;
        }
        .client-table td {
            border: 1px solid #000; /* Borda preta */
            padding: 10px;
        }
        .client-table .client-info {
            width: 70%;
            vertical-align: top;
        }
        .client-table .os-info {
            width: 30%;
            vertical-align: top;
        }
        .client-details h2 {
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 5px;
        }
        .services-title {
            text-align: center;
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 16px;
        }
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .services-table th,
        .services-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        .services-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .total-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: right;
        }
        .total-label {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class='container'>
    <table class='header-table'>
        <tr>
            <!-- Logo do Cliente -->
            <td class='logo'>
                <img src="{{ $logo }}" alt='Logo do Cliente' style='width: 90px; height: 90px;'>
            </td>

            <!-- Dados da Empresa Emitente -->
            <td class='company-details'>
                <div class='info'>{{ $emitente->nome }}</div>
                <div class='info'>{{ $emitente->endereco }} {{ $emitente->numero }}</div>
                <div class='info'>{{ $emitente->cidade }}, {{ $emitente->uf }}, {{ $cepFormatadoEmitente }}</div>
                <div class='info'>Telefone: {{ $telefoneFormatadoEmitente }}</div>
                <div class='info'>Email: {{ $emitente->email }}</div>
            </td>
        </tr>
    </table>

    <!-- Dados do Cliente -->
    <table class='client-table' style='margin-top: 40px; margin-bottom: 40px;'>
        <tr>
            <td class='client-info'>
                <div class='info'>Nome do Cliente: {{$cliente->nome}}</div>
                <div class='info'>Endereço: {{$cliente->endereco}}, {{$cliente->numero}}</div>
                <div class='info'>{{$cliente->cidade}}, {{$cliente->estado}}, {{$cepFormatadoCliente}}</div>
                <div class='info'>Telefone: {{$telefoneFormatadoCliente}}</div>
            </td>
            <td class='os-info'>
                <div class='info'>Número da OS: {{$venda->id}}</div>
                <div class='info'>Data: {{$competencia}}</div>
                <div class='info'>Validade: 30 dias</div>
            </td>
        </tr>
    </table>
    <div class='services-title'>Serviços/Produtos</div>
    <table class='services-table'>
        <thead>
        <tr>
            <td>Produto/Serviço</td>
            <td>Detalhes do item</td>
            <td>Qtde</td>
            <td>Valor</td>
            <td>Subtotal</td>
        </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < count($produtos); $i++)
        <tr>
            <td>{{ $produtos[$i] }}</td>
            <td>{{ $descricoes[$i] }}</td>
            <td>{{ $quantidades[$i] }}</td>
            <td>R$ {{ $valoresUnitarios[$i] }}</td>
            <td>R$ {{ $valoresTotais[$i] }}</td>
        </tr>
        @endfor
        </tbody>
    </table>
    <div class='services-title'>Total</div>
    <table class='total-table'>
        <tr>
            <td class='total-label'>Total da Ordem de Serviço:</td>
            <td>R$ {{$dadosVenda->totalizador_itens}}</td>
        </tr>
        <tr>
            <td class='total-label'>Frete:</td>
            <td>R$ {{$dadosVenda->totalizador_valor_adicional}}</td>
        </tr>
        <tr>
            <td class='total-label'>Desconto:</td>
            <td>R$ {{$dadosVenda->totalizador_desconto}}</td>
        </tr>
        <tr>
            <td class='total-label'>Total:</td>
            <td>R$ {{$dadosVenda->totalizador_valor}}</td>
        </tr>
        <tr>
            <td class='total-label'>Condicao:</td>
            <td>{{$dadosVenda->condicao_pagamento}}</td>
        </tr>
    </table>
    <div class='services-title'>Condição pagamento</div>
    <table class='total-table'>
        @for($i = 0; count($dataVencimento) > $i; $i++)
            <tr>
                <td class='total-label'>Data de Vencimento:</td>
                <td>{{ \App\Helpers\FormatUtils::formatDate($dataVencimento[$i])}}</td>
                <td class='total-label'>Valor:</td>
                <td>{{ $valorReceber[$i] }}</td>
            </tr>
        @endfor
    </table>
</div>
</body>
</html>
