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
                        <div class="custom-control custom-switch">
                            <input wire:model="tax"  name="imposto" type="checkbox" class="custom-control-input" id="switch-imposto" {{ isset($processo) && $processo->f_id == 0 ? 'checked' : '' }}>
                            <label class="custom-control-label lable-number" for="switch-imposto">Imposto</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch switch-pago">
                            <input wire:model="pay" type="checkbox" class="custom-control-input" id="switch-pago">
                            <label class="custom-control-label lable-number" for="switch-pago">Pago</label>
                        </div>
                    </div>
                </div>
                <div @class(['row'])>
                    <div @class(['col-8'])>
                        <label class="label-number" for="busca_nome">Fornecedor</label>
                        @include('components.layouts.forms.dropdowns.supplier-search-input-dropdown')
                        @if(isset($errorMessages['id_fornecedor']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['id_fornecedor'][0] }}</span>
                        @endif
                    </div>
                    <div @class(['col-4'])>
                        <label class="label-number" for="numero_nota">Numero nota fiscal</label>
                        <input wire:model="numberNotaFiscal" name="numero_nota" type="text" class="input-login form-control" placeholder="Numero nota" value="{{ (isset($processo)) ? $processo->num_nota : null }}">
                        @if(isset($errorMessages['numero_nota']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['numero_nota'][0] }}</span>
                        @endif
                    </div>
                </div>
                <div @class(['row', 'mt-1'])>
                    <div class="col">
                        <label class="label-number" for="emissao_nota">Emissão nota</label>
                        <input wire:model="emissionDate" name="emissao_nota" type="date" class="input-login form-control" value="{{ (isset($processo)) ? $processo->p_emissao : null }}">
                        @if(isset($errorMessages['emissao_nota']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['emissao_nota'][0] }}</span>
                        @endif
                    </div>
                    <div class="col">
                        <label class="label-number" for="competencia">Competência</label>
                        <input wire:model="competence" name="competencia" type="date" class="input-login form-control" value="{{ (isset($processo)) ? $processo->competencia : null }}">
                        @if(isset($errorMessages['competencia']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['competencia'][0] }}</span>
                        @endif
                    </div>
                    <div class="col">
                        <label class="label-number" for="valor_total" style="font-size: 14px; white-space: nowrap;">Valor total da nota R$</label>
                        <input wire:model="totalValue" id="valor_total" name="valor" type="text" class="input-login form-control currency-type"
                               placeholder="0,00">
                        @if(isset($errorMessages['valor']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['valor'][0] }}</span>
                        @endif
                    </div>
                    <div class="col">
                        <label class="label-number" for="condicao">Condição</label>
                        <select wire:model="paymentCondition" id="condicaoSelect" name="condicao" class="input-login select-number form-control form-select" aria-label=".form-select">
                            @if(isset($processo))
                                <option value="{{ $processo->p_condicao }}">{{ $processo->p_condicao == 'vista' ? 'A vista' : 'A prazo' }}</option>
                            @else
                                <option>Selecione</option>
                            @endif
                            <option value="vista">A vista</option>
                            <option value="prazo">A prazo</option>
                        </select>
                        @if(isset($errorMessages['condicao']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['condicao'][0] }}</span>
                        @endif
                    </div>
                    <div class="col">
                        <label class="label-number" for="valorPrimeiraParcela" style="font-size: 14px; white-space: nowrap;">Valor da 1ª parcela R$</label>
                        <input wire:model="valueOfTheFirstInstallment" id="valorPrimeiraParcela" name="valor0" type="text" class="input-login form-control currency-type" placeholder="0,00">
                    </div>
                    <div class="col">
                        <label class="label-number" for="dataPrimeiraParcela">Data parcela 1</label>
                        <input wire:model="dateOfTheFirstInstallment" id="dataPrimeiraParcela" name="data0" type="date" class="input-login form-control" value="{{ (isset($processo)) ? json_decode($processo->parcelas)->data0: null }}">
                    </div>
                    <div class="col">
                        <label class="label-number" for="parcela">Parcelas</label>
                        <input wire:model="installments" id="parcela" name="parcela" type="text" class="input-login  form-control parcela" placeholder="Qtde" value="">
                        @if(isset($errorMessages['parcelas']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['parcelas'][0] }}</span>
                        @endif
                    </div>
                </div>
                <div @class(['row', 'mt-1'])>
                    <div class=" col">
                        <label @class(['label-number']) for="dropdownCategoriaButton">Categoria financeira</label>
                        @include('components.layouts.forms.dropdowns.finance-category-dropdown')
                        @if(isset($errorMessages['id_sub_dre']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['id_sub_dre'][0] }}</span>
                        @endif
                    </div>
                    <div class="col">
                        <label class="label-number" for="dropdownCobrancaButton">Tipo de cobrança</label>
                        @include('components.layouts.forms.dropdowns.billing-type')
                        @if(isset($errorMessages['tipo_cobranca']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['tipo_cobranca'][0] }}</span>
                        @endif
                    </div>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="workflow">Workflow</label>
                        @include('components.layouts.forms.dropdowns.workflow-dropdown')
                        @if(isset($errorMessages['id_workflow']))
                            <span class="text-danger font-extra-light-dt">{{ $errorMessages['id_workflow'][0] }}</span>
                        @endif
                    </div>
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
                <div @class(['row', 'mt-1'])>
                    <div @class(['col'])>
                        <label @class(['label-number']) for="observacao">Observação888</label>
                        <textarea wire:model="observation" name="observacao" class="input-login form-control" rows="3" placeholder="">{{ (isset($processo)) ? $processo->observacao : null }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['col-2'])></div>
</div>
@if(isset($files_type_description))
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
                        @if((!is_null($files_type_description) and ($files_type_description !== 'null')))
                        @foreach(json_decode($files_type_description) as $key => $file)
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
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div @class(['col-2'])></div>
    </div>
@endif
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
                            <label @class(['label-number']) for="removeInput" class="label-number">Adicionar</label>
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
        <div @class(['row', 'mt-1'])>
            <div @class(['col-2'])></div>
            <div @class(['col'])>
                <div @class(['card', 'border', 'border-success'])>
                <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                    Parcelas pagamento
                </span>
                    <div @class(['card-body'])>
                        @foreach($installmentStatus as $key => $installment)
                            <div @class(['row', 'mt-1'])>
                                <div @class(['col'])>
                                    <label @class(['label-number']) for="valorParcela">Valor parcela {{ $key  }}R$</label>
                                    <input wire:model="installmentValue.{{ $key }}" name="valorParcela" type="text" class="input-login form-control currency-type" placeholder="0,00">
                                </div>
                                <div @class(['col'])>
                                    <label @class(['label-number']) for="dataParcela">Data parcela {{ $key }}</label>
                                    <input {{ $installment[$key] }}wire:model="installmentDate.{{ $key }}" name="dataParcela" type="date" class="input-login form-control" value="">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div @class(['col-2'])></div>
        </div>
        <div @class(['row', 'mt-3', 'mb-3'])>
            <div @class(['col-2'])></div>
            <div @class(['col', 'text-right'])>
                <button type="submit" @class(['btn', 'btn-success', 'btn-sm'])>Salvar</button>
            </div>
            <div @class(['col-2'])></div>
        </div>
    </div>
</div>
