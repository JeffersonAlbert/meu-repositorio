<div @class(['card', 'border', 'border-success'])>
            <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
                Anexos
            </span>
    <div @class(['card-body'])>
        <div @class(['row', 'mt-1'])>
            <div @class(['col'])>
                <label @class(['label-number']) for="arquivo">Arquivo</label>
                <input name="arquivo" type="file" class="input-login form-control" wire:model="accountFiles.0">
                @if(isset($errorMessages['emptyFile']))
                    <span class="text-danger font-extra-light-dt">{{ is_string($errorMessages['emptyFile'][0]) ? $errorMessages['emptyFile'][0] : $errorMessages['emptyFile'][0][0] }}</span>
                @endif
            </div>
            <div @class(['col'])>
                <label @class(['label-number']) for="descricao">Descrição</label>
                <input name="descricao" type="text" class="input-login form-control" placeholder="" wire:model="accountFilesDescription.0">
            </div>
            <div @class(['col'])>
                <label @class(['label-number']) for="data">Tipo</label>
                <select name="data" class="input-login form-control" wire:model="accountFilesType.0">
                    <option value="">Selecione o tipo do documento</option>
                    <option value="contrato">Contrato</option>
                    <option value="documento_fiscal">Documento fiscal</option>
                    <option value="documento_cobranca">Documento de cobrança</option>
                    <option value="comprovante_pagamento">Comprovante de pagamento</option>
                    <option value="outro">Outro</option>
                </select>
                @if(isset($errorMessages['emptyFileType']))
                    <span class="text-danger font-extra-light-dt">{{ is_string($errorMessages['emptyFileType'][0]) ? $errorMessages['emptyFileType'][0] : $errorMessages['emptyFileType'][0][0] }}</span>
                @endif
            </div>
            <div @class(['col-1'])>
                <label @class(['label-number']) for="addInput" class="label-number">Adicionar</label>
                <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="addInput">
                    <i @class(['bi', 'bi-plus'])></i>
                </button>
            </div>
        </div>
        @foreach($inputs as $index => $input)
            <div @class(['row', 'mt-1'])>
                <div @class(['col'])>
                    <label @class(['label-number']) for="arquivo">Arquivo</label>
                    <input name="arquivo[]" type="file" class="input-login form-control" wire:model.defer="accountFiles.{{ $index+1 }}">
                    @if(isset($errorMessages['emptyFile']))
                        <span class="text-danger font-extra-light-dt">{{ $errorMessages['emptyFile'][$index+1][0] ?? null }}</span>
                    @endif
                </div>
                <div @class(['col'])>
                    <label @class(['label-number']) for="descricao">Descrição</label>
                    <input name="descricao" type="text" class="input-login form-control" placeholder="" wire:model="accountFilesDescription.{{ $index+1 }}">
                </div>
                <div @class(['col'])>
                    <label @class(['label-number']) for="data">Tipo</label>
                    <select name="data" class="input-login form-control" wire:model="accountFilesType.{{ $index+1 }}">
                        <option value="">Selecione o tipo do documento</option>
                        <option value="contrato">Contrato</option>
                        <option value="documento_fiscal">Documento fiscal</option>
                        <option value="documento_cobranca">Documento de cobrança</option>
                        <option value="comprovante_pagamento">Comprovante de pagamento</option>
                        <option value="outro">Outro</option>
                    </select>
                    @if(isset($errorMessages['emptyFileType'][$index+1]))
                        <span class="text-danger font-extra-light-dt">{{ $errorMessages['emptyFileType'][$index+1][0] ?? null }}</span>
                    @endif
                </div>
                <div @class(['col-1'])>
                    <label @class(['label-number']) for="removeInput" class="label-number">Adicionar</label>
                    <button type="button" @class(['btn', 'btn-md', 'btn-success']) wire:click="removeInput({{ $index }})">
                        <i @class(['bi', 'bi-x'])></i>
                    </button>
                </div>
            </div>
        @endforeach
        <div @class(['row', 'mt-3'])>
            <div @class(['col', 'text-right'])>
                <button type="submit" @class(['btn', 'btn-sm', 'btn-success'])>
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>
