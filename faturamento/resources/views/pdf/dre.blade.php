<!DOCTYPE html>
<html lang='pt-br'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>DRE</title>
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
        @if(isset($periodo) and $periodo == 'monthly')
            <tr>
                <th>Categoria</th>
                <th>Janeiro</th>
                <th>Fevereiro</th>
                <th>Março</th>
                <th>Abril</th>
                <th>Maio</th>
                <th>Junho</th>
                <th>Julho</th>
                <th>Agosto</th>
                <th>Setembro</th>
                <th>Outubro</th>
                <th>Novembro</th>
                <th>Dezembro</th>
                <th>Total</th>
            </tr>
        @elseif(isset($periodo) and $periodo == 'bimonthly')
            <tr>
                <th>Categoria</th>
                <th>Janeiro/Fevereiro</th>
                <th>Março/Abril</th>
                <th>Maio/Junho</th>
                <th>Julho/Agosto</th>
                <th>Setembro/Outubro</th>
                <th>Novembro/Dezembro</th>
                <th>Total</th>
            </tr>
        @elseif(isset($periodo) and $periodo == 'quarterly')
            <tr>
                <th>Categoria</th>
                <th>1º Trimestre</th>
                <th>2º Trimestre</th>
                <th>3º Trimestre</th>
                <th>4º Trimestre</th>
                <th>Total</th>
            </tr>
        @elseif(isset($periodo) and $periodo == 'semester')
            <tr>
                <th>Categoria</th>
                <th>1º Semestre</th>
                <th>2º Semestre</th>
                <th>Total</th>
            </tr>
        @elseif(isset($periodo) and $periodo == 'yearly')
            <tr>
                <th>Categoria</th>
                @foreach(range($startYear, $endYear) as $year)
                    <th>{{ $year }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        @else
            <tr>
                <th>Categoria</th>
                <th>Janeiro</th>
                <th>Fevereiro</th>
                <th>Março</th>
                <th>Abril</th>
                <th>Maio</th>
                <th>Junho</th>
                <th>Julho</th>
                <th>Agosto</th>
                <th>Setembro</th>
                <th>Outubro</th>
                <th>Novembro</th>
                <th>Dezembro</th>
                <th>Total</th>
            </tr>
        @endif
        </thead>
        <tbody>
        @foreach ($data as $category => $values)
            <tr>
                @php
                    $categoryClass = '';
                    $valueClass = '';

                    if (strpos($category, '+') !== false) {
                        $categoryClass = 'color: green';
                        $valueClass = 'color: green';
                    } elseif (strpos($category, '-') !== false) {
                        $categoryClass = 'color: red';
                        $valueClass = 'color: red';
                    } else {
                        $categoryClass = 'font-weight: bold';
                        $valueClass = 'font-weight: bold';
                    }
                @endphp
                <td>
                    <span>{{ $category }}</span>
                </td>
                @foreach ($values as $value)
                    <td>
                        <span>{{ number_format($value, 2, ',', '.') }}</span>
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    </body>
</html>
