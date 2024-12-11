<!-- modal para adicionar tipos de cobrancas -->
<div wire:ignore.self class="modal fade" id="modalApportionment" tabindex="-1" role="dialog"
     aria-labelledby="modalApportionmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalApportionmentLabel">Adicionar tipo cobrança</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('components.layouts.forms.apportionment-form')
            </div>
        </div>
    </div>
</div>
