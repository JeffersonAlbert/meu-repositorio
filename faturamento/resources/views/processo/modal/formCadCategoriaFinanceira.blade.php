{{-- modal cadastro de categoria financeira --}}
<div class="modal fade" id="cad_categoria_financeira" tabindex="-1" role="dialog" aria-labelledby="modalCadCatFinanceira" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m-0 font-weight-bold text-primary font-size-18px" id="modalCadCatFinanceira">Cadastrar categoria financeira</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="POST">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <label class="label-number" for="nome">Descrição</label>
                    <input id="nome" name="nome" class="form-control input-login">
                </div>
                <div class="row">
                    <label class="label-number" for="dre-pai">Aparecer em:</label>
                    <select id="dre-pai" name="dre-pai" class="form-control input-login">
                        @foreach($dres as $dre)
                        <option value="{{ $dre->id }}">{{ $dre->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary font-weigtn-bold" data-dismiss="modal">Voltar</button>
                <button type="button" class="btn btn-success" id="inserirCentroCusto" disabled>Confirmar</button>
            </div>
            </form>
        </div>
    </div>
</div>
