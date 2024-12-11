<!-- Modal -->
<div wire:ignore.self class="modal fade secondModal" id="modalAddSupplier"
     tabindex="-1" role="dialog" aria-labelledby="modalAddSupplierLabel"
     aria-hidden="true" x-data="{ loading: false }">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div @class(['row', 'bg-white','border','border-success'])>
                <div @class(['col-2'])></div>
                <div @class(['col-8'])>
                    <div class="modal-header">
                        <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                            Adicionar fornecedor
                        </span>
                        <button type="button" class="close closeModal" data-dismiss="" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div @class(['col-2'])>
                </div>
            </div>
            <div class="modal-body">
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        @include('form-parts.html.fornecedor-form')
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
