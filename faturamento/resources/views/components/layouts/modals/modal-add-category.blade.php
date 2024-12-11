{{-- modal de inserção de categoria --}}
<div wire:ignore.self class="modal fade secondModal" id="modalAddFinanceCategory"
     tabindex="-1" role="dialog" aria-labelledby="modalAddFinanceCategoryLabel"
     aria-hidden="true" x-data="{ loading: false }">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                    Cadastrar categoria financeira
                </span>
                <button type="button" class="close closeModal" data-dismiss="" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div @class(['row'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col'])>
                        @include('components.layouts.forms.form-finance-category')
                    </div>
                    <div @class(['col-2'])></div>
                </div>
            </div>
        </div>
    </div>
</div>
