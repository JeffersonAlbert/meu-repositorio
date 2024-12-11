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
                Informações de pagamento
            </span>
            <div @class(['card-body'])>
                <div @class(['row'])>
                    <div class="font-regular-wt font-heading-bar mt-3">
                        Os dados abaixo são muito importantes para a baixa da sua fatura. Preencha-os com atenção.
                    </div>
                </div>
                <div @class(['row', 'mt-3', 'mb-3'])>
                    <div @class(['col'])>
                        <span @class(['label-number'])>Valor da parcela</span>
                        <input type="text" class="input-login form-control" wire:model="installmentValue"
                               disabled>
                    </div>
                    <div wire:ignore.self @class(['col'])>
                        <span @class(['label-number', 'required'])>Data do pagamento</span>
                        <input type="date" class="input-login form-control" wire:model="paymentDate">
                    </div>
                    <div @class(['col'])>
                        <span @class(['label-number'])>Forma de pagamento</span>
                        <select class="input-login form-control" wire:model="paymentMethodId">
                            <option value="{{ isset($paymentMethodId) ? $paymentMethodId : 0 }}">{{ isset($paymentMethodName) ? $paymentMethodName : 'Selecione a forma de pagamento' }}</option>
                            @foreach(\App\Models\FormasPagamento::all() as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->nome }}</option>
                            @endforeach
                        </select>
                        @if(isset($errorMessages['emptyPaymentMethod']))
                            <span class="text-danger font-extra-light-dt">
                                {{ $errorMessages['emptyPaymentMethod'][0] }}
                            </span>
                        @endif
                    </div>
                    <div @class(['col'])>
                        <span @class(['label-number', 'required'])>Conta</span>
                        @include('components.layouts.forms.dropdowns.bank-account')
                        @if(isset($errorMessages['id_banco']))
                            <span class="text-danger font-extra-light-dt">
                                {{ $errorMessages['id_banco'][0] }}
                            </span>
                        @endif
                    </div>
                </div>
                <div @class(['row','mt-2'])>
                    <div @class(['col'])>
                        <span @class(['label-number', 'required'])>Valor pago</span>
                        <input type="text" @class(['input-login', 'totalPayment',
                            'form-control', 'currency-type']) wire:ignore
                        wire:model.live.debounce.500ms="amountWithLateInterestAndDiscount">
                    </div>
                    <div @class(['col'])>
                        <span @class(['label-number', 'required'])>Juros</span>
                        <input type="text" @class(['input-login', 'totalPayment',
                            'form-control', 'currency-type']) wire:model.live.debounce.500ms="interest">
                    </div>
                    <div @class(['col'])>
                        <span @class(['label-number', 'required'])>Multa</span>
                        <input type="text" @class(['input-login', 'totalPayment',
                            'form-control', 'currency-type']) wire:ignore
                               wire:model.live.debounce.500ms="fine">
                    </div>
                    <div @class(['col'])>
                        <span @class(['label-number', 'required'])>Desconto</span>
                        <input type="text" @class(['input-login', 'totalPayment',
                            'form-control', 'currency-type']) wire:ignore
                               wire:model.live.debounce.500ms="discount">
                    </div>
                </div>
                <div @class(['row', 'mt-3'])>
                    <div @class(['col', 'text-right', 'titulo-grid-number', 'font-regular-wt'])>
                        <span class="totalPayment">Total: <b>{{ isset($amountWithLateInterestAndDiscount) && is_null($amountWithLateInterestAndDiscount) ? $paymentValue : $amountWithLateInterestAndDiscount }}</b></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
<div @class(['row', 'mt-2'])>
    <div @class(['col-2'])></div>
    <div @class(['col'])>
        <div @class(['card', 'border', 'border-success'])>
        <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                Observações
        </span>
            <div @class(['card-body'])>
                <div @class(['row'])>
                    <div @class(['col'])>
                        <span @class(['label-number'])>Observações</span>
                        <textarea class="input-login form-control" wire:model="observation"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
