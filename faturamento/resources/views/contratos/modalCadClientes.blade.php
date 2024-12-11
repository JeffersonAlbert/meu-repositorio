{{-- cadastrar cliente --}}
<div class="modal fade" id="modal-cad-cliente" tabindex="-1" role="dialog" aria-labelledby="modalInserirCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content color-background-f1f4f3">
            <div class="modal-header modal-header-background">
               <h6 class="m-0 font-weight-bold text-primary font-size-18px">Cadastrar novo cliente</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form class="form-group color-background-f1f4f3" action="{{ route('clientes.store') }}" method="POST">
            <div class="modal-body modal-body-background">
                <div class="messages-modal-cad-clientes"></div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="documento_cliente" class="required">CNPJ/CPF</label>
                        <input id="documento_cliente" class="input-login form-control" name="cpf_cnpj">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="nome_cliente" class="required">Nome do cliente</label>
                        <input id="nome_cliente" class="input-login form-control" name="nome">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="tel_cliente">Telefone</label>
                        <input id="tel_cliente" class="input-login form-control" name="telefone">
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-background">
                <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-back-number" id="inserirCliente">Confirmar</button>
            </div>
            </form>
        </div>
    </div>
</div>
