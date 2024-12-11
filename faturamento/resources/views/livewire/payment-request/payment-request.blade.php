<div>
    <div id="loader" class="loader" wire:loading
         wire:loading.target="searchSupplierCpfCnpj, saveSupplier, saveAccount, updateAccount">
        <div class="spinner-grow text-success" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>
    <div @class(['row'])>
        <div @class(['col-12'])>
            <div @class(['titulo-grid-number', 'font-regular-wt'])>
                <h3>Solicitação de pagamentos</h3>
            </div>
        </div>
    </div>

    <!-- Adicione o switch de tema aqui -->
    <div @class(['row', 'mt-3'])>
        <div @class(['col-12'])>
            @include('layout.switch-dt-wt') <!-- Inclusão do botão de troca de tema -->
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
                <input wire:change="setMyApproval" type="checkbox" class="custom-control-input" id="switch-approved" value="totalOverdue">
                <label class="custom-control-label" for="switch-approved">Precisa da minha aprovação <span class="badge badge-success">{{ $this->totalNeedsMyApproval }}</span></label>
            </div>
        </div>
        <div @class(['col'])>
            <div class="custom-control custom-switch">
                <input wire:ignore wire:change="setPendent" type="checkbox" class="custom-control-input" id="switch-pendent" value="totalOverdue">
                <label class="custom-control-label" for="switch-pendent">Apenas pendentes <span class="badge badge-success">{{ $this->totalPendent }}</span></label>
            </div>
        </div>
        <div @class(['col'])>
            <div class="custom-control custom-switch">
                <input wire:ignore wire:change="setPaid" type="checkbox" class="custom-control-input" id="switch-paid" value="totalOverdue">
                <label class="custom-control-label" for="switch-paid">Apenas pagos</label>
            </div>
        </div>
        <div @class(['col'])>
            <div class="custom-control custom-switch">
                <input wire:ignore wire:change="setOnlyOpened" type="checkbox" class="custom-control-input custom-checkbox" id="switch-opened" value="totalOverdue">
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
                        {{ $payableByStatus['totalOverdue'] }}
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
                        {{ $payableByStatus['totalDueToday'] }}
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
                        {{ $payableByStatus['totalDue'] }}
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
                        {{ $payableByStatus['totalApproved'] }}
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
                        {{ $payableByStatus['total'] }}
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
                    <th scope="col">Identificação</th>
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
                    <th scope="col">Nº Nota:</th>
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
                    <th scope="col">Aprovações</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>
                <tbody @class(['rel-tb-claro', 'td-grid-font'])>
                @foreach($requestPayments as $paymentRequest)
                    <tr id="{{ $paymentRequest->id }}{{ date('Y-m-d', strtotime($paymentRequest->pvv_dtv)) }}"
                        wire:click="showDetails({{ json_encode($paymentRequest) }})"
                        data-toggle="modal" data-target="#modalShowAccount">
                        <td @class(['text-center'])>
                            <div onclick="event.stopPropagation()" class="custom-control custom-checkbox small">
                                <input wire:click="markIdToDeleteOrPay({{ $paymentRequest->id }}, {{ $paymentRequest->pvv_id  }})" onclick="event.stopPropagation()"
                                       class="custom-control-input actions-check"
                                       type="checkbox" value="" data-id="{{ $paymentRequest->id }}" id="chk_{{ $paymentRequest->id }}{{ $paymentRequest->pvv_id }}">
                                <label onclick="event.stopPropagation()" @class(['custom-control-label']) for="chk_{{ $paymentRequest->id }}{{ $paymentRequest->pvv_id }}"></label>
                            </div>
                        </td>
                        <td>{{ $paymentRequest->trace_code }}</td>
                        <td>{{ $paymentRequest->f_name ?? $paymentRequest->sub_categoria_dre }}</td>
                        <td>{{ $paymentRequest->num_nota }}</td>
                        <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($paymentRequest->vparcela) }}</td>
                        <td>{{ \App\Helpers\FormatUtils::formatDate($paymentRequest->pvv_dtv) }}</td>
                        <td class="text-center">
                            {{ $approval_progress_request_payment[$paymentRequest->id." ".date('Y-m-d', strtotime($paymentRequest->pvv_dtv))]['approved'] }}
                            /
                            {{ $approval_progress_request_payment[$paymentRequest->id." ".date('Y-m-d', strtotime($paymentRequest->pvv_dtv))]['total'] }}
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{
                                $approval_progress_request_payment[$paymentRequest->id." ".date('Y-m-d', strtotime($paymentRequest->pvv_dtv))]['percentual']
                                    }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href='{{ route('approvals.index',[
                                    'processId' => $paymentRequest->id,
                                     'pvvDtv' => $paymentRequest->pvv_dtv])
                                     }}'
                               data-placement="top" title="Detalhes da conta" type="button"
                               @class(['btn', 'btn-sm', 'btn-success'])
                               onclick="event.stopPropagation()"
                            >
                                <i @class(['bi', 'bi-pencil'])></i>
                            </a>
                            @if($paymentRequest->pago == 1)
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
    <div @class(['row', 'mt-3'])>
        <div @class(['col'])>
            {{ $requestPayments->links() }}
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
    @include('components.layouts.modals.modal-ask-mass-delete')
    {{-- modal para excluir as faturas em massa --}}
    @include('components.layouts.modals.modal-add-bank')
    {{-- modal para excluir as faturas em massa --}}
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

    document.addEventListener('DOMContentLoaded', function() {
        console.log('teste');
        $("div.solicitacao").addClass('active-number');
        $("div.inicio").removeClass('active-number');
    });

    document.addEventListener('livewire:init',() => {
        Livewire.on('editAccount', (event) => {
            $('#modalEditAccount').modal('show');
        });

        Livewire.on('closeModal', (event) => {
            $('#'+event).modal('hide');
        });

        Livewire.on('showModal', (event) => {
            $('#'+event[0]).modal('show');
        });

        Livewire.on('deleteFile', (event) => {
            $('#modalAskDeleteFile').modal('show');
        });

        Livewire.on('clearCheckboxes', (event) => {
            $('.actions-check').prop('checked', false);
            $('.action-button').hide();
            $('#chk_all').prop('checked', false);
        });



        $('.actions-check').off('change').on('change', function() {
            checkIfAnyChecked();
        });

        $('#switch-paid').on('change', function() {
            const isChecked = this.checked;
            if(isChecked) {
                console.log('aqui');
                $('#switch-opened').prop('disabled', true);
                $('#switch-pendent').prop('disabled', true);
            }else{
                $('#switch-opened').prop('disabled', false);
                $('#switch-pendent').prop('disabled', false);
            }
        });

        $('#switch-opened').on('change', function() {
            const isChecked = this.checked;
            if(isChecked) {
                $('#switch-paid').prop('disabled', true);
                $('#switch-pendent').prop('disabled', true);
            }else{
                $('#switch-paid').prop('disabled', false);
                $('#switch-pendent').prop('disabled', false);
            }
        });

        $('#switch-pendent').on('change', function() {
            const isChecked = this.checked;
            if(isChecked) {
                $('#switch-paid').prop('disabled', true);
                $('#switch-opened').prop('disabled', true);
            }else{
                $('#switch-paid').prop('disabled', false);
                $('#switch-opened').prop('disabled', false);
            }
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

        $('#chk_all').on('click', function() {
            // Verifica o estado atual do checkbox de seleção total
            const isChecked = this.checked;

            // Altera o estado de todos os checkboxes individuais
            $('.actions-check').prop('checked', isChecked);

            // Itera sobre cada checkbox e aciona a função Livewire
            $('.actions-check').each(function() {
                const dataId = $(this).data('id');
                if (isChecked) {
                    $('.action-button').show();
                @this.markIdToDeleteOrPay(dataId); // Marca para deletar
                } else {
                    $('.action-button').hide();
                @this.markIdToDeleteOrPay(dataId); // Desmarca para deletar (você precisará criar essa função no Livewire)
                }
            });
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('saveWithoutfile', (event) => {
            $('#modalWithOutFile').modal('show');
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('sendClick', (event) => {
            setTimeout(() => {
                $(event[0]).click();
            }, 500);
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

    document.addEventListener('DOMContentLoaded', function () {
        var parcelaElements = document.getElementsByClassName('parcela');

        Array.prototype.forEach.call(parcelaElements, function(element) {
            element.addEventListener('blur', function () {
            @this.calculateInstallments();
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var parcelaElements = document.getElementsByClassName('parcela');

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
    });
</script>
