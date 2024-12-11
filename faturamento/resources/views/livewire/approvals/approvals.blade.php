<div @class(['row'])>
    <div id="loader" class="loader"
         wire:loading wire:target="setPdf, approve, docUpdate, searchDreByString,
         searchBillingTypeByString, searchWorkflowByString, searchCenterCostByString,
         searchApportionmentByString, updateAccount, selectSupplier, searchSupplierCpfCnpj,
         saveSupplier, saveDRE">
        <div class="spinner-grow text-success" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>
    <div @class(['col-9'])>
        <div @class(['row'])>
            <div class="dropdown show">
                <a class="btn btn-search dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $this->fileName }}
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    @foreach($docList as $file)
                            <a wire:click="setPdf('{{$file}}')" href="#"
                                @class(['dropdown-item'])>
                                {{ $file }}
                            </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div @class(['row', 'mt-2'])>
            <iframe wire:ignore.self id="pdfIframe" src="" width="100%" height="920px" style="border: none;"></iframe>
            <div wire:ignore.self id="openseadragon1" style="width: 100%; height: 920px;" @if($mimeType == 'application/pdf') hidden @endif></div>
        </div>
        <div @class(['row', 'mt-3'])>
            @include('components.layouts.grafics.quartely-chart')
        </div>
    </div>
    <div @class(['col-3'])>
        <div @class(['row', 'ml-1','mt-5'])>
            <div @class(['card'])>
                <div @class(['card-body', 'mb-0'])>
                    <div @class(['card-body', 'reduce-margin-30'])>
                        <h5 @class(['card-title', 'card-text-md'])>Dados nota</h5>
                        <p @class(['card-text', 'card-text-md'])>Nº Nota: <b>{{ $notaFiscalNumber }}</b></p>
                        <p @class(['card-text','card-text-md'])>Vencimento: <b>{{ date('d/m/Y', strtotime($installmentDate)) }}</b></p>
                        <p @class(['card-text', 'card-text-md'])>Valor: <b>{{ $installmentValue }}</b></p>
                        <p @class(['card-text', 'card-text-md'])>Emissão: <b>{{ date('d/m/Y', strtotime($emissionDate)) }}</b></p>
                        <p @class(['card-text', 'card-text-md'])>Competência: <b>{{ date('d/m/Y', strtotime($competence)) }}</b></p>
                        <hr @class(['sidebar-divider'])>
                    </div>
                </div>
                @if($allApproved and auth()->user()->financeiro)
                    @if($pay == 0)
                        <div @class(['card-body', 'reduce-margin-30'])>
                            <h5 @class(['card-title', 'card-text-md'])>Nota aprovada</h5>
                            <hr @class(['sidebar-divider'])>
                            <button wire:click="payBilling" type="button" @class(['btn', 'btn-primary', 'w-100'])>
                                <i @class(['bi', 'bi-dollar'])></i>
                                Pagar
                            </button>
                            <hr @class(['sidebar-divider'])>
                        </div>
                    @else
                        <div @class(['card-body', 'reduce-margin-30'])>
                            <h5 @class(['card-title', 'card-text-md'])>Nota aprovada</h5>
                            <hr @class(['sidebar-divider'])>
                            <button wire:click="editPayment" type="button" @class(['btn', 'btn-primary', 'w-100'])>
                                <i @class(['bi', 'bi-dollar'])></i>
                                Editar pagamento
                            </button>
                            <hr @class(['sidebar-divider'])>
                        </div>
                    @endif
                @endif
                <div @class(['card-body', 'reduce-margin-30'])>
                    <h5 @class(['card-title', 'card-text-md'])>Ações</h5>
                    <div @class(['text-center'])>
                        <div class="row">
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button wire:click="showProcessComments" type="button" @class(['btn', 'btn-success'])
                                        data-toggle="tooltip" title="Adicionar comentário" data-placement="top">
                                    <i @class(['bi', 'bi-chat-text'])></i>
                                </button>
                            </div>
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button wire:click="showUploadFiles" type="button"
                                        @class(['btn', 'btn-success']) data-toggle="tooltip"
                                        title="Adicionar novo arquivo" data-placement="top">
                                    <i @class(['bi', 'bi-upload'])></i>
                                </button>
                            </div>
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button wire:click="showAddToDo" type="button" @class(['btn', 'btn-success']) data-toggle="tooltip"
                                        title="Adicionar pendência ao processo" data-placement="top"
                                        {{ $allApproved ? null : 'disabled' }} {{ $pay ? 'disabled' : null }}>
                                    <i @class(['bi', 'bi-arrow-return-left'])></i>
                                </button>
                            </div>
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button wire:click="showDeleteProcess" type="button" @class(['btn', 'btn-success'])
                                        data-toggle="tooltip" title="Remover processo" data-placement="top"
                                        {{ $allApproved ? 'disabled' : null }} {{ $pay ? 'disabled' : null }}>
                                    <i @class(['bi', 'bi-trash'])></i>
                                </button>
                            </div>
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button type="button" wire:click="showProcessHistory"
                                        @class(['btn', 'btn-success']) data-toggle="tooltip"
                                        title="Exibir histórico do processo" data-placement="top">
                                    <i @class(['bi', 'bi-clock'])></i>
                                </button>
                            </div>
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button type="button" wire:click="showEditAccount"
                                        @class(['btn', 'btn-success']) data-toggle="tooltip"
                                        title="Editar processo" data-placement="top"
                                {{ $pay ? 'disabled' : null }}>
                                    <i @class(['bi', 'bi-pen'])></i>
                                </button>
                            </div>
                            <div @class(['col-2', 'col-sm-12', 'col-md-3', 'mb-2'])>
                                <button type="button" wire:click="askSetToOpen({{$processId}}, {{$pvvId}})"
                                        @class(['btn', 'btn-success']) data-toggle="tooltip"
                                        title="Remover pagamento" data-placement="top"
                                {{ $pay ? null : 'disabled' }}>
                                    <i @class(['bi', 'bi-x'])></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr @class(['sidebar-divider'])>
                </div>
                <div @class(['card-body', 'reduce-margin-30'])>
                    <h5 @class(['card-title', 'card-text-md'])>Aprovações</h5>
                    <hr @class(['sidebar-divider'])>
                    @foreach($approvals as $approval)
                        @if($approval['approved'])
                            <button type="button" data-toggle="tooltip"
                                    title="Aprovado por {{ $approval['approvedBy'] }} em {{ date('d/m/Y', strtotime($approval['approvedAt'])) }}"
                                    data-placement="top"
                                    @class(['btn', 'btn-success', 'w-100']) disabled>
                                <i @class(['bi', 'bi-check'])></i>
                                {{ $approval['groupName'] }}
                            </button>
                            <hr @class(['sidebar-divider'])>
                        @elseif(!$approval['approved'] and $approval['canApprove'])
                            <button type="button" wire:click="approve({{ $approval['groupId'] }})"
                                    data-toggle="tooltip"
                                    title="Clique para aprovar"
                                    data-placement="top"
                                @class(['btn', 'btn-warning', 'w-100'])>
                                <i @class(['bi', 'bi-clock'])></i>
                                {{ $approval['groupName'] }}
                            </button>
                            @if(isset($errorMessages['invalidUser']))
                                <span class="text-danger font-extra-light-dt">{{ $errorMessages['invalidUser'][0] }}</span>
                            @endif
                            <hr @class(['sidebar-divider'])>
                        @else
                            <button type="button" data-toggle="tooltip"
                                    title="Aguardando aprovação"
                                    data-placement="top"
                                    @class(['btn', 'btn-secondary', 'w-100']) disabled>
                                <i @class(['bi', 'bi-clock'])></i>
                                {{ $approval['groupName'] }}
                            </button>
                            <hr @class(['sidebar-divider'])>
                        @endif
                    @endforeach
                </div>
                <div @class(['card-body', 'mb-0'])>
                    <h5 @class(['card-title', 'card-text-md'])>Dados do processo</h5>
                    <p @class(['card-text', 'card-text-md'])>Identificação: <b>{{ $traceCode }}</b></p>
                    <p @class(['card-text', 'card-text-md'])>Criado em: <b>{{ date('d/m/Y', strtotime($createdAt)) }}</b></p>
                    <p @class(['card-text', 'card-text-md'])>Por: <b>{{ $userCreated }}</b></p>
                    <hr @class(['sidebar-divider'])>
                </div>
                <div @class(['card-body', 'reduce-margin-30'])>
                    <h5 @class(['card-title', 'card-text-md'])>Fornecedor</h5>
                    <p @class(['card-text', 'card-text-md'])>Documento: <b>{{ \App\Helpers\FormatUtils::formatDoc($supplierCNPJ) }}</b></p>
                    <p @class(['card-text', 'card-text-md'])>nome: <b>{{$supplierName}}</b></p>
                    <hr @class(['sidebar-divider'])>
                </div>
                <div @class(['card-body', 'reduce-margin-30'])>
                    <h5 @class(['card-title', 'card-text-md'])>Informação de pagamento</h5>
                    @if($pay)
                        <p @class(['card-text', 'card-text-md'])>Data: <b>{{ date('d/m/Y', strtotime($paymentDate)) }}</b></p>
                        <p @class(['card-text', 'card-text-md'])>Valor: <b>{{ $paymentValue }}</b></p>
                        <p @class(['card-text', 'card-text-md'])>Conta: <b>{{ $bankName }}</b></p>
                        <p @class(['card-text', 'card-text-md'])>Forma de pagamento: <b>{{ $paymentMethodName }}</b></p>
                    @else
                        <p @class(['card-text', 'card-text-md'])><b>Fatura ainda em aberto</b></p>
                    @endif
                </div>
                <div @class(['card-body', 'reduce-margin-30'])>
                    <h5 @class(['card-title', 'card-text-md'])>Observação</h5>
                    <p @class(['card-text', 'card-text-md'])><b>{{$observation}}</b></p>
                </div>
            </div>
        </div>
        @include('components.layouts.modals.modal-payment-account')
        @include('components.layouts.modals.modal-update-payment')
        @include('components.layouts.modals.modal-process-comments')
        @include('components.layouts.modals.modal-upload-files')
        @include('components.layouts.modals.modal-add-to-do')
        @include('components.layouts.modals.modal-pending')
        @include('components.layouts.modals.modal-delete-process')
        @include('components.layouts.modals.modal-process-history')
        @include('components.layouts.modals.modal-edit-accounts')
        @include('components.layouts.modals.modal-ask-set-to-open')
        @include('components.layouts.modals.modal-add-bank')
        @include('components.layouts.modals.modal-delete-file')
    </div>
    <script>

        document.addEventListener('livewire:init', function () {
            Livewire.on('docUpdated', (event) => {
                console.log(event);
                setTimeout(() => {
                    if(event[1] === 'application/pdf') {
                        const pdfPath = '{{ route('r2.img', ['any' => '__DOC__']) }}'.replace('__DOC__', event[0]);
                        console.log(pdfPath);
                        const viewerUrl = '{{ asset("js/pdfjs/web/viewer.html") }}?file=' + encodeURIComponent(pdfPath);
                        console.log(viewerUrl);
                        document.getElementById('pdfIframe').src = viewerUrl;
                        document.getElementById('pdfIframe').hidden = false;
                        document.getElementById('openseadragon1').hidden = true;
                    }else{
                        OpenSeadragon({
                            id: 'openseadragon1',
                            prefixUrl: "{{ asset('js/openseadragon/images/') }}/",
                            tileSources: {
                                type: 'image',
                                url: '{{ route('r2.img', ['any' => '__DOC__']) }}'.replace('__DOC__', event[0])
                            }
                        });
                        document.getElementById('pdfIframe').hidden = true;
                        document.getElementById('openseadragon1').hidden = false;
                    }
                }, 1000);
            });
        });

        document.addEventListener('livewire:init', function () {
            Livewire.on('payAccount', (event) => {
                $('#modalPayment').modal('show');
            });

            /*var totalPayment = document.getElementsByClassName('totalPayment');

            Array.prototype.forEach.call(totalPayment, function(element) {
                element.addEventListener('blur', function () {
                @this.calculateAmountWithLateInterestAndDiscount();
                });

                element.addEventListener('keyup', function () {
                @this.calculateAmountWithLateInterestAndDiscount();
                });
            });*/

            $('.totalPayment').on('blur', function() {
                console.log('calculando blur');
                @this.calculateAmountWithLateInterestAndDiscount();
            });


        });

        document.addEventListener('livewire:init', function () {
            Livewire.on('editPayment', (event) => {
                $('#modalUpdatePayment').modal('show');
            });
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('closeModal', (event) => {
                $('#'+event[0]).modal('hide');
            });

            Livewire.on('showModal', (event) => {
                $('#'+event[0]).modal('show');
            });

            function renderChart(){
                let expenses = dataPointsFormat(@json($expensesHistoryQuartely));
                let paidExpenses = dataPointsFormat(@json($paidExpensesHistoryQuartely));
                var options = {
                    animationEnabled: true,
                    theme: "light2",
                    height: 250,
                    dataPointMaxWidth: 50,
                    dataPointMinWidth: 20,
                    axisX: {
                        valueFormatString: "MMM"
                    },
                    axisY: {
                        prefix: "R$",
                        labelFormatter: addSymbols
                    },
                    legend: {
                        cursor: "pointer",
                        itemclick: toggleDataSeries
                    },
                    toolTip: {
                        shared: true
                    },
                    data: [
                        {
                            // Change type to "doughnut", "line", "splineArea", etc.
                            type: "column",
                            name: "Total despesa",
                            xValueFormatString: "MMMM YYYY",
                            yValueFormatString: "R$ #.##",
                            showInLegend: true,
                            color: "#d35c7a",
                            dataPoints: expenses,
                        },
                        {
                            // Change type to "doughnut", "line", "splineArea", etc.
                            type: "column",
                            name: "Pago",
                            xValueFormatString: "MMMM YYYY",
                            yValueFormatString: "R$ #.##",
                            showInLegend: true,
                            color: "#1cc88a",
                            dataPoints: paidExpenses,
                        }
                    ]
                };
                $("#expensesHistory").CanvasJSChart(options);
            }

            renderChart();

            Livewire.on('renderChart', (event) => {
                setTimeout(() => {
                    renderChart();
                }, 2000);
                //renderChart();
            });

            $('document').ready(function (){

            });

            $('.textAreaComment').on('input', function() {
                $('.charCount').text($(this).val().length + ' caracteres');
                if($(this).val().length > 20){
                    $('.charCount').removeClass('text-danger');
                    $('.charCount').addClass('text-success');
                    $('.sendComment').prop('disabled', false);
                }else{
                    $('.charCount').removeClass('text-success');
                    $('.charCount').addClass('text-danger');
                    $('.sendComment').prop('disabled', true);
                }
            });
        });
    </script>
</div>