<div @class(['row', 'mt-2'])>
    <div @class(['col-2'])></div>
    <div @class(['col'])>
        <div @class(['card', 'border', 'border-success'])>
            <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                Anexos
            </span>
            <div @class(['card-body'])>
                <div @class(['row'])>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="arquivo">Arquivo</label>
                        <input name="arquivo" type="file" class="input-login form-control" wire:model="accountFiles.0">
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="descricao">Descrição</label>
                        <input name="descricao" type="text" class="input-login form-control" placeholder="" wire:model="accountFilesDescription.0">
                    </div>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="data">Tipo</label>
                        <select name="data" class="input-login form-control" wire:model="accountFilesType.0">
                            <option value="">Selecione o tipo do documento</option>
                            <option value="contrato">Contrato</option>
                            <option value="documento_fiscal">Documento fiscal</option>
                            <option value="documento_cobranca">Documento de cobrança</option>
                            <option value="comprovante_pagamento">Comprovante de pagamento</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>
                    <div @class(['col-1'])>
                        <label @class(['label-number']) for="addInput" class="label-number">Adicionar</label>
                        <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="addInput">
                            <i @class(['bi', 'bi-plus'])></i>
                        </button>
                    </div>
                </div>
                @foreach($inputs as $index => $input)
                    <div @class(['row', 'mt-1'])>
                        <div @class(['col'])>
                            <label @class(['label-number']) for="arquivo">Arquivo</label>
                            <input name="arquivo[]" type="file" class="input-login form-control" wire:model.defer="accountFiles.{{ $index+1 }}">
                        </div>
                        <div @class(['col'])>
                            <label @class(['label-number']) for="descricao">Descrição</label>
                            <input name="descricao" type="text" class="input-login form-control" placeholder="" wire:model="accountFilesDescription.{{ $index+1 }}">
                        </div>
                        <div @class(['col'])>
                            <label @class(['label-number']) for="data">Tipo</label>
                            <select name="data" class="input-login form-control" wire:model="accountFilesType.{{ $index+1 }}">
                                <option value="">Selecione o tipo do documento</option>
                                <option value="contrato">Contrato</option>
                                <option value="documento_fiscal">Documento fiscal</option>
                                <option value="documento_cobranca">Documento de cobrança</option>
                                <option value="comprovante_pagamento">Comprovante de pagamento</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <div @class(['col-1'])>
                            <label @class(['label-number']) for="removeInput" class="label-number">Adicionar</label>
                            <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="removeInput({{ $index }})">
                                <i @class(['bi', 'bi-x'])></i>
                            </button>
                        </div>
                    </div>
                @endforeach
                @if(isset($files_type_description) and (!is_null($files_type_description)))
                    <div @class(['row', 'mt-4'])>
                        <table @class(['table', 'table-responsive-sm', 'table-head-number', 'table-hover'])>
                            <thead @class(['head-grid-data'])>
                            <tr>
                                <th>#</th>
                                <th>Documento</th>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody @class(['rel-tb-claro', 'td-grid-font'])>
                                @foreach(json_decode($files_type_description) as $key => $file)
                                    <tr id="row{{ $key+1 }}">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <a target="_blank" href='{{ route('r2.img', ['any' => "uploads/$file->fileName"]) }}'>
                                                {{ $file->fileName }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $file->fileType ?? 'Não informado' }}
                                        </td>
                                        <td>
                                            {{ $file->fileDesc }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="askDeleteFile('{{ $file->fileName }}',
                                                                                '{{ $key+1 }}')"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
<div @class(['row', 'mt-2', 'mb-2'])>
    <div @class(['col-2'])></div>
    <div @class(['col'])>
        <div @class(['card', 'border', 'border-success'])>
            <div @class(['card-body'])>
                <div @class(['row'])>
                    <div @class(['col-10'])>
                            @if(isset($errorMessages['id_processo_vencimento_valor']))
                            <div class="alert alert-danger">
                                <span class="text-danger font-extra-light-dt">
                                    {{ $errorMessages['id_processo_vencimento_valor'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div @class(['col'])>
                        @if(isset($pay) and $pay == true)
                            <button wire:click.prevent="updatePayment" class="btn btn-success w-100">Atualizar pagamento</button>
                        @else
                            <button wire:click.prevent="payAccount" class="btn btn-success w-100">Confirmar pagamento</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
