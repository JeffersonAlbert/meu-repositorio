<div x-data="{ open: false }" @click.away="open = false"
    @class(['dropdown'])>
    <button @click="open = !open"
        @class(['dropdown-number', 'btn', 'dropdown-toggle',
            'col-12', 'btn-transparent'])
            type="button" id="dropdownCobrancaButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="open">
        {{isset($workflowName) ? $workflowName : 'Selecione um workflow'}}
    </button>
    <div wire:ignore.self x-show="open"
         @class(['dropdown-menu', 'p-2', 'col'])
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownCobrancaButton">
        <div x-data="{ workflow: @entangle('queryWorkflow'), pageWorkflow: @entangle('pageWorkflowSearch') }"
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input x-model="workflow"
                   x-on:input.debounce.500ms="$wire.searchWorkflowByString()"
                   type="text" id="dropdown-workflow-input"
                   class="form-control"
                   placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-workflow-items">
            @foreach($workflowList as $workflow)
                <a href="#" data-id="{{ $workflow['id']}}"
                   wire:click.prevent="selectWorkflow({{ $workflow['id'] }}, '{{ $workflow['nome'] }}');
                   open = false;"
                   class="dropdown-item">
                    {{ $workflow['nome'] }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <a href="{{ route('workflow.index') }}" type="button"
               id="add-workflow-btn" class="btn btn-sm btn-success w-100">
                Adicionar
            </a>
        </div>
    </div>
</div>
<input type="hidden" wire:model="workflowId" val="{{ isset($workflowId) ? $workflowId : null }}" id="workflow">
