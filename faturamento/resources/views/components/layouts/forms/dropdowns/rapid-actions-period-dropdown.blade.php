<div class="btn-group">
    <div class="btn-group w-100">
        <button id='button-pai' type="button" @class(["dropdownPeriodoButton", "btn", "btn-sm", "btn-success", "dropdown-toggle"]) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Periodo por vencimento: {{ $queryPeriod }}
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item periodo" data-value="hoje" wire:click.prevent="setPeriod('today')" href="#">Hoje</a>
            <a class="dropdown-item periodo" data-value="semana" wire:click.prevent="setPeriod('thisWeek')" href="#">Esta semana</a>
            <a class="dropdown-item periodo" data-value="mes" wire:click.prevent="setPeriod('thisMonth')" href="#">Este mês</a>
            <a class="dropdown-item periodo" data-value="ano" wire:click.prevent="setPeriod('thisYear')" href="#">Este ano</a>
            <a class="dropdown-item periodo" data-value="ultimos_12_meses" wire:click.prevent="setPeriod('lastTwelveMonts')" href="#">Últimos 12 meses</a>
            <a class="dropdown-item periodo" data-value="all" wire:click.prevent="setPeriod('allPeriod')" href="#">Todo o período</a>
        </div>
    </div>
</div>
