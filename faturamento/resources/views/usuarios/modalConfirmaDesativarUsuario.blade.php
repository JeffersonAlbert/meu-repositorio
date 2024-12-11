<div class="modal fade" id="confirmModalDisable" tabindex="-1" aria-labelledby="confirmModalLabelDisable" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabelDisable">Confirmação</h5>
                <button type="button" class="btn-close btn-fechar close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja desabilitar este usuário?
                <input type="hidden" value="" id="userId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-fechar" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDesabilitar">Desabilitar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModalEnable" tabindex="-1" aria-labelledby="confirmModalLabelEnable" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabelEnable">Confirmação</h5>
                <button type="button" class="btn-close btn-fechar close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja habilitar este usuário?
                <input type="hidden" value="" id="userId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-fechar" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-success-number" id="confirmHabilitar">Habilitar</button>
            </div>
        </div>
    </div>
</div>
