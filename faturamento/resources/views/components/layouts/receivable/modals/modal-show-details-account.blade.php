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
                                Detalhes Conta {{ $traceCode }}
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
                                <span>Cliente:
                                    @if($detailClientName !== null)
                                        <a href='#'> {{ $detailClientName }} </a>
                                    @else
                                        {{  $detailDreCategory }}
                                    @endif
                                </span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Competencia: {{ \App\Helpers\FormatUtils::formatDate($detailCompetence) }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Categoria: {{ $detailDreCategory }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Centro de custo: {{ $detailCenterCost }}</span>
                                    </div>
                                </div>
                                <div @class(['row', 'mt-4'])>
                                    <div @class(['col'])>
                                        <span>Vencimento: {{ \App\Helpers\FormatUtils::formatDate($detailVencimento) }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Tipo de cobrança: {{ $detailBillingType }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Filial: {{ $detailBranch }}</span>
                                    </div>
                                    <div @class(['col'])>
                                        <span>Rateio: {{ $detailRateio }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div @class(['card', 'border', 'border-success', 'mt-2'])>
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
                        </div> --}}
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
                                        <span>Valor Total: {{ \App\Helpers\FormatUtils::formatMoney($detailValorTotal) }}</span>
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
                                                @if(isset($detailParcelas))
                                                    @foreach($detailParcelas as $key => $parcela)
                                                        <tr>
                                                            <td>{{ \App\Helpers\FormatUtils::formatDate($parcela->vencimento) }}</td>
                                                            <td>Forma de pagamento</td>
                                                            <td>Banco</td>
                                                            <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($parcela->valor) }}</td>
                                                            <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($parcela->juros + $parcela->multa) }}</td>
                                                            <td>R$ {{ \App\Helpers\FormatUtils::formatMoney($parcela->desconto) }}</td>
                                                            <td @class(['text-center'])>
                                                                <span class="{{ $parcela->status ? 'text-shadow-success-box' : 'text-shadow-alert-box' }}">{{ $parcela->status ? 'Pago' : 'Aberto' }}</span>
                                                            </td>
                                                            <td></td>
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
                                                        <p><span @class('text-danger')><b>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalAberto) }}</b></span></p>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>Valor pago R$</span>
                                                        <p><span @class('text-success')><b>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalPago) }}</b></span></p>
                                                    </div>
                                                </div>
                                                <div @class(['row'])>
                                                    <div @class(['col'])>
                                                        <span>Parcelas R$:</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalAberto) }}</span>
                                                    </div>
                                                    <div @class(['col'])>
                                                        <span>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalParcela) }}</span>
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
                                                        <span>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalDesconto) }}</span>
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
                                                        <span>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalJuros) }}</span>
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
                                                        <span>{{ \App\Helpers\FormatUtils::formatMoney($detailTotalMulta) }}</span>
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
                                        <textarea @class(['form-control', 'input-number']) disabled>{{ $detailObservation }}</textarea>
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
                                        @if(isset($detailFiles) and (!is_null($detailFiles)))
                                            @if(($detailFiles !== 'null'))
                                                @forelse($detailFiles as $key => $file)
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
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Nenhum arquivo anexado</td>
                                                    </tr>
                                                @endforelse
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
                        <button type="button" class="btn btn-success" wire:click="toEdit({{ $detailId }})">Editar</button>
                        <button type="button" class="btn btn-success" wire:click="toReceive({{ $detailId }})">Receber</button>
                        <button type="button" class="btn btn-success" wire:click="delete({{ $detailId }})">Deletar</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
