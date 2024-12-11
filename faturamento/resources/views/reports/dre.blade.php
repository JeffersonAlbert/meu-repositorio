@extends('layout.newLayout')

@push('scripts')
    @include('reports.js.periodo')
@endpush

@section('content')
    <div class="col-12">
        <div class="row">
            <span class="titulo-grid-number font-regular-wt">Demonstrativo de Resultado do Exercício</span>
        </div>
        <div class="row">
            <div class="col-3">
                <select id="periodo" class="input-login form-control">
                    <option value="">Selecione o periodo</option>
                    <option value="monthly">Mensal</option>
                    <option value="bimonthly">Bimestral</option>
                    <option value="quarterly">Trimestral</option>
                    <option value="semester">Semestral</option>
                    <option value="yearly">Anual</option>
                </select>
            </div>
            <div id="container" class="col-1">
                <input id="startYear" name="startYear" class="input-login form-control" placeholder="{{ date('Y') }}">
            </div>
            <div id="container2" class="col-1" style="display: none;">
                <input id="endYear" name="endYear" class="input-login form-control" placeholder="{{ date('Y') }}">
            </div>
            <div class="col-2">
                <button id="gerarRelatorio" class="btn btn-success" type="submit">Gerar Relatório</button>
            </div>
        </div>
        <div @class(['row', 'mt-3'])>
            <div @class(['col-1'])>
                <a href="{{ route('pdf-dre', ['periodo' => $periodo, 'startYear' => $startYear, 'endYear' => $endYear]) }}" @class(['btn-sm', 'btn', 'btn-success'])>Exportar pdf</a>
            </div>
            <div @class(['col-1'])>
                <a href="{{ route('excel-dre', ['periodo' => $periodo, 'startYear' => $startYear, 'endYear' => $endYear]) }}" @class(['btn-sm', 'btn', 'btn-success'])>Exportar excel</a>
            </div>
        </div>
        <div id="exportar" class="row mt-3">
            <div class="col-12">
                <div @class(['card'])>
                    <div @class(['card-body', 'w-100', 'card-body-grid-color'])>
                        <div @class(['table-overflow'])>
                            <table @class([
                                'table',
                                'table-striped',
                                'table-responsive-sm',
                                'table-head-number',
                                'table-bordered',
                                'table-overflow',
                            ])>
                                <thead @class(['head-grid-data'])>
                                @if(isset($periodo) and $periodo == 'monthly')
                                    <tr style="background-color: #e9e9e9">
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
                                    <tr style="background-color: #e9e9e9">
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
                                    <tr style="background-color: #e9e9e9">
                                        <th>Categoria</th>
                                        <th>1º Trimestre</th>
                                        <th>2º Trimestre</th>
                                        <th>3º Trimestre</th>
                                        <th>4º Trimestre</th>
                                        <th>Total</th>
                                    </tr>
                                @elseif(isset($periodo) and $periodo == 'semester')
                                    <tr style="background-color: #e9e9e9">
                                        <th>Categoria</th>
                                        <th>1º Semestre</th>
                                        <th>2º Semestre</th>
                                        <th>Total</th>
                                    </tr>
                                @elseif(isset($periodo) and $periodo == 'yearly')
                                    <tr style="background-color: #e9e9e9">
                                        <th>Categoria</th>
                                        @foreach(range($startYear, $endYear) as $year)
                                            <th>{{ $year }}</th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                @else
                                    <tr style="background-color: #e9e9e9">
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
                                <tbody @class(['rel-tb-claro'])>
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
                                            <span style="{{ $categoryClass }}">{{ $category }}</span>
                                        </td>
                                        @foreach ($values as $value)
                                            <td>
                                                <span style="{{ $valueClass }}">{{ number_format($value, 2, ',', '.') }}</span>
                                            </td>
                                        @endforeach
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
@endsection
