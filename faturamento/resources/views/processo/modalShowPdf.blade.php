{{-- edicao do processo --}}
<div class="modal fade" id="modalVelho" tabindex="-1" role="dialog" aria-labelledby="modalEdicaoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEdicaoModalLabel">Edição dos dados do processo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editProcessoVencimentoValor" action="{{ route('processo.update-pvv', ['processo' => $processo->id]) }}">
         {{ method_field('PUT') }}
        @csrf
      <div class="modal-body">
        <div class="alert alert-danger error-tipo-cobranca" style="display: none">
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="name">Fornecedor</label>
                <input name="name" type="text" class="form-control" value="{{$processo->fornecedor}}" disabled>
            </div>
            <div class="form-group col-md-2">
                <label for="numero_nota">Numero nota fiscal</label>
                <input name="numero_nota" type="text" class="form-control" value="{{$processo->num_nota}}">
            </div>
             <div class="form-group col-md-3">
                    <label for="emissao_nota">Emissão nota {{$processo->p_emissao}}</label>
                    <input name="emissao_nota" type="date" class="form-control" value="{{ $processo->p_emissao }}">
            </div>
            <div class="form-group col-md-2">
                <label for="valor_total">Valor total da nota</label>
                <input name="valor_total" type="text" class="form-control" value="{{ App\Helpers\FormatUtils::formatMoney($processo->valor) }}">
            </div>
            <div class="form-group col-md-2">
                <label for="dataParcela">Data parcela</label>
                <input name="dataParcela" type="date" class="form-control" value="{{ date('Y-m-d', strtotime($processo->pvv_dtv)) }}">
            </div>
            <input hidden name="dt_parcelas" type="text" value="{{ $processo->parcelas }}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="inserirAlteracaoPVV" class="btn btn-primary">Enviar</button>
      </div>
      </form>
    </div>
  </div>
</div>
{{-- modal para editar multiplos vencimentos--}}
<div class="modal fade" id="modalEdicao" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Este processo tem diversos vencimentos</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-edit-processo-error" style="display: none;"></div>
        <p>Para editar esse processo você deve verificar todas as parcelas, para continuar clique no botão editar</p>

        {{-- @include('processo.formEdit') --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="enviarEdicaoProcesso" type="button" class="btn btn-primary">Seguir</button>
        <button id="editarProcesso" type="button" class="btn btn-danger">Editar</button>
      </div>
    </div>
  </div>
</div>
{{-- modal para pagar a fatura --}}
<div class="modal fade" id="modalPagamento" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="m-0 font-weight-bold text-primary font-size-18px">Pagar processo</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger form-pagar-processo" style="display: none;"></div>
                @include('processo.formPagarProcesso')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-back-number" data-dismiss="modal">Close</button>
                <button id="enviarPagamento" type="button" class="btn btn-primary btn-success btn-success-number">Continuar</button>
            </div>
        </div>
    </div>
</div>
{{-- modal para adicionar banco --}}
<div class="modal fade" id="modalAddBanco" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar banco</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger form-addbanco-processo" style="display: none;"></div>
                @include('processo.formAdicionarBanco')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="enviarBanco" type="button" class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </div>
</div>
{{-- modal para observacao--}}
<div class="modal fade" id="modalShowObservacao" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Observação deste processo</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger form-addbanco-processo" style="display: none;"></div>
                <p>{{ $processo->p_observacao }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


