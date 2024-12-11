<div wire:ignore.self @class(['modal', 'fade']) id="modalAddToDo" tabindex="-1" role="dialog"
     aria-labelledby="modalAddToDoLabel" aria-hidden="true">
    <div @class(['modal-dialog', 'modal-lg']) role="document">
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>Adicionar pendência ao processo</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div @class(['modal-body'])>
                <form wire:submit.prevent="saveAddToDo"
                      enctype="multipart/form-data">
                    @include('components.layouts.forms.add-to-do-form')
                </form>
            </div>
        </div>
    </div>
</div>
