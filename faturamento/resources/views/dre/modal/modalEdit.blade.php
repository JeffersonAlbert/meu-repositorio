{{-- adicionar DRE --}}
<div class="modal fade" id="editDespesa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Categoria Receita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form id="formEditDRE">
                            @csrf
                            @method('PUT')
                            <input id="tipo" type="hidden" name="tipo" value="">
                            <input id="receitaDespesaId" type="hidden" name="id" value="">
                            <div class="row">
                                <div class="col-6">
                                    <label class="label-number" for='nome'>Descrição</label>
                                    <input id="descricaoDespesa" name='nome' type="text" class="input-login form-control" placeholder="{{ isset($dre->nome) ? $dre->nome : null }}">
                                </div>
                                <div class="col-6">
                                    <label class="label-number" for='dre-pai'>Aparecer em:</label>
                                    <select id="dre-pai-edit" name='dre-pai' class="input-login select-number form-control form-select">
                                        <option value="receita">Receita</option>
                                        @foreach($receita as $categoriaReceita)
                                        <option value="{{ $categoriaReceita->id }}">{{ $categoriaReceita->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-6">
                                    <label class="label-number" for='vinculo-dre'>Vincular a DRE</label>
                                    <select id="vinculo-dre-edit" name="vinculo-dre" class="input-login select-number form-control form-select">
                                    @foreach($vinculoDreReceita as $vinculo)
                                    <option value='{{ $vinculo->id }}'>{{ $vinculo->descricao }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="enviarEdicao" type="button" class="btn btn-success btn-back-number btn-submit">Confirmar</button>
            </div>
        </div>
    </div>
</div>

