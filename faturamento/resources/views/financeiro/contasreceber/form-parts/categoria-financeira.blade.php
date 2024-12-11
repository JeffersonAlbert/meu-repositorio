<label for="dropdownMenuButton" class="label-number required">Categoria financeira</label>
@if(isset($contasReceber))
<div class="dropdown">
    <button class="btn dropdown-toggle col-12 btn-transparent" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ $contasReceber->sub_desc}}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <input type="text" id="dropdownInput" class="form-control" placeholder="Digite sua opção">
        <div class="dropdown-divider"></div>
        <div id="dropdownItems">
        @foreach($subDres as $subDre)
        <a href="#" data-id="{{ $subDre->sub_id}}" class="dropdown-item">{{ $subDre->sub_desc }}</a>
        @endforeach
        </div>
        <!-- Adicione mais opções conforme necessário -->
    </div>
</div>
<input id='categoriaFinanceiraVal' name='sub_categoria_dre' value='{{ $contasReceber->sub_id }}' type='hidden'>
@else
<div class="dropdown">
    <button class="btn dropdown-toggle col-12 btn-transparent" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Selecione uma opção
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <input type="text" id="dropdownInput" class="form-control" placeholder="Digite sua opção">
        <div class="dropdown-divider"></div>
        <div id="dropdownItems">
        @foreach($subDres as $subDre)
        <a href="#" data-id="{{ $subDre->sub_id}}" class="dropdown-item">{{ $subDre->sub_desc }}</a>
        @endforeach
        </div>
        <!-- Adicione mais opções conforme necessário -->
    </div>
</div>
<input id='categoriaFinanceiraVal' name='sub_categoria_dre' value='' type='hidden'>
@endif
