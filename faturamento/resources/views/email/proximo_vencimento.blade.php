<!DOCTYPE html>
<html>
<head>
    <title>Processos parados</title>
</head>
<body>
    <h1>Processos parados</h1>
    <p>Bom dia! segue tabela dos processos proximo ao vencimento</p>

    <table border="1">
        <thead>
            <tr>
                <th>Codigo de Identificação</th>
                <th>Vencimento</th>
                <th>Ultima Alteração</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['trace_code'] }}</td>
                    <td>{{ App\Helpers\FormatUtils::formatDate($item['vencimento']) }}</td>
                    <td>{{ $item['updated_at'] }}</td>
                    <td><a href="{{ route('processo.aprovacao', ["id" => $item['id'], "vencimento" => $item['vencimento']]) }}">Vizualizar</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
