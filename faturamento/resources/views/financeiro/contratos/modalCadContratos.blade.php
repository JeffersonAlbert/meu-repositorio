
<!-- Adicione o jQuery -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div id="modalCadContrato" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title modal-tx-color" id="exampleModalLabel">Cadastro de contrato</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formAddContrato" method="POST" action="{{ route('contrato.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="contrato-messages"></div>
          <div class="form-group">
            <label for="contrato-name" class="col-form-label modal-tx-color">Contrato:</label>
            <input name="nome" type="text" class="input-login form-control" id="contrato-name">
          </div>
          <div class="form-group">
            <label for="search_cliente" class="col-form-label required modal-tx-color">Cliente:</label>
            <input type="text" name="cliente_contrato" class="input-login form-control" id="search_cliente">
          </div>
          <div class="form-row">
              <div class="form-group col-md-3">
                <label for="vigencia_inicial" class="required col-form-label modal-tx-color">Data inicial da vigencia:</label>
                <input class="input-login form-control" type="date" name="vigencia_inicial" id="vigencia_inicial">
              </div>
              <div class="form-group col-md-3">
                <label for="vigencia_final" class="required col-form-label modal-tx-color">Data final da vigencia:</label>
                <input class="input-login form-control" type="date" name="vigencia_final" id="vigencia_final">
              </div>



              <!--<div class="form-group col-md-3">
                <label for="upload_contrato" class="col-form-label required modal-tx-color">Arquivo:</label>
                <input class="input-login form-control input-file" type="file" name="files[]" id="upload_contrato" multiple="">
              </div>-->



              <div class="form-group col-md-3">
                <label for="valor_contrato" class="col-form-label required modal-tx-color">Valor contrato:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text modal-tx-color">R$</span>
                    </div>
                    <input type="text" class="input-login form-control" name='valor' id="valor_contrato">
                </div>
              </div>

              <form>

              <!--<div class="row">-->
                <div class="form-group col-md-8">

    <label class="label-number" for="upload2">Arquivos</label>
    <div class="file-input-number justify-content-center">
        <i class="bi bi-custom-file-login mr-3"></i>
        <label class="text-file-input-number" for="upload2">Escolher arquivo</label>
        <ul id="file-list"></ul>
        <input id="upload2" name="files[]" type="file" multiple="">
    </div>
</div>

              </div>

          </div>



      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Voltar</button>
        <button type="button" class="btn btn-success btn-back-number" id="enviarContrato">Salvar</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $('#upload2').on('change', function () {
            var files = $(this)[0].files;
            var fileList = $('#file-list');
            fileList.empty(); // Limpa a lista de arquivos antes de adicionar novos

            // Itera sobre os arquivos selecionados e adiciona à lista
            for (var i = 0; i < files.length; i++) {
                fileList.append('<li>' + files[i].name + '</li>');
            }
        });
    });
</script>
