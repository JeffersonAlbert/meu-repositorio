<div class="btn-group">
    <div class="btn-group w-100">
        <button id='button-pai' type="button" @class(["dropdownPeriodoButton", "btn", "btn-success", "dropdown-toggle"]) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Ações
        </button>
        <div class="dropdown-menu">
            <a id="edit{{ $id }}" class="dropdown-item"  wire:click.prevent="edit('{{ $id }}')" href="#">Editar conta</a>
            <a id="editInstallments{{ $id }}" data-toggle="modal" data-target="#modalEditInstallment"
               @class(['dropdown-item']) href="#">Editar parcelas</a>
            <a class="dropdown-item"  wire:click.prevent="deleteWarning(
            '{{ $id }}',
            '{{ $pvv_id }}',
            '{{ $pago }}',
            '{{ $valor }}',
            '{{ $vparcela }}',
            '{{ is_null($parcelas) ? 0 : $parcelas->count() }}',
            )" href="#">
                Deletar
            </a>
            <a @class(['dropdown-item'])
                href='{{ route('approvals.index',[ 'processId' => $id, 'pvvDtv' => $pvv_dtv])}}'>
                Verificar aprovações
            </a>
            @if(auth()->user()->financeiro and $pago == 1)
            <a @class(['dropdown-item']) wire:click.prevent="askSetToOpen('{{ $id }}','{{ $pvv_id }}')"
               href="#">Definir como em Aberto
            </a>
            @endif
        </div>

    </div>
</div>
