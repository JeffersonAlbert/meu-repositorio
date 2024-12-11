<div @class(['row'])>
    <div @class(['col-2'])></div>
    <div @class(['col'])>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {!! nl2br(session('error')) !!}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div @class(['card', 'border', 'border-success'])>
            <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                Informações das parcelas
            </span>
            <div @class(['card-body'])>
                <div @class(['row'])>
                    <div @class(['col-3'])>
                        <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                            Valor total da conta: R$
                        </span>
                    </div>
                    <div @class(['col-2'])>
                        <input @class(['form-control', 'input-login', 'text-right', 'currency-type']) wire:model="formatedTotalValue">
                        @if(isset($errorMessages['valorTotal']))
                            <span class="text-danger font-extra-light-dt">
                                {{ $errorMessages['valorTotal'][0] }}
                            </span>
                        @endif
                    </div>
                    <div @class(['col-3'])>
                        <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                            Quantidade de parcelas:
                        </span>
                        @if(isset($errorMessages['qtdeParcelas']))
                            <div @class(['row'])>
                                <span class="text-danger font-extra-light-dt">
                                    {{ $errorMessages['qtdeParcelas'][0] }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div @class(['col-1'])>
                        <input @class(['form-control', 'input-login', 'text-right']) wire:model="qtdParcelas">
                    </div>
                    <div @class(['col-2', 'text-center'])>
                        <button @class(['btn', 'btn', 'btn-success']) wire:click.prevent="addInstallment">
                            <i @class(['bi', 'bi-plus'])></i>
                        </button>
                    </div>
                </div>
                @forelse($installmentStatus as $index => $installmentData)
                    <div @class(['row', 'mt-2'])>
                        <div @class(["col-4"])>
                            <div @class(['form-group'])>
                                <label for="installmentStatus{{ $index }}">Vencimento {{ $index+1 }}ª parcela:</label>
                                <input {{ $installmentData["status"] == true ? 'disabled' : '' }}
                                       wire:model="installmentStatus.{{ $index }}.data"
                                       id="dataParcela" type="date"
                                       class="input-login form-control text-right currency-type" >
                                @if(isset($errorMessages['data'.$index]))
                                   <span class="text-danger font-extra-light-dt">
                                        {{ $errorMessages['data'.$index][0] }}
                                   </span>
                                @endif
                            </div>
                        </div>
                        <div @class(["col-4"])>
                            <div @class(['form-group'])>
                                <label for="installmentStatus{{ $index }}">Valor {{ $index+1 }}ª parcela R$:</label>
                                <input {{ $installmentData["status"] == true ? 'disabled' : '' }}
                                       wire:model="installmentStatus.{{ $index }}.valor"
                                       id="valorParcela" type="text"
                                       class="input-login form-control text-right currency-type" >
                                @if(isset($errorMessages['valor'.$index]))
                                    <span class="text-danger font-extra-light-dt">
                                        {{ $errorMessages['valor'.$index][0] }}
                                   </span>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" wire:model="installmentStatus.{{ $index }}.status">
                        <input type="hidden" wire:model="installmentStatus.{{ $index }}.id">
                        <input type="hidden" wire:model="installmentStatus.{{ $index }}.markedToDelete">
                        <div @class(["col-4", 'text-center'])>
                            <label @class(['label-number'])>&nbsp;</label>
                            <button @class(['btn', 'btn', 'btn-danger']) wire:click.prevent="markToDelete({{ $index }}, '{{ $installmentStatus[$index]['valor'] ?? 0,00}}')">
                                <i @class(['bi', 'bi-trash'])></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div @class(['row'])>
                        <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                            Não há parcelas para esta conta
                        </span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
<div @class(['row', 'mt-3'])>
    <div @class(['col-2'])></div>
    <div @class(['col', 'text-right'])>
        <button @class(['btn', 'btn-success']) wire:click.prevent="updateInstallments">
            Salvar
        </button>
    </div>
    <div @class(['col-2'])></div>
</div>
