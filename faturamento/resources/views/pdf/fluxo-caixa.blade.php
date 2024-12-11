<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fluxo Caixa</title>
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
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Entradas</th>
                    <th>Saídas</th>
                    <th>Saldo Final</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data->original as $key => $item)
                <tr>
                    <td>{{ \App\Helpers\FormatUtils::formatDate($key) }}</td>
                    <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($item['entradas']) }}</td>
                    <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($item['saidas']) }}</td>
                    <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($item['total']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </body>
</html>
