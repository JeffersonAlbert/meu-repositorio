<div class="row">
    <div class="col-12">
        <div class="row mt-3">
            <div class="col-12">
                <span class="titulo-grid-number font-regular-wt">Lista de contas a receber</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-5">
                <a id="receber-geral"
                    class="aba-switch active btn btn-sm btn-success btn-success-number btn-novos-number mr-1"
                    href="{{ route('financeiro.pegar-abas') }}">Geral
                    <span class="badge badge-success badge-counter">{{ $geral }}</span>
                </a>
                <a id="receber-pendentes" class="aba-switch btn-danger-number btn btn-sm btn-success-number mr-1"
                    href="{{ route('financeiro.pegar-abas') }}">Pendentes
                    <span class="badge badge-danger badge-counter">{{ $pendente }}</span>
                </a>
                <a id="receber-pagas" class="aba-switch btn btn-sm btn-primary btn-concluidos-number"
                    href="{{ route('financeiro.pegar-abas') }}">Pagas
                    <span class="badge badge-primary badge-counter">{{ $pago }}</span>
                </a>
            </div>
            <div class="col-3">
                @include('acoesRapidas.data')
            </div>
            <div class="col-2">
                <button class="btn btn-sm btn-success btn-success-number" id="enviarSelecionados">
                    Enviar lote
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-back-number btn-success dropdown-toggle btn-sm"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Linhas {{ auth()->user()->linhas_grid }}
                    </button>
                    <div id="rota-grid-lines" data-route="{{ route('usuarios.grid-lines') }}"></div>
                    <div class="dropdown-menu">
                        <a class="dropdown-item grid-lines" data-value="10" href="#">10</a>
                        <a class="dropdown-item grid-lines" data-value="25" href="#">25</a>
                        <a class="dropdown-item grid-lines" data-value="50" href="#">50</a>
                        <a class="dropdown-item grid-lines" data-value="100" href="#">100</a>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <button id="exibeFormBuscaGrid" class="btn-sm search-btn mr-1">
                    <i class="bi bi-search"></i>
                    Pesquisar
                </button>
                <button onclick="window.location.href='{{ route('financeiro.add-receber') }}';"
                    class="btn btn-sm btn-success btn-success-number">
                    <i class="bi bi-plus"></i>
                    Adicionar
                </button>
            </div>
        </div>
        <div class="row mt-1">
            @include('financeiro.contasreceber.formBuscaGrid')
        </div>
        <div class="row mt-3">
            <table class="table table-responsive-sm table-head-number table-bordered">
                <thead class="head-grid-data">
                    <th scope="col">
                        <input type="checkbox" id="allCobrancas">
                    </th>
                    <th scope="col">
                        Código de identificação
                    </th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Vencimento</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </thead>
                <tbody id="table-body" class="rel-tb-claro">
                    @forelse($contasReceber as $contaReceber)
                        <tr>
                            <td class="td-grid-font align-middle">
                                <input class="{{ $contaReceber->status == 'Pago' ? null : 'checkCobranca' }}"
                                    type="checkbox" value="" data-id="{{ $contaReceber->id }}"
                                    {{ $contaReceber->status == 'Pago' ? ' disabled ' : null }}>
                            </td>
                            <td class="td-grid-font align-middle">
                                <p class="background-trace-code align-middle"><b>{{ $contaReceber->trace_code }}</b>
                                </p>
                            </td>
                            <td class="td-grid-font align-middle">
                                <p>{{ $contaReceber->cliente }}</p>
                            </td>
                            <td class="td-grid-font align-middle">
                                <p class="align-middle">
                                    <b>R$ {{ App\Helpers\FormatUtils::formatMoney($contaReceber->valor) }} / Mês</b>
                                </p>
                            </td>
                            <td class="td-grid-font align-middle">
                                <p class="align-middel">
                                    <b>{{ App\Helpers\FormatUtils::now()->parse($contaReceber->vencimento)->isoFormat('D MMM YYYY') }}</b>
                                </p>
                            </td>
                            <td class="td-grid-font align-middle">
                                <p class="align-middel">
                                    {{ $contaReceber->status == null ? 'Em aberto' : $contaReceber->status }}
                                </p>
                            </td>
                            <td class="td-grid-font align-middle">
                                <p class="align-middel">
                                    @if ($contaReceber->status !== null)
                                        <a id="{{ $contaReceber->id }}"
                                            href="{{ route('financeiro.edit', ['financeiro' => $contaReceber->id]) }}"
                                            class="btn btn-sm btn-success btn-success-number baixa_fatura">Vizualizar</a>
                                    @else
                                        <a id="{{ $contaReceber->id }}"
                                            href="{{ route('financeiro.edit', ['financeiro' => $contaReceber->id]) }}"
                                            class="btn btn-sm btn-success btn-success-number baixa_fatura">Receber</a>
                                    @endif
                                    <button class="btn btn-sm btn-success btn-success-number" type="button"
                                        data-toggle="collapse" data-target="#collapse{{ $contaReceber->trace_code }}"
                                        aria-expanded="false" aria-controls="collapse{{ $contaReceber->trace_code }}">
                                        <i class="bi bi-caret-right-fill"></i>
                                    </button>
                                </p>
                            </td>
                        </tr>
                        <tr id="collapse{{ $contaReceber->trace_code }}" class="collapse">
                            <td colspan="12" class='td-grid-font'>
                                <div class='row ml-3'>Contrato: {{ $contaReceber->contrato }}</div>
                                <div class='row ml-3'>Produto / Serviço: {{ $contaReceber->produto }}</div>
                                <div class='row ml-3'>Centro custo / Rateio:
                                    {{ isset($contaReceber->rateio) ? $contaReceber->rateio : $contaReceber->centro_custo }}
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>

           <div class="row ml-1">
   <div class="pagination">
        {{ $contasReceber->links('vendor.pagination.custom') }}
   </div>
</div>


        </div>
    </div>
</div>

<head>
    <style>
    .page-item .page-link {
        transition: background-color 0.3s, color 0.3s;
    }

    .page-item .page-link:hover {
        background-color: #00E087; /* Verde mais claro */
        color: white;
    }

    .page-item.active .page-link {
        background-color: #00B269; /* Verde mais escuro */
        color: white;
    }

    .page-item .page-link {
        background-color: white;
        color: #00B269; /* Cor do texto quando o botão não está ativo */
    }
</style>

</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Verifica a página atual
        var currentPage = "{{ $contasReceber->currentPage() }}";
        var lastPage = "{{ $contasReceber->lastPage() }}";

        // Ajusta a cor do botão Previous se a página atual não for a primeira
        if (currentPage > 1) {
            $('.page-link:contains("Previous")').closest('.page-item').addClass('active');
        }

        // Ajusta a cor do botão Next se a página atual não for a última
        if (currentPage < lastPage) {
            $('.page-link:contains("Next")').closest('.page-item').addClass('active');
        }

        // Adiciona comportamento ao clicar nos botões de paginação
        $('.page-link').on('click', function() {
            // Remove a classe 'active' de todos os botões de paginação
            $('.page-item').removeClass('active');

            // Adiciona a classe 'active' ao botão clicado
            $(this).closest('.page-item').addClass('active');
        });
    });
</script>



