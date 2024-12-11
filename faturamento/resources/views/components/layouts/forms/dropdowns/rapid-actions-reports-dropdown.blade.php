<div class="btn-group">
    <div class="btn-group">
        <button id='relatorios' type="button" class="dropdownRelatorioButton btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i @class(['bi', 'bi-bi-clipboard2-data'])></i>Gerar Relatorio: {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item relatorio" data-value="hoje" wire:click.prevent="report('pdf')" href="#">Pdf {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}</a>
            <a class="dropdown-item relatorio" data-value="semana" wire:click.prevent="report('excel')" href="#">Excel {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}</a>
            <a class="dropdown-item relatorio" data-value="mes" wire:click.prevent="report('graphs')" href="#">Graficos {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}</a>
        </div>
    </div>
</div>
