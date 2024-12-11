<div id="modalCadProduto" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="cadProdutos" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cadProdutos">Cadastro de produto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formAddProduto" method="POST" action="{{ route('produto.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="produto-messages"></div>
            <div class="form-group">
              <label for="produto-name" class="col-form-label required">Nome:</label>
              <input name="nome" type="text" class="input-login form-control" id="produto-name">
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="m-3 form-check">
                        <input class="form-check-input" type="radio" name="produto" id="radio_produto" value="produto">
                        <label class="form-check-label" for="radio_produto">
                        Produto
                        </label>
                    </div>
                    <div class="m-3 form-check">
                        <input class="form-check-input" type="radio" name="produto" id="radio_servico" value="servico">
                        <label class="form-check-label" for="radio_servico">
                        Serviço
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="col-form-label required">Ean:</label>
                    <input name="ean" type="text" class="input-login form-control" id="produto-ean">
                </div>
                <div class="form-group col-md-4">
                    <label for="codigo_produto" class="col-form-label required">Codigo:</label>
                    <input name="codigo_produto" type="text" class="input-login form-control" id="codigo_produto">
                </div>
            </div>
            </form>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Voltar</button>
          <button type="button" class="btn btn-success btn-back-number" id="enviarProdutos">Salvar</button>
        </div>
      </div>
    </div>
  </div>
