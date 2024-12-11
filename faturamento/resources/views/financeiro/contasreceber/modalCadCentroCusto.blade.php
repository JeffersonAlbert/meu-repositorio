{{-- modal cadastro centro de custo --}}
<div class="modal fade" id="modalInserirCentroCusto" tabindex="-1" role="dialog" aria-labelledby="modalCentroCusto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m-0 font-weight-bold text-primary font-size-18px" id="modalCentroCusto">Cadastrar novo cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="{{ route('centrocusto.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="messages-modal-cad-centrocusto"></div>
                <input type="hidden" id="id_centro_custo_edit" name="id_centro_custo_edit" value="">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="nome_centro_custo" class="required">Centro custo</label>
                        <input id="nome_centro_custo" class="input-login form-control" name="centro_custo">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descricao_centro_custo">Descrição</label>
                        <textarea id="descricao_centro_custo" class="desc-background form-control" name="descricao"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="inserirCentroCusto" disabled>Confirmar</button>
            </div>
            </form>
        </div>
    </div>
</div>
