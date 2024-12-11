<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header modal-header-background">
        <h1 class="modal-title fs-5" id="exampleModalLabel">
            <h6 class="m-0 font-weight-bold text-primary font-size-18px">Cadastro de fornecedor</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Fechar">x</button>
        </h1>

      </div>
      <div class="modal-body modal-body-background">
        <div class="container">
            <div class="alert alert-danger error-form" style="display: none">
            </div>
            <div class="alert alert-success success-form" style="display: none;">
            </div>
            <form method="POST" action="{{ route('fornecedor.store') }}">
            @csrf
                <input hidden name="id_empresa" value="{{ auth()->user()->id_empresa }}">
                <input hidden name="modal_form" value="true">
                <div class="form-row">
                    <div class="form-group col-md-12">
                       <label class="label-number required text-primary_01" for="busca_cliente">CNPJ/CPF</label>
                        <input id="docFornecedor" name="cpf_cnpj" type="text" class="input-login form-control" placeholder="Documento fornecedor" value="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                       <label class="label-number required text-primary" for="busca_nome">Nome do Cliente</label>
                        <input name="nome" type="text" class="input-login form-control" placeholder="Nome fornecedor" value="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                       <label class="label-number" for="busca_nome">Telefone</label>
                        <input name="telefone" type="text" class="input-login form-control" placeholder="Numero telefone" value"">
                    </div>
                </div>
        </div>
      </div>
      <div class="modal-footer modal-footer-background">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Fechar</button>
        <button class="btn btn-success btn-back-number btn-submit">Inserir</button>
            </form>
      </div>
    </div>
  </div>
</div>
<!-- modal upload arquivos -->
<div class="modal fade" id="modalUpload" tabindex="-1" aria-labelledby="modalUploadLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header color-background-white-02">
        <h1 class="modal-title fs-5" id="modalUploadLabel">
            <h6 class="m-0 font-weight-bold text-primary">Formulario de upload de arquivos</h6>
        </h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
        <div class="modal-body color-background-white-02">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                       <div class="upload-erro col-md-12" style="display: none;"></div>
                       <div class="upload-success col-md-12" style="display: none;"></div>
                    </div>
                    <div class="row bg-black">
                        <form id="uploadForm">
                            <label class="label-number" for="upload">Upload de arquivos</label>
                                <input id="upload" class="form-control input-file" name="files[]" type="file" multiple="">
                            </label>
                        </form>
                    </div>
                    <div class="row mt-3 bg-black">
                        <p>Arquivos que compoem o processo:</p><br>
                    </div>
                    <div id="listaArquivos">
                        @if(isset($processo->doc_name))
                        @foreach(json_decode($processo->doc_name, false) as $file)
                        @if($file !== "teste.pdf")
                        <div class="row bg-black">
                            <p>{{ $file }}
                              <!---- Maycon --->
                                <button class='btn btn-sm downloadfile'>
                                  <i class="bi bi-cloud-download" ></i>
                                </button>


                               <!---- Maycon --->

                                <button class="btn btn-sm delete-button excluirArquivosProcesso">
                                    <i class="bi bi-x text-danger"></i>
                                </button>
                            </p>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
      <div class="color-background-white-02">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Fechar</button>
        <button id="enviarArquivos" onclick="uploadFiles()" class="btn btn-success btn-back-number btn-submit">Inserir</button>
      </div>
    </div>
  </div>
</div>
{{--modal de exclusao de documento--}}
<div class="modal" tabindex="-1" id="modalConfirmaExcluirDocumento" role="dialog" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Excluir arquivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="arquivoParaExclusao"></p>
      </div>
      <div class="modal-footer">
        <button type="button" id="excluir" class="btn btn-success btn-back-number">Save changes</button>
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
{{-- confirmar exclusao de documentos --}}
<div class="modal fade" id="confirmarExclusaoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este arquivo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary_01" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarExclusao">Confirmar Exclusão</button>
            </div>
        </div>
    </div>
</div>
<!-- modal comentario do processo -->
<div class="modal fade" id="modalComentario" tabindex="-1" aria-labelledby="modalComentarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalComentarioLabel">
            <h6 class="m-0 font-weight-bold text-primary">Comentarios do processo</h6>
        </h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
        <div class="modal-body">
            <div class="container-fluid row">
                <textarea class="form-control input-login" type="text" id="observacao" placeholder="Se necessario adicione comentario aqui"></textarea>
                 <div id="contadorObservacaoComentario">0/500</div>
            </div>
            <div class="row container-fluid table-responsive-sm">
                <table id="tabelaDados" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Usuario</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($observacao))
                    @forelse($observacao as $line)
                        <tr>
                            <td>{{ date('d/m/Y H:i:s', strtotime($line->created_at)) }}</td>
                            <td>{{isset($line) ? $line->name : null }}</td>
                            <td>{{isset($line) ? $line->observacao : null }}</td>
                        </tr>
                    @empty

                    @endforelse
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
      <div class="modal-footer modal-footer-background">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Fechar</button>
        <button id="enviarComentario" class="btn btn-success btn-back-number" disabled>Inserir</button>
      </div>
    </div>
  </div>
