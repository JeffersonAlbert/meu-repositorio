{{-- confirmar apagar rateio --}}
<div class="modal fade" id="modalInserirCliente" tabindex="-1" role="dialog" aria-labelledby="modalInserirCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m-0 font-weight-bold text-primary font-size-18px" id="modalInserirCliente">Cadastrar novo cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="{{ route('clientes.store') }}" method="POST">
            <div class="modal-body">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-back-number">Voltar</button>
                <button type="button" class="btn btn-success btn-back-number" id="inserirCliente" disabled>Confirmar</button>
            </div>
            </form>
        </div>
    </div>
</div>
