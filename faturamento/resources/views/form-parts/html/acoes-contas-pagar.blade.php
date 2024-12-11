<div class="btn-group">
    <div class="btn-group w-100">
        <button id='button-pai' type="button" @class(["dropdownPeriodoButton", "btn", "btn-success", "dropdown-toggle"]) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Ações
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item acoes" data-value="editar" wire:click.prevent="edit('{{ $id }}', '{{ $pvv_dtv }}')" href="#">Editar</a>
            <a class="dropdown-item periodo" data-value="semana" wire:click.prevent="delete('{{ $id }}', '{{ $pvv_dtv }}')" href="#">Deletar</a>
            <a class="dropdown-item periodo" data-value="mes" wire:click.prevent="pay('{{ $id }}', '{{ $pvv_dtv }}')" href="#">Pagar</a>
            <a class="dropdown-item periodo" data-value="ano" wire:click.prevent="clone('{{ $id }}', '{{ $pvv_dtv }}')" href="#">Clonar</a>
        </div>
    </div>
</div>
