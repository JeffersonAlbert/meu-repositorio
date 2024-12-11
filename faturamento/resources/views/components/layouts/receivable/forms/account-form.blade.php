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
                Informações principais
            </span>
            <div @class(['card-body'])>
                <div class="row">
                    <div class="font-regular-wt font-heading-bar mt-3">
                        Os dados abaixo são muito importantes para o cadastro do seu processo de faturamento. Preencha-os com atenção.
                    </div>
                </div>
                <div class="row mt-3 mb-3">
                    <div class="col">
                        <div class="custom-control custom-switch switch-pago">
                            <input wire:model="pay" type="checkbox" class="custom-control-input" id="switch-pago">
                            <label class="custom-control-label lable-number" for="switch-pago">Pago</label>
                        </div>
                    </div>
                </div>
                <div @class(['row'])>
                    <div @class(['col-8'])>
                        <label class="label-number" for="busca_nome">Cliente</label>
                        @include('components.layouts.forms.dropdowns.clients-search-input-dropdown')
                        @error('clientId')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div @class(['col-4'])>
                        <label class="label-number" for="numero_nota">Numero nota fiscal</label>
                        <input wire:model="numberNotaFiscal" name="numero_nota" type="text" class="input-login form-control" placeholder="Numero nota">
                        @error('numberNotaFiscal')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div @class(['row', 'mt-1'])>
                    <div class="col">
                        <label class="label-number" for="emissao_nota">Emissão nota</label>
                        <input wire:model="emissionDate" name="emissao_nota" type="date" class="input-login form-control">
                        @error('emissionDate')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col">
                        <label class="label-number" for="competencia">Competência</label>
                        <input wire:model="competence" name="competencia" type="date" class="input-login form-control">
                        @error('competence')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div @class(['col'])>
                        <label class="label-number" for="totalValue">Valor total</label>
                        <input wire:model.live.debounce.500ms.defer="totalValue" name="totalValue" type="text" class="input-login form-control currency-type">
                        @error('totalValue')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col">
                        <label class="label-number" for="condicao">Condição</label>
                        <select wire:model="paymentCondition" id="condicaoSelect" name="condicao" class="input-login select-number form-control form-select" aria-label=".form-select">
                            @if(isset($paymentCondition))
                                <option value="{{ $paymentCondition }}">{{ $paymentCondition == 'vista' ? 'A vista' : 'A prazo' }}</option>
                            @else
                                <option>Selecione</option>
                            @endif
                            <option value="vista">A vista</option>
                            <option value="prazo">A prazo</option>
                        </select>
                        @error('paymentCondition')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div @class(['col'])>
                        <label class="label-number" for="parcela">Parcelas</label>
                        <input wire:model="installmentsQtd" id="parcela" name="parcela" type="text" class="input-login  form-control parcela" placeholder="Qtde" value="">
                        @error('installmentsQtd')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div @class(['row', 'mt-1'])>
                    <div class="col">
                        <label @class(['label-number']) for="dropdownCategoriaButton">Categoria financeira</label>
                        @include('components.layouts.forms.dropdowns.finance-category-dropdown')
                        @error('financeCategoryId')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col">
                        <label class="label-number" for="dropdownCobrancaButton">Tipo de cobrança</label>
                        @include('components.layouts.forms.dropdowns.billing-type')
                        @error('billingTypeId')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div @class(['col'])>
                        <label @class(['label-number']) for="workflow">Workflow</label>
                        @include('components.layouts.forms.dropdowns.workflow-dropdown')
                        @if(isset($errorMessages['id_workflow']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['id_workflow'][0] }}</span>
                        @endif
                    </div> --}}
                </div>
                <div @class(['row', 'mt-1'])>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="dropdownCentroCustoButton">Centro de custo</label>
                        @include('components.layouts.forms.dropdowns.centers-cost-dropdown')
                    </div>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="dropdownApportionmentButton">Rateio</label>
                        @include('components.layouts.forms.dropdowns.apportionment')
                    </div>
                </div>
                {{-- <div @class(['row', 'mt-1'])>
                        <div class="col-6">
                            <label for="centroCusto" @class(['label-number'])>Centro de custo</label>
                            @include('components.layouts.forms.dropdowns.centers-cost-dropdown')
                        </div>
                        <div @class(['col-3'])>
                            <label for="centroCustoValor" @class(['label-number'])>R$ Valor</label>
                            <input type="text" class="form-control" id="centroCustoValor"
                                   wire:model="apportionmentValue.0">
                        </div>
                        <div class="col-2">
                            <label for="percent" @class(['label-number'])>% Percentual</label>
                            <input type="text" class="form-control" id="percent"
                                   wire:model="apportionmentPercent.0">
                        </div>
                        <div class="col-1">
                            <label for="addApportionment" class="label-number">Adicionar</label>
                            <button wire:click="addInputApportionment" type="button" class="btn btn-md btn-success">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <input type="hidden" wire:model="apportionmentInputs">
                    @foreach($inputsApportionment as $index => $input)
                            <div class="col-6">
                                <label for="centroCusto" @class(['label-number'])>Centro de custo</label>
                                @include('components.layouts.forms.dropdowns.centers-cost-dropdown')
                            </div>
                            <div @class(['col-3'])>
                                <label for="centroCustoValor" @class(['label-number'])>R$ Valor</label>
                                <input type="text" class="form-control" id="centroCustoValor"
                                       wire:model="apportionmentValue.{{$index}}">
                            </div>
                            <div class="col-2">
                                <label for="percent" @class(['label-number'])>% Percentual</label>
                                <input type="text" class="form-control" id="percent"
                                       wire:model="apportionmentPercent.{{$index}}">
                            </div>
                            <div class="col-1">
                                <label for="addApportionment" class="label-number">Remover</label>
                                <button wire:click="removeInputApportionment({{$index}})" type="button" class="btn btn-md btn-success">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <input type="hidden" wire:model="apportionmentInputs.{{$index}}">
                    @endforeach

                </div> --}}
                <div @class(['row', 'mt-1'])>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="observacao">Observação</label>
                        <textarea wire:model="observation" name="observacao" class="input-login form-control" rows="3" placeholder="">{{ (isset($processo)) ? $processo->observacao : null }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
<div @class(['row', 'mt-1'])>
    <div @class(['col-2'])></div>
    <div @class(['col'])>
        <div @class(['card', 'border', 'border-success'])>
            <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                Parcelas pagamento
            </span>
            <div @class(['card-body'])>
                <div @class(['row', 'mt-2'])>
                    <div class="col-6">
                        <label class="label-number" for="installment.0.date">Data da 1ª parcela</label>
                        <input wire:model.defer="installments.0.date" id="dataParcela0" type="date" class="input-login form-control" >
                        @error('installments.0.date')
                        <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-5">
                        <label class="label-number" for="installment.0.value">Valor da 1ª parcela R$</label>
                        <input wire:model.live.debounce.500ms.defer="installments.0.value" id="valorParcela0" type="text" class="input-login form-control currency-type" >
                        @error('installments.0.value')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                        @enderror
                    </div>
                    <div @class(['col-1'])>
                        <label @class(['label-number']) for="installmentInput" class="label-number">Adicionar</label>
                        <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="addInstallmentInput">
                            <i @class(['bi', 'bi-plus'])></i>
                        </button>
                    </div>
                </div>
                @foreach($installmentsInputs as $index => $installments)
                    <div @class(['row', 'mt-2'])>
                        <div class="col-6">
                            <label class="label-number" for="installment.{{ $index+1 }}.date">Data da {{ $index+2 }}ª parcela</label>
                            <input wire:model.defer="installments.{{ $index+1 }}.date" id="dataParcela0" type="date" class="input-login form-control" >
                            @error('installments.'.($index+1).'.date')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-5">
                            <label class="label-number" for="installment.{{ $index+1 }}.value">Valor da {{ $index+2 }}ª parcela R$</label>
                            <input wire:model.live.debounce.500ms.defer="installments.{{ $index+1 }}.value" id="valorParcela0" type="text" class="input-login form-control currency-type" >
                            @error('installments.'.($index+1).'.value')
                                <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class(['col-1'])>
                            <label @class(['label-number']) for="removeInstallmentInput" class="label-number">Remover</label>
                            <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="removeInstallmentInput({{ $index+1 }})">
                                <i @class(['bi', 'bi-x'])></i>
                            </button>
                        </div>
                    </div>
                @endforeach
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
                Arquivos
            </span>
            <div @class(['card-body'])>
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
                    @forelse($files as $key => $file)
                        <tr id="row{{ $key + 1 }}">
                            <td> {{ $key + 1 }}</td>
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
                                <button type="button" class="btn btn-danger btn-sm" wire:click="askDeleteFile('{{ $file->fileName }}', '{{$key+1}}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum arquivo anexado</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
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
                <div @class(['row', 'mt-1'])>
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
                            <label @class(['label-number']) for="removeInput" class="label-number">Remover</label>
                            <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="removeInput({{ $index }})">
                                <i @class(['bi', 'bi-x'])></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
<div @class(['row', 'mt-2'])>
    <div @class(['col'])>
        {{-- <div @class(['row', 'mt-1'])>
            <div @class(['col-2'])></div>
            <div @class(['col'])>
                <div @class(['card', 'border', 'border-success'])>
                    <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                        Parcelas pagamento
                    </span>
                    <div @class(['card-body'])>
                            @if($paymentCondition === 'prazo' && count($installmentDates) > 0)
                            <div class="row mt-2">
                                @if(isset($update) and $update == true)
                                    @foreach($installmentDates as $index => $date)
                                        <div class="col-3">
                                            <label class="label-number" for="dataParcela{{ $index + 1 }}">Data parcela {{ $index + 1 }}</label>
                                            <input wire:model.defer="installmentDates.{{ $index }}" id="dataParcela{{ $index + 1 }}" type="date" class="input-login form-control" >
                                        </div>
                                        <div class="col-3">
                                            <label class="label-number" for="valorParcela{{ $index + 1 }}">Valor da parcela {{ $index + 1 }} R$</label>
                                            <input wire:model.live.debounce.500ms.defer="installmentValues.{{ $index }}" id="valorParcela{{ $index + 1 }}" type="text" class="input-login form-control currency-type" >
                                        </div>
                                    @endforeach
                                @else
                                    @include('components.layouts.forms.update-installments')
                                @endif
                            </div>
                            @else
                            <div class="row mt-2">
                                @include('components.layouts.forms.update-installments')
                            </div>
                            @endif
                    </div>
                </div>
            </div>
            <div @class(['col-2'])></div>
        </div> --}}
        <div @class(['row', 'mt-3', 'mb-3'])>
            <div @class(['col-2'])></div>
            <div @class(['col', 'text-right'])>
                @if($update)
                    <button wire:click.prevent="sendUpdateAccount({{ $editableId }})" type="submit" @class(['btn', 'btn-success', 'btn-sm'])>Atualizar</button>
                @else
                    <button id="sendSaveFormAccount" type="submit" @class(['btn', 'btn-success', 'btn-sm'])>Salvar</button>
                @endif
            </div>
            <div @class(['col-2'])></div>
        </div>
    </div>
</div>
