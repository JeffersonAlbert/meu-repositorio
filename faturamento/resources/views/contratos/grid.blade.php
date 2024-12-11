@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card text-center">
            {{-- card header --}}
            <div class="card-header color-head-tab py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Controle de contratos</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-primary"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-ight shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header color-00b269">Menu</div>
                        <a class="dropdown-item bg-black color-00b269" href="{{ route('contrato.create') }}">Novo contrato</a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>
            {{-- card header --}}
            {{-- card body --}}
            <div class="card-body color-background-f1f4f3 text-primary" style="overflow-y: scroll">
                <table class="table table-responsive-xl text-primary">
                    <thead class="text-primary">
                            <th>#</th>
                            <th>Contrato nome</th>
                            <th>Cliente/Fornecedor</th>
                            <th>Valor contrato</th>
                            <th>Vigencia inicial</th>
                            <th>Vigencia Final</th>
                            <th>Periodo</th>
                            <th>Ações</th>
                    </thead>
                    <tbody>
                        @forelse($contratos as $contrato)
                            <tr>
                                <td>{{ $contrato->id }}</td>
                                <td>{{ $contrato->nome }}</td>
                                <td>{{ $contrato->cliente_nome }}</td>
                                <td>{{ App\Helpers\FormatUtils::formatMoney($contrato->valor) }}</td>
                                <td>{{ date('d/m/Y', strtotime($contrato->vigencia_ini)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($contrato->vigencia_fim)) }}</td>
                                <td>{{ $contrato->periodo }}</td>
                                <td>
                                    <a class="btn btn-success btn-back-number btn-sm" href="{{ route('contrato.edit', ['contrato' => $contrato->id]) }}">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="backgound-rgba-0000-10dastro de fornecedr" >Nada aqui ainda</tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- card body --}}
            {{-- card footer --}}
            <div class="card-footer color-background-footer">
                <div class="row">
                    <div class="pagination">
                        {{ $contratos->links() }}
                    </div>
                </div>
                <div class="row color-background-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-success btn-back-number">Voltar</a>
                </div>
            </div>
            {{-- card footer --}}
        </div>
    </div>
</div>
@endsection
