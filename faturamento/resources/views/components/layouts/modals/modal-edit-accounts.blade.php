<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalEditAccount" tabindex="-1" role="dialog"
     aria-labelledby="modalEditAccountLabel" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header-number">
                <div @class(['row', 'mt-4', 'mb-4'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col-4'])>
                            <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                            Editar conta a pagar {{ isset($newTraceCode) ? $newTraceCode : $trace_code }}
                            </span>
                    </div>
                    <div @class(['col-4'])>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form wire:ignore.self wire:submit.prevent="updateAccount" style="height: 100%"
                      enctype="multipart/form-data">
                @include('components.layouts.forms.account-form')
                </form>
            </div>
        </div>
    </div>
</div>
{{-- modal cadastro de fornecedores--}}
@include('components.layouts.modals.supplier-add-modal')
{{-- modal cadastro de categorias--}}
@include('components.layouts.modals.modal-add-category')
{{-- modal cadastro de tipos de cobrança--}}
@include('components.layouts.modals.modal-add-billing-type')
{{-- modal cadastro de centro de custo--}}
@include('components.layouts.modals.modal-add-center-cost')
{{-- modal de confirmacao de salvar sem arquivos --}}
@include('components.layouts.modals.modal-save-without-files')
{{-- modal de confirmacao de salvar sem arquivos --}}
@include('components.layouts.modals.modal-save-paid-account')
{{-- modal de confirmacao de salvar sem arquivos --}}

