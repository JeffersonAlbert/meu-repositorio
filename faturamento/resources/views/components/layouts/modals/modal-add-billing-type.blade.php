<!-- modal para adicionar tipos de cobrancas -->
<div wire:ignore.self class="modal fade" id="modalBillingType" tabindex="-1" role="dialog"
     aria-labelledby="modalBillingTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBillingTypeLabel">Adicionar tipo cobrança</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               @include('components.layouts.forms.billing-type-form')
            </div>
        </div>
    </div>
</div>
