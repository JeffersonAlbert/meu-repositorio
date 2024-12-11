<!-- modal para adicionar tipos de cobrancas -->
<div class="modal fade" id="cadTipoCobranca" tabindex="-1" role="dialog" aria-labelledby="cadTipoCobrancaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cadTipoCobrancaModalLabel">Adicionar tipo cobrança</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger error-tipo-cobranca" style="display: none">
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <input class="input-login form-control" name="nomeCobranca" id="nomeCobranca" placeholder="Nome do tipo de cobrança">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Fechar</button>
        <button id="addTipoCobranca" type="button" class="btn btn-success btn-back-number">Enviar</button>
      </div>
    </div>
  </div>
</div>
