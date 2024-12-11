{{-- modal cadastro de rateio --}}
<div class="modal fade" id="modalCadastroRateio" tabindex="-1" role="dialog" aria-labelledby="modalCadRateio"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="font-regular-wt text-processo" id="modalCadRateio">Cadastrar rateio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="cadastroRateioForm" class="form-group" action="{{ route('rateio.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="formCriarRateio">
                        <div class="messages-modal-cad-rateio"></div>
                        <input type="hidden" id="id_rateio_edit" name="id_categoria_edit" value="">
                        <div class="hidden_centro_custo"></div>
                        <div class="form-row remove-on-clone">
                            <div class="form-group col-12">
                                <label for="nome_rateio" class="required label-number">Nome/Identificação do
                                    rateio</label>
                                <input id="nome_rateio" class="input-login form-control" name="nome">
                            </div>
                        </div>
                        <div id="clonarRateio" class="form-row">
                            <div class="form-group col-md-7">
                                <div class="dropdown add_centro_custo">
                                    <label for="centro_custo" class="required label-number">Selecionar Centro
                                        Custo:</label>
                                    <button id="centro_custo-rateio" name="centro_custo"
                                        class="btn dropdown-toggle col-12 btn-transparent" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Selecione um centro de custo
                                    </button>
                                    <div id="centro_custo-rateio" class="dropdown-menu dropdown-centro_custo col-12"
                                        aria-labelledby="centro_custo" style="max-height: 200px; overflow-y: auto;">
                                        <a id="addRateioCentroCusto" class="dropdown-item" href="#">
                                            <i class="bi bi-plus-circle-fill text-success">
                                            </i> Adicionar
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @foreach ($centrosCusto as $centro_custo)
                                            <a id="{{ $centro_custo->id }}" class="dropdown-item" href="#">
                                                <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                                <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                                {{ $centro_custo->nome }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="percentual_centro_custo" class="required label-number">Percentual:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input name="percentual_rateio[]" class="input-login form-control"
                                        value="{{ isset($contasreceber) ? $contasreceber->percentual : null }}">
                                </div>
                            </div>
                            <div class="form-group input-group col-md-2">
                                <label for="cloneRateio" class="remove-on-clone label-number">Add</label>
                                <div class="input-group">
                                    <button id="cloneRateio" class="btn btn-sm btn-success">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-back-number font-weigth-bold text-primary"
                        data-dismiss="modal">Voltar</button>
                    <button type="button" class="btn btn-success" id="inserirRateios">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
