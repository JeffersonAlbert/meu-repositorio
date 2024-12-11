<div id='grupoVal' class='tags-container'></div>
<label for="dropdownMenuButton" class="label-number required">Grupo do Workflow</label>
<div class="dropdown">
    <button class="btn dropdown-toggle col-4 btn-transparent" type="button" id="dropdownMenuButton" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        Selecione
    </button>
    <div class="dropdown-menu col-4" style="max-height: 200px; overflow-y: auto;" aria-labelledby="dropdownMenuButton">
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
