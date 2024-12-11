<div wire:ignore.self class="modal fade" id="modalAddBank"
     tabindex="-1" role="dialog"
     aria-labelledby="modalAddBankLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddBankLabel">Adicionar novo banco</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" wire:submit="saveBank">
                    @include('components.layouts.forms.add-bank-form')
                </form>
            </div>
        </div>
    </div>
</div>
