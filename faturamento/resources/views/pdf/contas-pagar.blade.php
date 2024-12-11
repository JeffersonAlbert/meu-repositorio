<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Contas pagar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: center;
            border: 1px solid #000;
        }
        .table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<table @class(['table'])>
    <thead>
    <tr>
        <th>Identificação</th>
        <th>Cliente</th>
        <th>Filial</th>
        <th>Categoria</th>
        <th>Contrato</th>
        <th>Vencimento</th>
        <th>Valor</th>
        <th>Produto</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($relatorio as $data)
        <tr>
            <td>{{ $data->trace_code }}</td>
            <td>{{ $data->f_name ?? $data->dre_categoria }}</td>
            <td>{{ $data->filial_nome ?? 'Sem filial' }}</td>
            <td>{{ $data->dre_categoria ?? 'Sem categoria' }}</td>
            <td>{{ $data->contrato ?? 'Sem contrato' }}</td>
            <td>{{ \App\Helpers\FormatUtils::formatDate($data->pvv_dtv) }}</td>
            <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($data->vparcela) }}</td>
            <td>{{ $data->produto ?? 'Sem produto'}}</td>
            <td>{{ $data->pago == true ? "Pago" : "Pendente" }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
