<div wire:ignore.self class="modal fade" id="modalToReceive" tabindex="-1" role="dialog"
     aria-labelledby="modalToReceiveLabel" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header-number border border-success">
                <div @class(['row', 'mt-4', 'mb-4'])>
                    <div @class(['col-2'])></div>
                    <div @class(['col-4'])>
                            <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                            Recebimento {{ isset($paymentTraceCode) ? $paymentTraceCode : null }}
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
                @include('components.layouts.receivable.forms.form-payment-account')
            </div>
        </div>
    </div>
</div>