</div>
<!-- modal do historico do processo -->
<div class="modal fade" id="modalHistorico" tabindex="-1" aria-labelledby="modalHistoricoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalHistoricoLabel">
            <h6 class="m-0 font-weight-bold text-primary">Historico</h6>
        </h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
        <div class="modal-body">
            <div class="container-fluid table-responsive-sm">
                @if(isset($historico))
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Usuario</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($historico as $line)
                        <tr>
                            <td>{{ date('d/m/Y H:i:s', strtotime($line->created_at)) }}</td>
                            <td>{{isset($line) ? $line->u_name : null }}</td>
                            <td>{{isset($line) ? $line->historico : null }}</td>
                        </tr>
                    @empty

                    @endforelse
                    </tbody>
                </table>
                @endif
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Fechar</button>
        <button class="btn btn-success btn-back-number btn-submit">Inserir</button>
      </div>
    </div>
  </div>
</div>
<!-- modal para pendenciar processos -->
<div class="modal fade" id="modalPendencia" tabindex="-1" aria-labelledby="modalPendenciaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalPendenciaLabel">
            <h6 class="m-0 font-weight-bold text-primary">Pendenciar processo</h6>
        </h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="pendencia-error"></div>
        <div class="container-fluid row mb-3">
            <table id="tabelaDados" class="table table-hover">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Usuario</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($pendencias))
                @forelse($pendencias as $line)
                    <tr>
                        <td>{{ date('d/m/Y H:i:s', strtotime($line->created_at)) }}</td>
                        <td>{{isset($line) ? $line->name : null }}</td>
                        <td>{{isset($line) ? $line->observacao : null }}</td>
                    </tr>
                @empty

                @endforelse
                @endif
                </tbody>
            </table>

        </div>
       </div>
       <div class="modal-body">
        <div class="container-fluid row mb-3">
            <textarea class="form-control input-login" type="text" id="observacaoPendencia" name="observacaoPendencia" placeholder="Adicione o motivo da pendencia"></textarea>
            <div id="contadorCaracteres">0/500</div>
        </div>
        <div class="container-fluid row">
            <div type="hidden" id="user_hidden"></div>
            <div class="form-group">
                <label for="grupos" class="form-label">Usuarios a serem notificados</label>
                <input type="text" id="user-selecionado" class="form-control" data-role="tagsinput" >
                <input type="text" id="user-select" data-provide="typeahead" class="form-control" value="">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Close</button>
        <button id="pendencia" type="button" class="btn btn-success btn-back-number">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- modal aprovacao pendenciado -->
<div class="modal fade" id="modalPendenciado" tabindex="-1" aria-labelledby="modalPendenciadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalPendenciadoLabel">
            <h6 class="m-0 font-weight-bold text-primary">Continuar processo pendenciado</h6>
        </h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row container-fluid table-responsive-sm">
            <table id="tabelaDados" class="table table-hover">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Usuario</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($pendencias))
                @forelse($pendencias as $line)
                    <tr>
                        <td>{{ date('d/m/Y H:i:s', strtotime($line->created_at)) }}</td>
                        <td>{{isset($line) ? $line->name : null }}</td>
                        <td>{{isset($line) ? $line->observacao : null }}</td>
                    </tr>
                @empty

                @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-body">
        <div class="container-fluid row mb-3">
            <textarea class="form-control" type="text" id="observacaoPendenciado" name="observacaoPendenciado" placeholder="Adicione ajustes para justificar aprovaçao"></textarea>
            <div id="contadorCaracteresPendenciado">0/500</div>
        </div>
        <hr>
        <div class="container-fluid row">
            <div type="hidden" id="user_hidden"></div>
            <div class="form-group">
                <label for="grupos" class="form-label">Usuarios a serem notificados</label>
                <input type="text" id="user-selecionado-pendenciado" class="form-control" data-role="tagsinput" >
                <input type="text" id="user-select-pendenciado" data-provide="typeahead" class="form-control" value="">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Close</button>
        <button onclick="aprovarPendenciado()" type="button" class="btn btn-success btn-back-number">Save changes</button>
      </div>
    </div>
  </div>
</div>

{{-- deletar processo --}}
<div class="modal fade" id="modalDeletar" tabindex="-1" role="dialog" aria-labelledby="modalDeletarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeletarModalLabel">Deletando processo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger error-tipo-cobranca" style="display: none">
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <textarea class="form-control input-login" name="comentarioDelete" id="comentarioDelete" placeholder="Motivo da deleção"></textarea>
                 <div id="contadorCaracteresDelete">0/500</div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Fechar</button>
        <button id="deletarProcesso" type="button" class="btn btn-success btn-back-number">Enviar</button>
      </div>
    </div>
  </div>
</div>
{{-- aviso sem anexo--}}
<div class="modal" tabindex="-1" id="fileAlertModal" role="dialog" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Adicionar sem arquivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Voce esta iniciando um processo sem arquivos</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="enviarAssimMesmo" class="btn btn-success btn-back-number">Save changes</button>
        <button type="button" class="btn btn-success btn-back-number" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

