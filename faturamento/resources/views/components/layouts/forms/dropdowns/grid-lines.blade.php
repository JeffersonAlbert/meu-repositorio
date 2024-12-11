<div @class(['col'])>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Linhas a exibir: {{ auth()->user()->linhas_grid }}
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item grid-lines" data-value="10" wire:click.prevent="setRows('10')" href="#">10</a>
            <a class="dropdown-item grid-lines" data-value="25" wire:click.prevent="setRows('25')" href="#">25</a>
            <a class="dropdown-item grid-lines" data-value="50" wire:click.prevent="setRows('50')" href="#">50</a>
            <a class="dropdown-item grid-lines" data-value="100" wire:click.prevent="setRows('100')" href="#">100</a>
        </div>
    </div>
</div>
