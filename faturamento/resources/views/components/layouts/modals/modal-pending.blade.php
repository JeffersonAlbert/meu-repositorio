<div wire:ignore.self @class(['modal', 'fade']) id="modalPending"
     tabindex="-1" role="dialog" aria-labelledby="modalPendingLabel" aria-hidden="true"
     x-data="{ loading: false}">
    <div @class(['modal-dialog', 'modal-lg']) role="document">
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>Pendências do processo</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div @class(['modal-body'])>
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['alert', 'alert-warning'])>
                            <span class="font-extra light-dt">
                                Você está prestes a aprovar um registro com pendencias:<br>
                                {{date('d/m/Y H:i:s', strtotime($pendingDate)) }} {{ $pendingErrors }}.<br>
                                Tenha certeza de as corrigir antes de continuar!
                                Deseja continuar?.
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
                                <button type="button" class="btn btn-success" data-dismiss="modal" wire:click="saveCorrectedPendingIssue({{ $correctPendingGroup }})">
                                    Salvar
                                </button>
                            </div>
                            <div @class(['col'])>
                                <button type="button" class="btn btn-success" data-dismiss="modal">
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
