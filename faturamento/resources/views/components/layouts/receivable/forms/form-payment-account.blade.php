<div class="row">
    <div class="col-2"></div>
    <div class="col">
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
        <div class="card border border-success">
            <span class="card-title titulo-grid-number font-regular-wt text-center">
                Informações do recebimento
            </span>
            <div class="card-body">
                <div class="row">
                    <div class="font-regular-wt font-heading-bar mt-3">
                        Os dados abaixo são muito importantes para a baixa da sua fatura. Preencha-os com atenção.
                    </div>
                </div>
                <div class="col mt-3 mb-3">
                    <div class="row">
                        <div class="col">
                            <span class="label-number">Valor da parcela</span>
                            <input type="text" class="form-control input-login" wire:model="paymentValue" id="value" name="value"
                                   placeholder="Digite o valor da parcela" disabled>
                        </div>
                        <div class="col">
                            <span class="label-number">Data de recebimento</span>
                            <input type="date" class="form-control input-login" wire:model="paymentDate" id="paymentDate"
                                   name="paymentDate" placeholder="Digite a data de recebimento">
                            @error('paymentDate')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col">
                            <span class="label-number">Forma de pagamento</span>
                            <select class="input-login form-control" wire:model="paymentMethodId">
                                @foreach(\App\Models\FormasPagamento::all() as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->nome }}</option>
                                @endforeach
                            </select>
                            @error('paymentMethodId')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col">
                            <span class="label-number required">Conta</span>
                            @include('components.layouts.forms.dropdowns.bank-account')
                            @error('bankId')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col">
                            <span class="label-number required">Valor pago</span>
                            <input type="text" class="form-control input-login paymentValue currency-type" wire:model.live.debounce.500ms="paymentValuePaid" id="valuePaid"
                                   name="valuePaid" placeholder="0,00">
                            @error('paymentValuePaid')
                            <span class="text-danger font-extra-light-dt">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col">
                            <span class="label-number">Juros</span>
                            <input type="text" class="form-control input-login paymentValue currency-type" wire:model.live.debounce.500ms="paymentFees" id="paymentFees"
                                   name="paymentFees" placeholder="0,00">
                        </div>
                        <div class="col">
                            <span class="label-number">Multa</span>
                            <input type="text" class="form-control input-login paymentValue currency-type" wire:model.live.debounce.500ms="paymentFine" id="paymentFine"
                                   name="paymentFine" placeholder="0,00">
                        </div>
                        <div class="col">
                            <span class="label-number">Multa</span>
                            <input type="text" class="form-control input-login paymentValue currency-type" wire:model.live.debounce.500ms="paymentDiscount" id="paymentDiscount"
                                   name="paymentDiscount" placeholder="0,00">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-right titulo-grid-number font-regular-wt">
                            <span class="totalPayment">Total a pagar: <b>{{ $amountWithLateInterestAndDiscount }}</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mt-2">
    <div class="col-2"></div>
    <div class="col">
        <div class="card border border-success">
        <span class="card-title titulo-grid-number font-regular-wt text-center">
            Informações do recebimento
        </span>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <span class="label-number">Observações</span>
                        <textarea class="form-control input-login" wire:model="paymentObservations" id="paymentObservations"
                                  name="paymentObservations" placeholder="Digite as observações"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mt-2">
    <div class="col-2"></div>
    <div class="col">
       <div class="card border border-success">
            <span class="card-title titulo-grid-number font-regular-wt text-center">
                Anexos
            </span>
           <div class="card-body">
               <div class="row">
                   <div class="col">
                       <label class="label-number" for="arquivo">Arquivo</label>
                       <input type="file" class="input-login form-control" wire:model="accountFiles.0" id="file" name="file">
                   </div>
                   <div class="col">
                       <label class="label-number" for="arquivo">Descrição</label>
                       <input type="descricao" class="input-login form-control" wire:model="accountFilesDescription.0" id="file" name="descricao">
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
                   <div class="row mt-1">
                       <div class="col">
                           <label class="label-number" for="arquivo">Arquivo</label>
                           <input type="file" class="input-login form-control" wire:model="accountFiles.{{ $index+1 }}" id="file" name="file">
                       </div>
                       <div class="col">
                           <label class="label-number" for="arquivo">Descrição</label>
                           <input type="descricao" class="input-login form-control" wire:model="accountFilesDescription.{{ $index+1 }}" id="file" name="descricao">
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
                           <label @class(['label-number']) for="addInput" class="label-number">Adicionar</label>
                           <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="removeInput({{ $index }})">
                               <i @class(['bi', 'bi-x'])></i>
                           </button>
                       </div>
                   </div>
               @endforeach
               <div class="row mt-4">
                   <table class="table table-responsive-sm table-head-number table-hover">
                       <thead class="head-grid-data">
                           <tr>
                               <th>#</th>
                               <th>Documento</th>
                               <th>Tipo</th>
                               <th>Descricao</th>
                           </tr>
                       </thead>
                       <tbody class="rel-tb-claro td-grid-font">
                            @forelse($paymentFiles as $file)
                                <tr>
                                    <td></td>
                                    <td>
                                        <a target="_blank" href='{{ route('r2.img', ['any' => "uploads/$file->fileName"]) }}'>
                                            {{ $file->fileName }}
                                        </a>
                                    </td>
                                    <td>{{ $file->fileType ?? 'Não informado' }}</td>
                                    <td>{{ $file->fileDesc }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Nenhum arquivo anexado</td>
                                </tr>
                            @endforelse
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mt-2 mb-2">
    <div class="col-2"></div>
    <div class="col">
        <div class="card border border-success">
            <div class="card-body">
                <div class="row">
                    <div class="col-10"></div>
                    <div class="col">
                        <button type="submit" class="btn btn-success w-100" wire:click.prevent="receiveAccount({{ $paymentId }})">
                            Receber
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>
