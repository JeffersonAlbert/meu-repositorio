{{-- modal info de salvar sem arquivo --}}
<div wire:ignore.self class="modal fade secondModal" id="modalMassPay"
     tabindex="-1" role="dialog" aria-labelledby="modalMassPayLabel"
     aria-hidden="true" x-data="{ loading: false }">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                    Aviso!
                </span>
                <button type="button" class="close closeModal" data-dismiss="" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['alert', 'alert-warning'])>
                            <span class="font-extra light-dt delete-text">
                                @forelse($processToPay as $key => $process)
                                    <span>
                                        Você esta prestesa a pagar o processo <b>{{ $process['trace_code'] }}</b> com o valor <b>R$ {{ \App\Helpers\FormatUtils::formatMoney($process['price']) }}</b>.
                                        Lembrando que ao pagar dessa forma não será possivel editar juros multa ou descontos.
                                    </span><br><br>
                                @empty
                                    Nenhum processo selecionado.
                                @endforelse
                            </span>
                        </div>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
                <div class="row mb-2">
                    <div @class(['col-2'])></div>
                    <div class="col">
                        @include('components.layouts.forms.dropdowns.bank-account')
                    </div>
                    <div @class(['col-2'])></div>
                </div>
                <div class="row mb-2">
                    <div @class(['col-2'])></div>
                    <div class="col">
                        <select class="input-login form-control" wire:model="paymentMethodId">
                            <option value="{{ isset($paymentMethodId) ? $paymentMethodId : 0 }}">{{ isset($paymentMethodName) ? $paymentMethodName : 'Selecione a forma de pagamento' }}</option>
                            @foreach(\App\Models\FormasPagamento::all() as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['row'])>
                            <div @class(['col'])>
                                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="massPay">
                                    Deseja continuar?
                                </button>
                            </div>
                            <div @class(['col'])>
                                <button type="button" class="btn btn-success" data-dismiss="modal" wire:click="cancelMassPay">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
