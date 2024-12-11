<table class="table table-responsive-sm table-head-number table-bordered">
    <thead class="head-grid-data" style="background-color: #e9e9e9">
        <th scope="col">
            <div class="form-check align-middle">
                <input class="form-check-input" type="checkbox" id="checkboxUsuario">
                <label class="form-check-label" for="checkboxUsuario">Usuário</label>
            </div>
        </th>
        <th scope="col">Fornecedor</th>
        <th scope="col">Ultima edição</th>
        <th scope="col">Valor</th>
        <th scope="col">Vencimento</th>
        <th scope="col">Ações</th>
    </thead>
    <tbody id='tabela-processos' class="rel-tb-claro">
        @forelse($processos as $processo)
        <tr>
        <td class="text-center td-grid-font align-middle">
            @if($processo->status_aprovacao === false)
            <div  style="color: red">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            @endif
            <b>{{ $processo->u_name }} {{ $processo->last_name }}</b>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p class="background-trace-code align-middle"><b>Cod identificação: {{ $processo->trace_code }}</b></p>
            <p>{{ $processo->f_name == 0 ? $processo->sub_categoria_dre : $processo->f_name }}</p>
            <p>Nº Nota: {{ $processo->num_nota }}</p>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p><b>{{ \App\Helpers\FormatUtils::now()->parse($processo->updated_at)->isoFormat('D MMM YYYY') }}</b></p>
            <p>{{ \App\Helpers\FormatUtils::now()->parse($processo->updated_at)->format('H:i:s') }}</p>
            <p>Usuário: {{$processo->u_last_modification}}</p>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p><b>R$ {{ App\Helpers\FormatUtils::formatMoney($processo->vparcela) }}/Mês</b></p>
            <p>R$ {{ App\Helpers\FormatUtils::formatMoney($processo->valor) }}/Processo</p>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p><b>{{ App\Helpers\FormatUtils::now()->parse($processo->pvv_dtv)->isoFormat('D MMM YYYY') }}</b></p>
        </td>
        <td>
            <button onclick="window.location.href='{{ route('processo.editar-processo', ['processo' => $processo->id, 'data' => $processo->pvv_dtv]) }}'" class="btn btn-sm btn-success btn-success-number">
                <i class="bi bi-pen"></i>
                Editar
            </button><br>
            <button onclick="window.location.href='{{ route('processo.protocolo', ['processo' => $processo->id]) }}';" class="btn btn-sm btn-success btn-success-number mt-1">
                <i class="bi bi-download"></i>
                Download
            </button><br>
            <button onclick="window.location.href='{{ route('processo.aprovacao', ['id' => $processo->id, 'vencimento' => $processo->pvv_dtv])}}';"  class="btn btn-sm btn-success btn-success-number mt-1" data-toggle="modal" data-target=".bd-example-modal-xl">
                <i class="bi bi-eye"></i>
                Vizualizar
            </button>
        </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>
<div class="row ml-1">
    <div class="pagination">
        {{ $processos->links() }}
    </div>
</div>
{{-- @include('processo.modal.showAprovacoes') --}}
