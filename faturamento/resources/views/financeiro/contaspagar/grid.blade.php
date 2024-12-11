@extends('financeiro.contaspagar.js')
@extends('layout.newLayout')

@section('content')
@include('financeiro.contaspagar.cardsContas')
@include('financeiro.contaspagar.table')
<!-- <div class="row">
    <div class="col-xl-12 col-lg-7">
        {{-- card header--}}
        <div class="card text-center">
            <div class="card shadow mb-4">
              <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Controle Financeiro</h6>
                <div class="dropdown no-arrow">
                    <button id="exibeFormBuscaGrid" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="{{ route('processo.create') }}">Cadastrar nova conta</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Outro algo aqui</a>
                    </div>
                </div>
              </div>
              {{-- card body --}}
              <div class="card-body" style="overflow-y: scroll;">
              {{-- @include('financeiro.contaspagar.formBuscaGrid') --}}
              @include('financeiro.contaspagar.abas')
                <table class="table table-responsive-xl">
                    <thead>
                        <th>Fornecedor</th>
                        <th>N° Nota</th>
                        <th>Valor Vencimento</th>
                        <th>Valor total</th>
                        <th>Vencimento</th>
                        <th>Filial</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </thead>
                    <tbody id="tabela-corpo">
                    @forelse($faturas as $fatura)
                    <tr>
                        <td>{{ $fatura->f_name }}</td>
                        <td>{{ $fatura->num_nota }}</td>
                        <td>R$ {{ App\Helpers\FormatUtils::formatMoney($fatura->vparcela) }}</td>
                        <td>R$ {{ App\Helpers\FormatUtils::formatMoney($fatura->valor) }}</td>
                        <td>{{ date('d/m/Y', strtotime($fatura->pvv_dtv)) }}</td>
                        <td>{{ $fatura->filial_nome == null ? 'N/A' : $fatura->filial_nome }}</td>
                        <td>
                            <div data-toogle="tooltip" title="{{ ($fatura->aprovado == true ? ($fatura->pago ==true ? "Aprovado por nomes" : "Aprovado por nomes falta pagar") : "Aguardando aprovação grupos" )}}">
                                <i fill="currentColor" class="{{ ($fatura->aprovado == true ?  ($fatura->pago == true ? 'bi bi-check-circle-fill text-success': 'bi bi-exclamation-circle-fill text-primary') : 'bi bi-exclamation-circle-fill text-warning') }}"></i>
                                <span>{{ ($fatura->aprovado == true ? ($fatura->pago == true ? "Pago" : "Aprovado aguardando pagamento") : "Aguardando aprovacao") }}</span>
                            </div>
                        </td>
                        <td>Fazer algo</td>
                    </tr>
                    @empty
                        <div>
                            <span>Nada aqui</span>
                        </div>
                    @endforelse
                    </tbody>
                </table>
              </div>
              {{-- card footer --}}
              <div class="card-footer text-muted">
                <div class="row" >
                    <div class="pagination">
                        {{ $faturas->links() }}
                    </div>
                </div>
                <div class="row">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                </div>
              </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
