@extends('layout.newLayout')

@push('scripts')
    @include('reports.js.gerar-relatorio-contas-pagar')
    @include('reports.js.gerar-pdf-contas-pagar')
@endpush
@section('content')
    <div @class(['row'])>
        <div @class(['col-12'])>
            <div @class(['row'])>
                <span @class(['titulo-grid-number font-regular-wt'])>Contas pagar</span>
            </div>
            <div @class(['row'])>
                <div @class(['col-2'])>
                    <label for="button-pai" @class(['label-number'])>Vencimento:</label>
                    @include('form-parts.html.acoes-rapidas-periodo')
                </div>
                <div @class(['col-2'])>
                    <label for="button-pai" @class(['label-number'])> &nbsp; </label>
                    <a id="gerarRelatorio" href="#" class="btn btn-success">Gerar relatorio</a>
                </div>
                <div @class(['col-2'])>
                    <label for="button-pai" @class(['label-number'])> &nbsp; </label>
                    <a id="baixarPdf" href="{{ route('pdf-contas-pagar') }}" class="btn btn-success">Gerar Pdf</a>
                </div>
                <div @class(['col-2'])>
                    <label for="button-pai" @class(['label-number'])> &nbsp; </label>
                    <button id="baixarExcel" href="{{ route('excel-contas-pagar') }}" class="btn btn-success">Gerar Excel</button>
                </div>
                <div @class(['col-2'])>
                    <label for="button-pai" @class(['label-number'])> &nbsp; </label>
                    <button id="exibeFormBuscaGrid" class="btn search-btn mb-3">
                        <i class="bi bi-search"></i>
                        Pesquisar
                    </button>
                </div>
            </div>
            <div @class(['row', 'mt-3', 'mb-3'])>
                <div @class(['col-12'])>
                    @include('form-parts.html.formulario-busca-padrao-pagar')
                </div>
            </div>
            <div @class(['row', 'mt-3'])>
                <div @class(['col-12'])>
                    <div @class(['card', 'w-100'])>
                        <div @class(['card-body', 'card-body-grid-color'])>
                            <div @class(['table-overflow'])>
                                <table @class(['table', 'table-responsive-sm',
                                        'table-head-number',
                                        'table-bordered',
                                        'table-overflow'])>
                                    <thead @class(['head-grid-data'])>
                                        <tr>
                                            <th>Identificação</th>
                                            <th>Fornecedor</th>
                                            <th>Filial</th>
                                            <th>Categoria</th>
                                            <th>Contrato</th>
                                            <th>Vencimento</th>
                                            <th>Valor</th>
                                            <th>Produto</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody  class="rel-tb-claro tabela-relatorio">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
