@php($i = 0)
@forelse($installmentIds as $installment)
    @php($j = $i + 1)
        <div class="col-3 mt-1">
            <label class="label-number" for="dataParcela">{{ "Data {$j}ª parcela" }}</label>
            <input id="dataParcela{{ $i }}" type="date" class="input-login form-control"
                wire:model="installmentDates.{{ $i }}">
        </div>
        <div class="col-3 mt-1">
            <div @class(['row'])>
                <div @class(['col-8'])>
                    <label class="label-number" for="valorParcela{{ $i }}">{{ "Valor {$j}ª parcela R$" }}</label>
                    <input type="text" class="input-login form-control currency-type"
                        wire:model.debounce.500ms="installmentValues.{{ $i }}">
                </div>
                <div @class(['col-4'])>
                    <label class="label-number">&nbsp;</label>
                    <div @class(['row'])>
                        <button type="button" class="mr-1 btn {{ $installmentPaymentStatus[$i] == 0 ? 'btn-danger' : 'btn-success' }}"
                                wire:click="removeInstallment({{ $installment }})"
                                {{ $installmentPaymentStatus[$i] == 0 ? null : 'disabled' }}>
                            <i class="fas {{ $installmentPaymentStatus[$i] == 0 ? 'fa-trash' : 'bi-check' }}"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @php($i++)
@empty
    <span>Nenhuma parcela cadastrada</span>
@endforelse
