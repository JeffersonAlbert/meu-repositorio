<div wire:ignore.self @class(['modal', 'fade']) id="modalUploadFiles" tabindex="-1" role="dialog"
     aria-labelledby="modalUploadFilesLabel" aria-hidden="true">
    <div @class(['modal-dialog', 'modal-lg']) role="document">
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>Comentário do Processo</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div @class(['modal-body'])>
                <form wire:submit.prevent="uploadFiles"
                      enctype="multipart/form-data">
                    @include('components.layouts.forms.upload-files-form')
                </form>
            </div>
        </div>
    </div>
</div>
