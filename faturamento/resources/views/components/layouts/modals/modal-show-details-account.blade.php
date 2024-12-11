<!-- Modal -->
<div wire:ignore.self  class="modal fade" id="modalShowAccount" tabindex="-1"
     role="dialog" aria-labelledby="modalShowAccountLabel" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header-number border border-success">
                <div @class(['row', 'mt-4', 'mb-4'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col-4', 'text-left', 'bg-white'])>
                            <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                                Detalhes Conta {{ $trace_code }}
                            </span>
                    </div>
                    <div @class(['col-4', 'bg-white'])>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
            <div class="modal-body font-regular-wt">
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['card', 'border', 'border-success'])>
                            <div @class(['card-header', 'bg-white'])>
                                <div @class(['row'])>
                                    <div @class(['col'])>
                                        <span>Informações gerais</span>
                                    </div>
                                </div>
                            </div>
                            <div @class(['card-body'])>
                                <div @class(['row', 'mt-2'])>
                                    <div @class(['col'])>
                                <span>Fornecedor:
                                    @if($f_name !== null)
                                        <a href='#'> {{ $f_name }} </a>
                                    @else
                                        {{  $dre_categoria }}
                                    @endif
                                </span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Competencia: {{ \App\Helpers\FormatUtils::formatDate($competencia) }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Categoria: {{ $dre_categoria }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Centro de custo: {{ $centro_custo }}</span>
                                    </div>
                                </div>
                                <div @class(['row', 'mt-4'])>
                                    <div @class(['col'])>
                                        <span>Vencimento: {{ \App\Helpers\FormatUtils::formatDate($pvv_dtv) }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Tipo de cobrança: {{ $tipo_cobranca }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Filial: {{ $filial_nome }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Rateio: {{ $rateio }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div @class(['card', 'border', 'border-success', 'mt-2'])>
                            <div @class(['card-header', 'bg-white'])>
                                <div @class(['row'])>
                                    <div @class(['col'])>
                                        <span>Informações sobre aprovação</span>
                                    </div>
                                </div>
                            </div>
                            <div @class('card-body')>
                                <div @class(['row', 'mt-4'])>
                                    <div @class(['col'])>
                                        <span>Progresso de aprovação</span>
                                        @foreach($approvedBy as $key => $value)
                                            <div @class(['row', 'mt-2'])>
                                                <span>{{$value->group_name}}: {{ $value->user_name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div @class(['col', 'text-center'])>
                                        {{ $approval_progress[$id." ".date('Y-m-d', strtotime($pvv_dtv))]['approved'] ?? 0 }}
                                        /
                                        {{ $approval_progress[$id." ".date('Y-m-d', strtotime($pvv_dtv))]['total'] ?? 0}}
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{
                                $approval_progress[$id." ".date('Y-m-d', strtotime($pvv_dtv))]['percentual'] ?? 0
                                    }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div @class(['card', 'border', 'border-success', 'mt-2'])>
                            <div @class(['card-header', 'bg-white'])>
                                <div @class(['row'])>
                                    <div @class(['col'])>
                                        <span>Informações das faturas</span>
                                    </div>
                                </div>
                            </div>
                            <div @class(['card-body'])>
                                <div @class(['row', 'mt-4'])>
                                    <div @class(['col', 'text-right', 'titulo-grid-number', 'font-regular-wt'])>
                                        <span>Valor Total: {{ \App\Helpers\FormatUtils::formatMoney($valor) }}</span>
                                    </div>
                                </div>
                                <div @class(['row', 'mt-4'])>
                                    <div @class(['col'])>
                                        <div @class(['row', 'p-1'])>
                                            <table @class(['table', 'table-responsive-sm', 'table-head-number', 'table-hover'])>
                                                <thead @class(['head-grid-data'])>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Forma pagamento</th>
                                                    <th>Conta</th>
                                                    <th>Valor R$</th>
                                                    <th>Juros/Multa R$</th>
                                                    <th>Desconto R$</th>
                                                    <th @class(['text-center'])>Situacao</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody @class(['rel-tb-claro', 'td-grid-font'])>
                                                @if(isset($parcelas))
                                                    @foreach($parcelas as $parcela)
                                                        <tr>
                                                            <td>{{ \App\Helpers\FormatUtils::formatDate($parcela->pvv_dtv) }}</td>
                                                            <td>{{ $parcela->forma_pagamento_nome }}</td>
                                                            <td>{{ $parcela->banco }}</td>
                                                            <td>{{ \App\Helpers\FormatUtils::formatMoney($parcela->vparcela) }}</td>
                                                            <td>{{ \App\Helpers\FormatUtils::formatMoney($parcela->juros+$parcela->multas) }}</td>
                                                            <td>{{ \App\Helpers\FormatUtils::formatMoney($parcela->descontos) }}</td>
                                                            <td @class(['text-center'])><span class="{{ $parcela->pago ? 'text-shadow-success-box' : 'text-shadow-alert-box' }}">{{ $parcela->pago ? 'Pago' : 'Aberto' }}</span></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    @if($page !== 'payment-request')
                                                                    <div class="btn-group w-100">
                                                                        <button id='button-pai' type="button" @class(["dropdownPeriodoButton", "btn", "btn-success", "dropdown-toggle"]) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Ações
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            @if(!$parcela->aprovado)
                                                                            @elseif($parcela->pago and $parcela->aprovado)
                                                                                <a class="dropdown-item"  wire:click.prevent="editPaymentDetail({{ json_encode($parcela) }})" href="#">Editar pagamento</a>
                                                                            @else
                                                                                <a class="dropdown-item"  wire:click.prevent="payAccountDetail({{ json_encode($parcela) }})" href="#">Pagar</a>
                                                                            @endif
                                                                            <a class="dropdown-item"  wire:click.prevent="deleteWarning(
                                                                                '{{ $parcela->id }}',
                                                                                '{{ $parcela->pvv_id }}',
                                                                                '{{ $parcela->pago }}',
                                                                                '{{ $parcela->valor }}',
                                                                                '{{ $parcela->vparcela }}',
                                                                                {{ json_encode($parcelas) }})" href="#">Deletar
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="dropdown-divider mt-2"></div>
                                        <div @class(['row', 'mt-4', 'border', 'border-success'])>
                                            <div @class(['col', 'text-right'])>
                                                <div @class(['row'])>
                                                    <div @class(['col'])></div>
                                                    <div @class(['col'])>
                                                        <span>Valor em aberto R$</span>
                                                        <p><span @class('text-danger')><b>{{ isset($valorPagar) ? \App\Helpers\FormatUtils::formatMoney($valorPagar) : '0,00' }}</b></span></p>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>Valor pago R$</span>
                                                        <p><span @class('text-success')><b>{{ isset($valorPago) ? \App\Helpers\FormatUtils::formatMoney(($valorPago+$jurosPago+$multasPago-$descontosPago)) : '0,00' }}</b></span></p>
                                                    </div>
                                                </div>
                                                <div @class(['row'])>
                                                    <div @class(['col'])>
                                                        <span>Parcelas R$:</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ isset($valorPagar) ? \App\Helpers\FormatUtils::formatMoney($valorPagar) : '0,00' }}</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ isset($valorPago) ? \App\Helpers\FormatUtils::formatMoney($valorPago) : '0,00' }}</span>
                                                    </div>
                                                </div>
                                                <div @class(['row'])>
                                                    <div @class(['col'])>
                                                        <span>Descontos R$:</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>0,00</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ isset($valorPago) ? \App\Helpers\FormatUtils::formatMoney($descontosPago) : '0,00' }}</span>
                                                    </div>
                                                </div>
                                                <div @class(['row'])>
                                                    <div @class(['col'])>
                                                        <span>Juros R$:</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>0,00</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ isset($valorPago) ? \App\Helpers\FormatUtils::formatMoney($jurosPago) : '0,00' }}</span>
                                                    </div>
                                                </div>
                                                <div @class(['row'])>
                                                    <div @class(['col'])>
                                                        <span>Multas R$:</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>0,00</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ isset($valorPago) ? \App\Helpers\FormatUtils::formatMoney($multasPago) : '0,00' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div @class(['card', 'border', 'border-success', 'mt-2'])>
                            <div @class(['card-header', 'bg-white'])>
                                <div @class(['row'])>
                                    <div @class(['col'])>
                                        <span>Observações</span>
                                    </div>
                                </div>
                            </div>
                            <div @class(['card-body'])>
                                <div @class(['row'])>
                                    <div @class(['col'])>
                                        <textarea @class(['form-control', 'input-number']) disabled>{{ $observacao }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div @class(['card', 'border', 'border-success', 'mt-2'])>
                            <div @class(['card-header', 'bg-white'])>
                                <div @class(['row'])>
                                    <div @class(['col'])>
                                        <span>Arquivos anexados</span>
                                    </div>
                                </div>
                            </div>
                            <div @class(['card-body'])>
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
                                        @if(isset($files_type_description) and (!is_null($files_type_description)))
                                            @if(($files_type_description !== 'null'))
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
                                            @endif
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
            <div class="modal-footer-number">
                <div @class(['row', 'mt-1'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col', 'text-right'])>
                        @if($page !== 'payment-request')
                            @if($aprovado and !$pago)
                            <button type="button" class="btn btn-success" wire:click="payBilling">Pagar</button>
                            @elseif($aprovado and $pago)
                            <button type="button" class="btn btn-success" wire:click="editPayment">Editar pagamento</button>
                            @else
                            <button data-toggle="tooltip" data-placement="top"
                                    title="Faltam aprovações para pagar esse processo"
                                    type="button" class="btn btn-success" wire:click="payBilling" disabled>
                                Pagar
                            </button>
                            @endif
                            @include('components.layouts.forms.dropdowns.payable-account-actions-dropdown')
                        @else
                            <a id="edit{{ $id }}" class="btn btn-success"
                               wire:click.prevent="edit('{{ $id }}')"
                               href="#">
                                Editar conta
                            </a>
                            <a id="editInstallments{{ $id }}" data-toggle="modal"
                               data-target="#modalEditInstallment"
                               @class(['btn', 'btn-success']) href="#">
                                Editar parcelas
                            </a>
                            <button type="button" class="btn btn-success" wire:click="redirectToDesktop('{{ $id }}', '{{ $pvv_dtv }}')">Aprovações</button>
                        @endif
                        <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
