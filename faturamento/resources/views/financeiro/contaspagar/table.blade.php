<div class="row">
    <div class="col-12">
        <div class="row mt-3">
            <div class="col-12">
                <span class="titulo-grid-number font-regular-wt">Lista de contas a pagar</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-5">
                <a id="geral" class="ml-1 btn-novos-number btn btn-sm btn-success-number active aba-financeiro" style="text-decoration: underline" href="#">
                    Geral
                    <span class="badge badge-success badge-counter">{{$total}}</span>
                </a>
                <a id="andamento" class="ml-1 btn-pendentes-number btn btn-sm btn-success-number aba-financeiro">
                    Em andamento
                    <span class="badge badge-warning badge-counter">{{$qtdeAndamento}}</span>
                </a>
                <a id="pendentes" class="ml-1 btn btn-sm btn-danger btn-danger-number aba-financeiro">
                    Pendentes
                    <span class="badge badge-danger badge-counter">{{$qtdePendentes}}</span>
                </a>
                <a id="pagas" class="ml-1 btn btn-sm btn-primary btn-concluidos-number aba-financeiro">
                    Pagos
                    <span class="badge badge-primary badge-counter">{{$qtdePagos}}</span>
                </a>
            </div>
            <div class="col-3">
            @include('processo.acoesRapidas.datas')
            </div>
            <div class="col-2">
                <div class="btn-group">
                    <button type="button" class="btn btn-back-number btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Linhas {{ auth()->user()->linhas_grid }}
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item grid-lines" data-value="10" href="#">10</a>
                      <a class="dropdown-item grid-lines" data-value="25" href="#">25</a>
                      <a class="dropdown-item grid-lines" data-value="50" href="#">50</a>
                      <a class="dropdown-item grid-lines" data-value="100" href="#">100</a>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <button id="exibeFormBuscaGrid" class="btn btn-sm search-btn mr-1">
                    <i class="bi bi-search"></i>
                    Pesquisar
                </button>
                <button onclick="window.location.href='{{ route('processo.create') }}';" class="btn btn-sm btn-success btn-success-number">
                    <i class="bi bi-plus"></i>
                    Adicionar
                </button>
            </div>
        </div>
        <div class="row mt-1">
            @include('financeiro.contaspagar.formBuscaGrid')
        </div>

        <div class="row mt-3">
            <table class="table table-responsive-sm table-head-number table-bordered">
                <thead class="head-grid-data">
                    <th scope="col">
                        Código de identificação
                    </th>
                    <th scope="col">Fornecedor</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Vencimento</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </thead>
                <tbody id="tabela-corpo" class="rel-tb-claro">
                    @forelse($faturas as $fatura)
                    <tr>
                        <td class="td-grid-font align-middle">
                            <p class="background-trace-code align-middle"><b>{{ $fatura->trace_code }}</b></p>
                        </td>
                        <td class="td-grid-font align-middle">
                            <p>{{ $fatura->f_name == null ? $fatura->dre_categoria : $fatura->f_name }}</p>
                        </td>
                        <td class="td-grid-font align-middle">
                            <p class="align-middle">
                                <b>R$ {{ App\Helpers\FormatUtils::formatMoney($fatura->vparcela) }} / Mês</b>
                            </p>
                        </td>
                        <td class="td-grid-font align-middle">
                            <p class="align-middle">
                                <b>{{ App\Helpers\FormatUtils::now()->parse($fatura->pvv_dtv)->isoFormat('D MMM YYYY') }}</b>
                            </p>
                        </td>
                        <td class="td-grid-font align-middle">
                            <div data-toogle="tooltip" title="{{ ($fatura->aprovado == true ? ($fatura->pago ==true ? "Aprovado por nomes" : "Aprovado por nomes falta pagar") : "Aguardando aprovação grupos" )}}">
                                <i fill="currentColor" class="{{ ($fatura->aprovado == true ?  ($fatura->pago == true ? 'bi bi-check-circle-fill text-success': 'bi bi-exclamation-circle-fill text-primary') : 'bi bi-exclamation-circle-fill text-warning') }}"></i>
                                <a href="{{ route('processo.aprovacao', ['id' => $fatura->id, 'vencimento' => isset($fatura->pvv_dtv) ? $fatura->pvv_dtv : date('d-m-Y H:m:s') ]) }}">
                                    <span>{{ ($fatura->aprovado == true ? ($fatura->pago == true ? "Pago" : "Aprovado aguardando pagamento") : "Aguardando aprovacao") }}</span>
                                </a>
                            </div>
                        </td>
                        <td class="td-grid-font align-middle">
                            <a class="btn btn-sm btn-success btn-success-number" href="{{ route('processo.aprovacao', ['id' => $fatura->id, 'vencimento' => isset($fatura->pvv_dtv) ? $fatura->pvv_dtv : date('d-m-Y H:m:s') ]) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-success btn-success-number" type="button" data-toggle="collapse" data-target="#collapse{{ $fatura->trace_code }}" aria-expanded="false" aria-controls="collapse{{ $fatura->trace_code }}">
                                <i class="bi bi-caret-right-fill"></i>
                            </button>
                        </td>
                    </tr>
                    <tr id="collapse{{ $fatura->trace_code }}" class="collapse">
                        <td colspan="12" class="td-grid-font">
                            <div class="row ml-3">Filial: {{ $fatura->filial_nome }} / Contrato: {{ $fatura->contrato }}</div>
                            <div class="row ml-3">Produto/Serviço: {{ $fatura->produto }}</div>
                            <div class="row ml-3">Cento de custo: {{ $fatura->centro_custo }} / Rateio: {{ $fatura->rateio }} </div>
                            <div class="row ml-3">Valor processo: R$ <b>{{ App\Helpers\FormatUtils::formatMoney($fatura->valor) }}</b></div>
                        </td>
                    </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>

            <!-- TESTANDO CARD 13 TRELLO -->

            <div class="row ml-1">
    <div class="pagination">
        {{ $faturas->links('vendor.pagination.custom') }}
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Verifica a página atual
        var currentPage = "{{ $faturas->currentPage() }}";
        var lastPage = "{{ $faturas->lastPage() }}";

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
