<!-- Modal -->
<div class="modal fade" id="uploadImagemPerfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="uploadImgProfile" action="{{ route('usuarios.upload-img') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <input type="file" name="imagem" class="btn btn-success btn-sm" accept="image/*">
                    <button type="submit" id="enviarImg" class="btn btn-success btn-sm">Enviar</button>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
