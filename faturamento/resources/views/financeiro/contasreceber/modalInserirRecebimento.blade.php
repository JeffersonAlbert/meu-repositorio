{{-- modal de baixa de contas a receber --}}
<div class="modal fade" id="modalInserirRecebimento" tabindex="-1" role="dialog" aria-labelledby="inserirRecebimento" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inserirRecebimento">Inserir recebimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="inserirRecebimento" action="#">
                <div type="hidden" id="message-receber"></div>
                <div class="form-group">
                    <label for="valor-recebido" class="col-form-label required">Valor Pago:</label>
                    <input name="valor-recebido" type="text" class="form-control formatMoney" id="valor-recebido">
                </div>
                <div class="form-group">
                    <label for="data-valor-recebido" class="required">Data:</label>
                    <input name="data-valor-recebido" type="date" class="form-control" id="data-valor-recebido">    
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="receber" type="button" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
</div>