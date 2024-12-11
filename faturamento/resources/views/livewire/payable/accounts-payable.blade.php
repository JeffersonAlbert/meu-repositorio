<div>
    <div id="loader" class="loader" wire:loading
         wire:loading wire:target="searchSupplierCpfCnpj, saveSupplier, saveAccount, updateAccount,
         setPeriod, report, setType, setOnlyOpened, searchBy">
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>
    <div @class(['row', 'mb-3'])>
        @if (session()->has('error'))
            <div class="col-12">
                <div class="alert alert-danger">
                    {!! nl2br(session('error')) !!}
                </div>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="col-12">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        <div @class(['col-12'])>
            <div @class(['titulo-grid-number', 'font-regular-wt'])>
                <h3>Contas a pagar</h3>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
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
                                    <label @class(['label-number']) for="fornecedorVal">Fornecedores:</label>
                                    @include('components.layouts.forms.dropdowns.supplier-search-dropdown')
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
        <div @class(['col'])>
            <div class="custom-control custom-switch">
                <input wire:change="setOnlyOpened" type="checkbox" class="custom-control-input custom-checkbox" id="switch-opened" value="totalOverdue">
                <label class="custom-control-label" for="switch-opened">Exibir contas em aberto</label>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col-sm', 'pr-0'])>
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalOverdue')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Vencidos</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-danger'])>
                        R$ {{ \App\Helpers\FormatUtils::formatMoney($payableByStatus['totalOverdue']) }}
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
                    <p @class(['card-text', 'card-text-md'])>Vencem hoje</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-danger'])>
                        R$ {{ \App\Helpers\FormatUtils::formatMoney($payableByStatus['totalDueToday']) }}
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
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalUpcoming')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>A vencer</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-success'])>
                        R$ {{ \App\Helpers\FormatUtils::formatMoney($payableByStatus['totalUpcoming']) }}
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
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalPaid')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Pagos </p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-success'])>
                        R$ {{ \App\Helpers\FormatUtils::formatMoney($payableByStatus['totalPaid']) }}
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
            <div @class(['card', 'card-grid']) wire:click.prevent="setType('totalForPeriod')">
                <div @class(['card-body', 'text-center'])>
                    <p @class(['card-text', 'card-text-md'])>Total</p>
                    <p @class(['card-text', 'card-text-lg', 'reduce-margin', 'card-text-success'])>
                        R$ {{ \App\Helpers\FormatUtils::formatMoney($payableByStatus['totalForPeriod']) }}
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
    <div @class(['row', 'mt-2', 'mb-2'])>
        @include('components.layouts.forms.dropdowns.grid-lines')
        <div @class(['col', 'text-right'])>
            <div @class(['btn-group'])>
                <div @class(['btn-group', 'w-100'])>
                    <button wire:ignore
                            @class(['dropdown-toggle','btn', 'btn-sm', 'btn-success','mr-1', 'action-button'])
                            data-toggle="dropdown"
                            type="button"
                            style="display: none">
                    Ações
                    </button>
                    <div @class(['dropdown-menu'])>
                        <a wire:click="askMassDelete" @class(['dropdown-item']) href="#">Deletar</a>
                        <a wire:click="askMassPay" @class(['dropdown-item']) href="#">Pagar</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="selectedSum" wire:ignore class="col text-right">
        </div>
        <div id="totalSum" wire:ignore class="col text-right">
        </div>
    </div>
    <div @class(['row'])>
        <div @class(['col-12'])>
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
                    <th wire:click="sortBy('f_name')">Fornecedor
                            @if($sortField == 'f_name')
                                @if($sortDirection == 'desc')
                                    <i class="bi bi-chevron-up"></i>
                                @else
                                    <i class="bi bi-chevron-down"></i>
                                @endif
                            @else
                                <i class="bi bi-chevron-expand"></i>
                            @endif
                    </th>
                    <th wire:click="sortBy('vparcela')">Valor
                        @if($sortField == 'vparcela')
                            @if($sortDirection == 'desc')
                                <i class="bi bi-chevron-up"></i>
                            @else
                                <i class="bi bi-chevron-down"></i>
                            @endif
                        @else
                            <i class="bi bi-chevron-expand"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('pvv_dtv')">Vencimento
                        @if($sortField == 'pvv_dtv')
                            @if($sortDirection == 'desc')
                                <i class="bi bi-chevron-up"></i>
                            @else
                                <i class="bi bi-chevron-down"></i>
                            @endif
                        @else
                            <i class="bi bi-chevron-expand"></i>
                        @endif
                    </th>
                    <th>Status</th>
                    <th>Aprovações</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody @class(['rel-tb-claro', 'td-grid-font'])>
                @foreach($accountsPayable as $account)
                    <tr id="{{ $account->id }}{{ $account->pvv_id }}"
                        wire:click="showDetails({{ json_encode($account) }})"
                        data-toggle="modal" data-target="#modalShowAccount">
                        <td @class(['text-center'])>
                            <div onclick="event.stopPropagation()" class="custom-control custom-checkbox small">
                                <input wire:click="markIdToDeleteOrPay({{ $account->id }}, {{ $account->pvv_id }})" onclick="event.stopPropagation()"
                                       class="custom-control-input actions-check"
                                       type="checkbox" value="" data-id="{{ $account->id }}" data-pvv-id="{{ $account->pvv_id }}"
                                       id="chk_{{ $account->id }}{{ $account->pvv_id }}">
                                <label onclick="event.stopPropagation()" @class(['custom-control-label']) for="chk_{{ $account->id }}{{ $account->pvv_id }}"></label>
                            </div>
                        </td>
                        <td>{{ $account->trace_code }}</td>
                        <td>{{ $account->f_name ?? $account->dre_categoria }}</td>
                        <td @class(['installmentValue'])>R$ {{ \App\Helpers\FormatUtils::formatMoney($account->vparcela) }}</td>
                        <td>{{ \App\Helpers\FormatUtils::formatDate($account->pvv_dtv) }}</td>
                        <td>{{ $account->pago ? 'Pago' : 'Aberto' }}</td>
                        <td class="text-center">
                            {{ $approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))]['approved'] }}
                            /
                            {{ $approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))]['total'] }}
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{
                                $approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))]['percentual']
                                    }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </td>
                        <td>
                            <button data-toogle="tooltip" data-placement="top" title="Detalhes da conta"
                                @class(['btn btn-sm btn-success'])>
                                <i @class(['bi', 'bi-eye'])></i>
                            </button>
                            <a href='{{ route('approvals.index',[
                                    'processId' => $account->id,
                                     'pvvDtv' => $account->pvv_dtv])
                                     }}'
                               data-placement="top" title="Detalhes da conta" type="button"
                               @class(['btn', 'btn-sm', 'btn-success'])
                               onclick="event.stopPropagation()"
                            >
                                <i @class(['bi', 'bi-pencil'])></i>
                            </a>
                            @if($account->pago == 1)
                                <button disabled type="button" @class(['btn', 'btn-sm', 'btn-light'])
                                    data-toggle="tooltip" title="Pago">
                                    <i style="color: #1cc88a" @class(['bi', 'bi-currency-dollar'])></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div @class(['row'])>
        <div @class(['col-12'])>
            {{ $accountsPayable->links() }}
        </div>
    </div>
    @include('components.layouts.modals.modal-show-details-account')
    @include('livewire.payable.modal.add-account')
    @include('components.layouts.modals.modal-edit-installments')
    @include('components.layouts.modals.modal-edit-accounts')
    {{-- modal cadastro de fornecedores--}}
    @include('components.layouts.modals.supplier-add-modal')
    {{-- modal cadastro de categorias--}}
    @include('components.layouts.modals.modal-add-category')
    {{-- modal cadastro de tipos de cobrança--}}
    @include('components.layouts.modals.modal-add-billing-type')
    {{-- modal cadastro de centro de custo--}}
    @include('components.layouts.modals.modal-add-center-cost')
    {{-- modal de confirmacao de salvar sem arquivos --}}
    @include('components.layouts.modals.modal-save-without-files')
    {{-- modal de confirmacao de salvar sem arquivos --}}
    @include('components.layouts.modals.modal-save-paid-account')
    {{-- modal de confirmacao de salvar sem arquivos --}}
    @include('components.layouts.modals.modal-delete-installment')
    @include('components.layouts.modals.modal-delete-installment-warning')
    {{-- modal de confirmacao de salvar sem arquivos --}}
    @include('components.layouts.modals.modal-success-delete')
    {{-- modal de confirmacao de deletar parcelas ou contas sem arquivos --}}
    @include('components.layouts.modals.modal-delete-file')
    {{-- modal de confirmacao de deletar parcelas ou contas sem arquivos --}}
    @include('components.layouts.modals.modal-payment-account')
    {{-- modal para pagamentos de faturas --}}
    @include('components.layouts.modals.modal-update-payment')
    {{-- modal para alterar faturas pagas --}}
    @include('components.layouts.modals.modal-ask-set-to-open')
    {{-- modal para alterar faturas pagas --}}
    @include('components.layouts.modals.modal-ask-mass-delete')
    {{-- modal para excluir as faturas em massa --}}
    @include('components.layouts.modals.modal-add-bank')
    {{-- modal para excluir as faturas em massa --}}
    @include('components.layouts.modals.modal-mass-pay')
    {{-- modal para pagar as faturas em massa --}}'
    @include('components.layouts.modals.modal-apportionment')
    {{-- modal para adicionar rateios --}}
