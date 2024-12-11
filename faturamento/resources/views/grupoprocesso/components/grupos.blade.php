<label for="dropdownMenuButton" class="label-number required">Grupo do Workflow</label>
@if (isset($grupos))
    <div class="dropdown">
        <button class="btn dropdown-toggle col-12 btn-transparent" type="button" id="dropdownMenuButton"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ $processo->sub_desc }}
        </button>
        <div class="dropdown-menu" style="max-height: 200px; overflow-y: auto;" aria-labelledby="dropdownMenuButton">
            <input type="text" id="dropdownInput" class="form-control" placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
            <div id="dropdownItems">
                @foreach ($grupos as $grupo)
                    <a href="#" data-id="{{ $grupo->id }}" class="dropdown-item">{{ $grupo->nome }}</a>
                @endforeach
            </div>
            <!-- Adicione mais opções conforme necessário -->
        </div>
    </div>
    <input id='categoriaFinanceiraVal' name='categoria_financeira' value='{{ $grupo->id }}' type='hidden'>
@else
    <div class="dropdown">
        <button class="btn dropdown-toggle col-12 btn-transparent" type="button" id="dropdownMenuButton"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Selecione
        </button>
        <div class="dropdown-menu" style="max-height: 200px; overflow-y: auto;" aria-labelledby="dropdownMenuButton">
            <input type="text" id="dropdownInput" class="form-control" placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
            <div id="dropdownItems">
                @foreach ($subDre as $dre)
                    <a href="#" data-id="{{ $dre->sub_id }}" class="dropdown-item">{{ $dre->sub_desc }}</a>
                @endforeach
            </div>
            <!-- Adicione mais opções conforme necessário -->
        </div>
    </div>
    <input id='categoriaFinanceiraVal' name='categoria_financeira' value='' type='hidden'>
@endif
