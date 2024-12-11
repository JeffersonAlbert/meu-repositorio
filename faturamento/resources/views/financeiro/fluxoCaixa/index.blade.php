@extends('layout.newLayout')
@push('scripts')
    @include('financeiro.fluxoCaixa.js.gerar-relatorio')
@endpush
@section('content')
    <div @class(['row'])>
        <div @class(['col-2'])>
            <div class="form-group">
                <input type="text" id="datepicker-month-year" class="form-control" placeholder="Selecione o mês e ano">
            </div>
        </div>
        <div class="col-2">
            <button id="gerarRelatorio" class="btn btn-success" type="submit">Gerar Relatório</button>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col-1'])>
            <a id="pdfFluxoCaixa" href="{{ route('pdf-fluxo-caixa', ['date' => date('Y-m')]) }}" @class(['btn-sm', 'btn', 'btn-success'])>Exportar pdf</a>
        </div>
        <div @class(['col-1'])>
            <a id="excelFluxoCaixa" href="{{ route('excel-fluxo-caixa', ['date' => date('Y-m')]) }}" @class(['btn-sm', 'btn', 'btn-success'])>Exportar excel</a>
        </div>
    </div>
    <div @class(['row', 'mt-2'])>
        <div @class(['col'])>
            <div @class(['card w-100'])>
                <div @class(['card-body', 'card-body-grid-color'])>
                    <div @class(['table-overflow'])>
                        <table @class([
                            'table',
                            'table-responsive-sm',
                            'table-head-number',
                            'table-bordered',
                            'table-overflow',
                        ])>
                            <thead @class(['head-grid-data'])>
                                <tr style="background-color: #e9e9e9">
                                    <th>Data</th>
                                    <th>Entradas</th>
                                    <th>Saídas</th>
                                    <th>Saldo Final</th>
                                </tr>
                            </thead>
                            <tbody @class(['rel-tb-claro', 'tabela-fluxo-diario']) >
                                @foreach($fluxoCaixa as $key => $item)
                                    <tr>
                                        <td>{{ \App\Helpers\FormatUtils::formatDate($key) }}</td>
                                        <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($item['entradas']) }}</td>
                                        <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($item['saidas']) }}</td>
                                        <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($item['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