</div>
<script>
    document.addEventListener('livewire:init', function () {
        $('.selectValue').select2(
            {
                placeholder: '{{ isset($f_name) ? $f_name : 'Selecione um fornecedor' }}',
                dropdownCssClass: 'input-login',
                selectionCssClass: 'input-login',
                containerCssClass: 'input-login',

            }
        ).on('change', function (e) {
            const selectedOption = $(this).find('option:selected');
            @this.selectSupplier(selectedOption.val(), selectedOption.text());
        });
    });

    document.addEventListener('livewire:update', function () {
        $('.selectValue').select2({
            placeholder: '{{ isset($f_name) ? $f_name : 'Selecione um fornecedor' }}',
            allowClear: true,
            dropdownCssClass: 'input-login',
            selectionCssClass: 'input-login',
            containerCssClass: 'input-login',
        });
    });

    document.addEventListener('livewire:init', function () {
        $('.addSupplier').select2(
            {
                placeholder: 'Selecione um fornecedor' ,
                dropdownParent: $('#modalAddAccount'),
                dropdownCssClass: 'input-login',
                selectionCssClass: 'input-login',
                containerCssClass: 'input-login',
            }
        ).on('change', function (e) {
            const selectedOption = $(this).find('option:selected');
            @this.selectSupplier(selectedOption.val(), selectedOption.text());
        });

        addBtnToDropdown('addSupplier', 'Adicionar fornecedor', 'modalAddSupplier');
    });

    document.addEventListener('livewire:update', function () {
        $('.addSupplier').select2(
            {
                placeholder: 'Selecione um fornecedor' ,
                dropdownParent: $('#modalAddAccount'),
                dropdownCssClass: 'input-login',
                selectionCssClass: 'input-login',
                containerCssClass: 'input-login',
            }
        ).on('change', function (e) {
            const selectedOption = $(this).find('option:selected');
            @this.selectSupplier(selectedOption.val(), selectedOption.text());
        });
        addBtnToDropdown('addSupplier', 'Adicionar fornecedor', 'modalAddSupplier');
    });

    document.addEventListener('livewire:init', function () {
        $('.addDre').select2(
            {
                placeholder: 'Selecione uma categoria financeira',
                dropdownParent: $('#modalAddAccount'),
                dropdownCssClass: 'input-login',
                selectionCssClass: 'input-login',
                containerCssClass: 'input-login',
            }
        ).on('change', function (e) {
            const selectedOption = $(this).find('option:selected');
            @this.selectFinanceCategory(selectedOption.val(), selectedOption.text());
        });

        addBtnToDropdown('addDre', 'Adicionar categoria financeira', 'modalAddFinanceCategory');
    });

    document.addEventListener('livewire:update', function () {
        $('.addDre').select2(
            {
                placeholder: 'Selecione uma categoria financeira',
                dropdownParent: $('#modalAddAccount'),
                dropdownCssClass: 'input-login',
                selectionCssClass: 'input-login',
                containerCssClass: 'input-login',
            }
        );
    });

    document.addEventListener('livewire:init', function () {
        Livewire.on('dataSaved', (event) => {
            if(!event){
                $('#modalAddSupplier').modal('hide');
                $('#addAccount').click();
                $('#modalEditAccount').click;
                setTimeout(() => {
                    $('#addAccount').click();
                }, 1000);
            }else{
                $('#modalAddSupplier').modal('hide');
                $('#edit'+event[0]).click();
                setTimeout(() => {
                    $('#edit'+event[0]).click();
                }, 1000);
            }
        });
    });

    document.addEventListener('livewire:init', function () {
        Livewire.on('editPayment', (event) => {
            $('#modalUpdatePayment').modal('show');
        });
    });

    document.addEventListener('livewire:init', function () {
        Livewire.on('successPay', (event) => {
            console.log(event);
            $('#modalPayment').modal('hide');
            $('#modalShowAccount').modal('hide');
            $('#modalUpdatePayment').modal('hide');
        });
    });

    document.addEventListener('livewire:init', function () {
        Livewire.on('payAccount', (event) => {
            $('#modalPayment').modal('show');
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('deleteAccount', (event) => {
            $('#deleteInstallment').modal('show');
            console.log(event[0]);
            setTimeout(() => {
                $('span.delete-text').text(event[0]);
                if(event[1]){
                    $('button.btn-danger').attr('disabled', true);
                }
                console.log('lala');
            }, 500);
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('deleteInstallment', (event) => {
            console.log('teste');
            $('#deleteInstallmentWarning').modal('show');
            console.log(event[0]);
            setTimeout(() => {
                $('span.delete-text').text(event[0]);
                if(event[1]){
                    $('button.btn-danger').attr('disabled', true);
                }
                console.log('lala');
            }, 500);
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('closeModal', (event) => {
            $('#'+event[0]).modal('hide');
        });

        Livewire.on('showModal', (event) => {
            $('#'+event[0]).modal('show');
        });

        Livewire.on('clickRefresh', (event) => {
            setTimeout(() => {
                $('#'+event[0]+event[1]).click();
            }, 500);
        });

        $('.clearSupplier').on('click', function(){
            $('#cpfCnpj').val('');
        });

        Livewire.on('clearCheckboxes', (event) => {
            $('.actions-check').prop('checked', false);
            $('.action-button').hide();
            $('#chk_all').prop('checked', false);
            $('#selectedSum').empty();
        });

        Livewire.on('calculatePage', (event) =>{
            setTimeout(() => {
                var valueElement = document.getElementsByClassName('installmentValue');
                var totalSum = 0;

                Array.prototype.forEach.call(valueElement, function(element) {
                    var valueText = element.textContent || element.innerText;
                    var numericValue = parseFloat(valueText.replace('.', '').replace(',', '.').replace('R$', ''));
                    console.log(numericValue);
                    if (!isNaN(numericValue)) {
                        totalSum += numericValue;
                    }
                });
                $('#totalSum').html('<span class="card-text">Total da tabela abaixo: </span><span class="card-text card-text-success"><b>' + formatCurrency(totalSum) + ' </b></span>');
            }, 1000);
        });

        $('.actions-check').off('change').on('change', function() {
            checkIfAnyChecked();
            var checked = $(this).is(':checked');
            if(!checked){
                $('#chk_all').prop('checked', false);
            }
            var parentInstallmentValue = $(this).closest('tr').find('.installmentValue').text();
            var valueId = $(this).closest('tr').attr('id');
            var numericValue = parseFloat(parentInstallmentValue.replace('.', '').replace(',', '.').replace('R$', ''));
            @this.sumToDelete(valueId, numericValue);
        });

        // Função para verificar se algum checkbox está marcado
        function checkIfAnyChecked() {
            const isChecked = $('.actions-check').is(':checked');
            if (isChecked) {
                console.log('Pelo menos um checkbox foi marcado.');
                $('.action-button').show();
            } else {
                console.log('Nenhum checkbox está marcado.');
                $('.action-button').hide();
            }
            return isChecked;
        }

        Livewire.on('showSelectedValue', (event) => {
            console.log(event);
            if(event[0] > 0){
                $('#selectedSum').html('<span class="card-text">Total selecionado abaixo: </span><span class="card-text card-text-success"><b>' + formatCurrency(event[0]) + ' </b></span>');
            }else{
                $('#selectedSum').empty();
            }
        });

        $('#chk_all').on('click', function() {
            // Verifica o estado atual do checkbox de seleção total
            const isChecked = this.checked;
            let numericValue = 0;
            @this.rmToSum();

            // Altera o estado de todos os checkboxes individuais
            $('.actions-check').prop('checked', isChecked);

            // Itera sobre cada checkbox e aciona a função Livewire
            $('.actions-check').each(function() {
                const dataId = $(this).data('id');
                const dataPvvId = $(this).data('pvv-id');
                var parentInstallmentValue = $(this).closest('tr').find('.installmentValue').text();
                var valueId = $(this).closest('tr').attr('id');
                numericValue = parseFloat(parentInstallmentValue.replace('.', '').replace(',', '.').replace('R$', ''));
                console.log("valor da parcela "+numericValue);
                if (isChecked) {
                    $('.action-button').show();
                    @this.markIdToDeleteOrPay(dataId, dataPvvId);
                    @this.sumToDelete(valueId, numericValue);
                } else {
                    $('.action-button').hide();
                    @this.markIdToDeleteOrPay(dataId, dataPvvId);
                    @this.rmToSum();
                }
            });
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('deleteFile', (event) => {
            $('#modalAskDeleteFile').modal('show');
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('deletedFile', (event) => {
            let row = $('#row'+event[0]).val();
            if(row.length){
                row.remove();
            }
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('sendClick', (event) => {
            console.log('Aqui');
            setTimeout(() => {
                $(event[0]).click();
            }, 500);
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('successDelete', (event) => {
            $('#modalShowAccount').modal('hide');
            $('#successDelete').modal('show');
            setTimeout(() => {
                $('span.success-delete-text').text(event[0]);
                console.log('lala');
            }, 500);
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('editAccount', (event) => {
            $('#modalEditAccount').modal('show');
            console.log(event);
            let selectValue = $('.addSupplier');
            let option = new Option(event[1], event[0], true, true);
            selectValue.append(option).trigger('change');
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var parcelaElements = document.getElementsByClassName('parcela');

        Array.prototype.forEach.call(parcelaElements, function(element) {
            element.addEventListener('blur', function () {
            @this.calculateInstallments();
            });
        });

        Array.prototype.forEach.call(parcelaElements, function(element) {
            element.addEventListener('keyup', function () {
            @this.calculateInstallments();
            });
        });

        $('#condicaoSelect').on('change', function () {
            if($(this).val() == 'vista'){
                let valorTotal = $('#valor_total').val();
                $('#valorPrimeiraParcela').val(valorTotal);
                $('#parcela').val(0)
                @this.set('valueOfTheFirstInstallment', valorTotal);
            }
            if($(this).val() == 'prazo'){
                @this.calculateInstallments();
            }
        });

        var totalPayment = document.getElementsByClassName('totalPayment');

        Array.prototype.forEach.call(totalPayment, function(element) {
            element.addEventListener('blur', function () {
            @this.calculateAmountWithLateInterestAndDiscount();
            });

            element.addEventListener('keyup', function () {
            @this.calculateAmountWithLateInterestAndDiscount();
            });
        });

    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('saveWithoutfile', (event) => {
            $('#modalWithOutFile').modal('show');
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('paidAccount', (event) => {
            $('#modalPaidAccount').modal('show');
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('updatedAccount', (event) => {
            $('#modalEditAccount').modal('hide');
            $('#modalShowAccount').modal('hide');
            setTimeout(() => {
                $(`tr#${event[0]} td`).click();
                console.log('lala');
            }, 500);
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('saveAccount', (event) => {
            $('#modalAddAccount').modal('hide');
        });
    });

    // Função para rolar o modal para o topo
    document.addEventListener('DOMContentLoaded', function() {
        // Função para rolar o modal para o topo
        function scrollModalToTop() {
            $('.modal .modal-body').scrollTop(0);
        }

        // Associar o evento de rolar ao evento 'shown.bs.modal' do Bootstrap
        $(document).on('shown.bs.modal', function () {
            scrollModalToTop();
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('editInstallment', (event) => {
            $('#modalEditInstallment').modal('show');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        console.log('teste');
        $("div.contas-pagar").addClass('active-number');
        $("div.inicio").removeClass('active-number');
    });
</script>
