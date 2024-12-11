<form wire:ignore.self wire:submit.prevent="saveAccount" style="height: 100%">
    <div @class(['row'])>
        <div @class(['col-2'])></div>
        <div @class(['col'])>
            <div @class(['card'])>
                <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>Informações principais</span>
                <div @class(['card-body'])>
                    <div class="row">
                        <div class="font-regular-wt font-heading-bar mt-3">
                            Os dados abaixo são muito importantes para o cadastro do seu processo de faturamento. Preencha-os com atenção.
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <div class="custom-control custom-switch">
                                <input name="imposto" type="checkbox" class="custom-control-input" id="switch-imposto" {{ isset($processo) && $processo->f_id == 0 ? 'checked' : '' }}>
                                <label class="custom-control-label lable-number" for="switch-imposto">Imposto ?</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-control custom-switch switch-pago">
                                <input type="checkbox" class="custom-control-input" id="switch-pago">
                                <label class="custom-control-label lable-number" for="switch-pago">Pago ?</label>
                            </div>
                        </div>
                    </div>
                    <div @class(['row'])>
                        <div @class(['col'])>
                            <label class="label-number" for="busca_nome">Fornecedor</label>
                            @include('livewire.payable.modal.fornecedor-input')
                        </div>
                        <div @class(['col'])>
                            <label class="label-number" for="numero_nota">Numero nota fiscal</label>
                            <input name="numero_nota" type="text" class="input-login form-control" placeholder="Numero nota" value="{{ (isset($processo)) ? $processo->num_nota : null }}">
                        </div>
                    </div>
                    <div @class(['row', 'mt-1'])>
                        <div class="col">
                            <label class="label-number" for="emissao_nota">Emissão nota</label>
                            <input name="emissao_nota" type="date" class="input-login form-control" value="{{ (isset($processo)) ? $processo->p_emissao : null }}">
                        </div>
                        <div class="col">
                            <label class="label-number" for="competencia">Competência</label>
                            <input name="competencia" type="date" class="input-login form-control" value="{{ (isset($processo)) ? $processo->competencia : null }}">
                        </div>
                        <div class="col">
                            <label class="label-number" for="valor_total" style="font-size: 14px; white-space: nowrap;">Valor total da nota R$</label>
                            <input id="valor_total" name="valor" type="text" class="input-login form-control" placeholder="0,00" value="{{ (isset($processo)) ? App\Helpers\FormatUtils::formatMoney($processo->valor) : null }}">
                        </div>
                        <div class="col">
                            <label class="label-number" for="condicao">Condição</label>
                            <select id="condicaoSelect" name="condicao" class="input-login select-number form-control form-select" aria-label=".form-select">
                                @if(isset($processo))
                                    <option value="{{ $processo->p_condicao }}">{{ $processo->p_condicao == 'vista' ? 'A vista' : 'A prazo' }}</option>
                                @else
                                    <option>Selecione</option>
                                @endif
                                <option value="vista">A vista</option>
                                <option value="prazo">A prazo</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="label-number" for="valorPrimeiraParcela" style="font-size: 14px; white-space: nowrap;">Valor da 1ª parcela R$</label>
                            <input id="valorPrimeiraParcela" name="valor0" type="text" class="input-login form-control" placeholder="0,00" value="{{ (isset($processo)) ? json_decode($processo->parcelas)->valor0: null }}">
                        </div>
                        <div class="col">
                            <label class="label-number" for="dataPrimeiraParcela">Data parcela 1</label>
                            <input id="dataPrimeiraParcela" name="data0" type="date" class="input-login form-control" value="{{ (isset($processo)) ? json_decode($processo->parcelas)->data0: null }}">
                        </div>
                        <div class="col">
                            <label class="label-number" for="parcela">Parcelas</label>
                            <input id="parcela" name="parcela" type="text" class="input-login  form-control" placeholder="Qtde" value="{{ (isset($processo)) ? $processo->qtde_parcelas : null }}">
                        </div>
                    </div>
                    <div @class(['row', 'mt-1'])>
                        <div class=" col">
                            <label @class(['label-number']) for="parcela">Categoria financeira</label>
                            @include('components.layouts.forms.dropdowns.finance-category-dropdown')
                        </div>
                        <div class="col">
                            <label class="label-number" for="parcela">Tipo de cobrança</label>
                            @include('form-parts.html.tipo-cobranca-select')
                        </div>
                    </div>
                </div>
                <div @class(['card-footer'])>
                    <button @class(['btn', 'btn-sm', 'btn-success'])>Salvar</button>
                </div>
            </div>
        </div>
        <div @class(['col-2'])></div>
    </div>
</form>
