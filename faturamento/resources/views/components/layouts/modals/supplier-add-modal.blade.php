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
                        <button wire:click.prevent="clearSupplierForm" type="button" class="close closeModal clearSupplier" data-dismiss="" aria-label="Close">
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
                        @include('components.layouts.forms.supplier-form')
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
