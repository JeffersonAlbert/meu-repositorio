{{-- modal cadastro de categoria --}}
<div class="modal fade" id="modalInserirCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCategoria" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCategoria">Cadastrar nova categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="messages-modal-cad-categoria"></div>
                <input type="hidden" id="id_categoria_edit" name="id_categoria_edit" value="">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="nome_categoria" class="required">Categoria</label>
                        <input id="nome_categoria" class="desc-background form-control" name="categoria">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descricao_categoria">Descrição</label>
                        <textarea id="descricao_categoria" class="desc-background form-control" name="descricao"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="inserirCategoria" disabled>Confirmar</button>
            </div>
            </form>
        </div>
    </div>
</div>
