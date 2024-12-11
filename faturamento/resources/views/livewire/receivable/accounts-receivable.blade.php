<div>
    {{-- Stop trying to control. --}}

    @include('layout.switch-dt-wt') <!-- Inclui o botão de troca de tema -->

    <script>
        document.addEventListener('DOMContentLoaded', initializeDarkModeToggle);
        document.addEventListener('livewire:load', initializeDarkModeToggle);
        document.addEventListener('livewire:update', initializeDarkModeToggle);
    </script>

    <div id="loader" class="loader" wire:loading
         wire:loading.target="searchSupplierCpfCnpj, saveSupplier, saveAccount, updateAccount">
        <div class="spinner-grow text-success" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>
    <div @class(['row'])>
        <div @class(['col-12'])>
            <div @class(['titulo-grid-number', 'font-regular-wt'])>
                <h3>Contas a receber</h3>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col-12']) x-data="{ expanded: false }">
            <div @class(['row'])>
                <div @class(['col', 'text-left'])>
                    <button @class(['btn', 'btn-sm', 'btn-search'])
                            x-on:click="expanded = ! expanded">
                        <i @class(['bi', 'bi-search'])></i>
                        Pesquisar
                    </button>
                </div>
                <div @class(['col', 'text-right'])>
                    @include('components.layouts.forms.dropdowns.rapid-actions-period-dropdown')
                </div>

                <div @class(['col', 'text-right'])>
                    @include('components.layouts.forms.dropdowns.rapid-actions-reports-dropdown')
                </div>
                <div @class(['col', 'text-right'])>
                    <button id="addAccount" @class(['btn', 'btn-sm', 'btn-success']) data-toggle="modal"
                            data-target="#modalAddAccount" wire:click="generateTraceCodeAndCleanForm">
                        <i @class(['bi', 'bi-plus'])></i>
                        Adicionar
                    </button>
                </div>
            </div>
            <div @class(['row', 'mt-3']) x-show="expanded" x-transition>
                <div @class(['col-12'])>
                    <form wire:submit.prevent="searchBy">
                        <div @class(['row', 'mt-1'])>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="trace_code">Rastreio:</label>
                                    <input wire:model="traceCode" @class(['input-login', 'form-control']) id="trace_code" type="text" placeholder="Código de rastreio">
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="fornecedorVal">Clientes:</label>
                                    @include('components.layouts.forms.dropdowns.clients-search-dropdown')
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="billingType">Tipo cobrança:</label>
                                    <select wire:model="billingType" @class(['input-login', 'form-control']) id="billingType">
                                        <option value="">Selecione a cobrança</option>
                                        @foreach(\App\Models\TipoCobranca::where('id_empresa', auth()->user()->id_empresa)->get() as $type)
                                            <option value="{{ $type->id }}">{{ $type->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="bank">Banco:</label>
                                    <select wire:model="bank" @class(['input-login', 'form-control']) id="bank">
                                        <option value="">Selecione o banco</option>
                                        @foreach(\App\Models\Bancos::where('id_empresa', auth()->user()->id_empresa)->get() as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="paymentMethod">Forma de pagamento:</label>
                                    <select wire:model="paymentMethod" @class(['input-login', 'form-control']) id="paymentMethod">
                                        <option value="">Selecione a forma de pagamento</option>
                                        @foreach(\App\Models\FormasPagamento::all() as $paymentMethod)
                                            <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div @class(['row'])>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="centerCost">Centro de custo:</label>
                                    <select wire:model="centerCost" @class(['input-login', 'form-control']) id="centerCost">
                                        <option value="">Selecione o centro de custo</option>
                                        @foreach(\App\Models\CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get() as $centerCost)
                                            <option value="{{ $centerCost->id }}">{{ $centerCost->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="apportioned">Rateio:</label>
                                    <select wire:model="apportioned" @class(['input-login', 'form-control']) id="apportioned">
                                        <option value="">Selecione o rateio</option>
                                        @foreach(\App\Models\Rateio::where('id_empresa', auth()->user()->id_empresa)->get() as $apportioned)
                                            <option value="{{ $apportioned->id }}">{{ $apportioned->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="billingDateRangeStart">Vencimento inicial:</label>
                                    <input wire:model="billingDateRangeStart" @class(['input-login', 'form-control']) id="billingDateRangeStart" type="date" />
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="billingDateRangeEnd">Vencimento final:</label>
                                    <input wire:model="billingDateRangeEnd" @class(['input-login', 'form-control']) id="billingDateRangeEnd" type="date" />
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="paymentDateRangeStart">Pagamento incial:</label>
                                    <input wire:model="paymentDateRangeStart" @class(['input-login', 'form-control']) id="paymentDateRangeStart" type="date" />
                                </div>
                            </div>
                            <div @class(['col'])>
                                <div @class(['form-group'])>
                                    <label @class(['label-number']) for="paymentDateRangeEnd">Pagamento final:</label>
                                    <input wire:model="paymentDateRangeEnd" @class(['input-login', 'form-control']) id="paymentDateRangeEnd" type="date" />
                                </div>
                            </div>
                        </div>
                        <div @class(['row'])>
                            <div @class(['col'])>
                                <button type="submit" @class(['btn', 'btn-success', 'btn-md'])>Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div @class(['row', 'mt-3'])>
        <div @class(['col-sm', 'pr-0'])>
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalOverdue')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Vencidos</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-danger'])>
                        {{ $receivableByStatus['totalOverdue'] }}
                    </p>
                    <p @class(['card-text', 'card-text-sm', 'reduce-margin'])>
                        {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}
                    </p>
                    <div @class(['progress'])>
                        <div @class(['progress-bar', ($showType == 'totalOverdue' ? 'bg-danger' : 'bg-white')]) role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-sm', 'p-0'])>
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalDueToday')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Vencem hoje: </p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-danger'])>
                        {{ $receivableByStatus['totalDueToday'] }}
                    </p>
                    <p @class(['card-text', 'card-text-sm', 'reduce-margin'])>
                        {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}
                    </p>
                    <div @class(['progress'])>
                        <div @class(['progress-bar', ($showType == 'totalDueToday' ? 'bg-danger' : 'bg-white')]) role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-sm', 'p-0'])>
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalDue')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>A vencer:</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-success'])>
                        {{ $receivableByStatus['totalDue'] }}
                    </p>
                    <p @class(['card-text', 'card-text-sm', 'reduce-margin'])>
                        {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}
                    </p>
                    <div @class(['progress'])>
                        <div @class(['progress-bar', ($showType == 'totalUpcoming' ? 'bg-success' : 'bg-white')]) role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-sm', 'p-0'])>
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalApproved')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Aprovados:</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-success'])>
                        {{ $receivableByStatus['totalApproved'] }}
                    </p>
                    <p @class(['card-text', 'card-text-sm', 'reduce-margin'])>
                        {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}
                    </p>
                    <div @class(['progress'])>
                        <div @class(['progress-bar', ($showType == 'totalPaid' ? 'bg-success' : 'bg-white')]) role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-sm', 'pl-0'])>
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('all')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Total: </p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-success'])>
                        {{ $receivableByStatus['total'] }}
                    </p>
                    <p @class(['card-text', 'card-text-sm', 'reduce-margin'])>
                        {{ \App\Helpers\FormatUtils::formatDate($period['startDate']) }} a {{ \App\Helpers\FormatUtils::formatDate($period['endDate']) }}
                    </p>
                    <div @class(['progress'])>
                        <div @class(['progress-bar', ($showType == 'totalForPeriod' ? 'bg-success' : 'bg-white')]) role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col'])>
            <table @class(['table', 'table-responsive-sm', 'table-head-number', 'table-bordered', 'table-hover'])>
                <thead @class(['head-grid-data'])>
                    <tr>
                        <th @class(['text-center'])>
                            <div onclick="event.stopPropagation()" class="custom-control custom-checkbox small">
                                <input onclick="event.stopPropagation()" class="custom-control-input"
                                       type="checkbox" id="chk_all">
                                <label onclick="event.stopPropagation()" class="custom-control-label" for="chk_all"></label>
                            </div>
                        </th>
                        <th>Identificação</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody @class(['rel-tb-claro', 'td-grid-font'])>
                    @foreach($receivableList as $receivable)
                        <tr wire:click="showDetails({{ $receivable->id }})">
                            <td></td>
                            <td>{{ $receivable->trace_code }}</td>
                            <td>{{ $receivable->cliente }}</td>
                            <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($receivable->valor) }}</td>
                            <td>{{ \App\Helpers\FormatUtils::formatDate($receivable->vencimento) }}</td>
                            <td>{{ $receivable->status ? ucfirst($receivable->status) : "Aberto"}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('components.layouts.receivable.modals.add-account')
    @include('components.layouts.receivable.modals.ask-installments')
    @include('components.layouts.receivable.modals.modal-show-details-account')
    @include('components.layouts.receivable.modals.modal-payment-account')
    @include('components.layouts.receivable.modals.edit-account')
</div>
<script>
    document.addEventListener('livewire:init', function () {
        $('.selectValue').select2(
            {
                placeholder: '{{ isset($f_name) ? $f_name : 'Selecione um cliente' }}',
                dropdownCssClass: 'input-login',
                selectionCssClass: 'input-login',
                containerCssClass: 'input-login',

            }
        ).on('change', function (e) {
            const selectedOption = $(this).find('option:selected');
            @this.selectedClient(selectedOption.val(), selectedOption.text());
        });

        Livewire.on('showModal', (event) => {
            $('#'+event[0]).modal('show');
        });

        Livewire.on('hideModal', (event) => {
            $('#'+event[0]).modal('hide');
        });

        $('#condicaoSelect').on('change', function(){
            if($(this).val() == 'vista'){
                $('#parcela').attr('disabled', true);
                $('#parcela').val(1);
            }else{
                $('#parcela').attr('disabled', false);
                $('#parcela').show();
            }
        });

        $('#parcela').on('blur', function(){
            @this.addInstallmentsQtd($(this).val());
        })

        $('.paymentValue').on('keyup', function(){
            @this.caluculateAmountWithLateInterestAndDiscount($(this).val());
        });

        $('.paymentValue').on('blur', function(){
            @this.caluculateAmountWithLateInterestAndDiscount($(this).val());
        });

        $("div.contas-receber").addClass('active-number');
        $("div.inicio").removeClass('active-number');
    });
</script>
