{{-- modal info de salvar sem arquivo --}}
<div wire:ignore.self class="modal fade secondModal" id="successDelete"
     tabindex="-1" role="dialog" aria-labelledby="successDeleteLabel"
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
                            <span class="font-extra light-dt success-delete-text">

                            </span>
                        </div>
                    </div>
                    <div @class(['col-2'])></div>
                </div>
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        <div @class(['row', 'text-right'])>
                            <div @class(['col'])>
                                <button wire:click="saveWithoutFiles" type="button" class="btn btn-success" data-dismiss="modal">
                                    Fechar
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
