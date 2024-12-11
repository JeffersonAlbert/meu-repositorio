{{-- modal info de salvar sem arquivo --}}
<div wire:ignore.self class="modal fade secondModal" id="modalMassDelete"
     tabindex="-1" role="dialog" aria-labelledby="modalMassDeleteLabel"
     aria-hidden="true" x-data="{ loading: false }">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                    Aviso!
                </span>
                <button type="button" class="close closeModal" data-dismiss="" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['alert', 'alert-warning'])>
                            <span class="font-extra light-dt delete-text">
                                @forelse($processToDelete as $key => $process)
                                    <span>
                                        @if($process[0]->pago == 1)
                                            O processo {{ $process[0]->trace_code }} já
                                            foi pago, para excluir terá de definir como em aberto no detalhes da conta.
                                        @else
                                            Tem certeza que deseja excluir o processo
                                         {{ $process[0]->trace_code }}.
                                        @endif
                                    </span><br><br>
                                @empty
                                    Nenhum processo selecionado.
                                @endforelse
                            </span>
                        </div>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['row'])>
                            <div @class(['col'])>
                                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="massDelete">
                                    Deseja continuar?
                                </button>
                            </div>
                            <div @class(['col'])>
                                <button type="button" class="btn btn-success" wire:click="cancelMassDelete" data-dismiss="modal">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
