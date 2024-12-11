<div class="btn-group">
    <button type="button" data-value="anterior" class="btn btn-back-number btn-success"><</button>
    <div class="btn-group">
        <button id='button-pai' type="button" class="btn btn-back-number btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Periodo {{ ucfirst(\App\Helpers\FormatUtils::now()->parse()->isoFormat('MMM YYYY')) }}
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item periodo" data-value="hoje" href="#">Hoje</a>
            <a class="dropdown-item periodo" data-value="semana" href="#">Esta semana</a>
            <a class="dropdown-item periodo" data-value="mes" href="#">Este mês</a>
            <a class="dropdown-item periodo" data-value="ano" href="#">Este ano</a>
            <a class="dropdown-item periodo" data-value="ultimos_12_meses" href="#">Últimos 12 meses</a>
            <a class="dropdown-item periodo" data-value="all" href="#">Todo o período</a>
        </div>
    </div>
    <button type="button" data-value="proximo" class="btn btn-back-number btn-success">></button>
</div>
