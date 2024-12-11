<form wire:submit.prevent="saveRateio">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="apportionmentName">Nome do rateio</label>
                <input type="text" class="form-control" id="apportionmentName"
                       wire:model="apportionmentName">
            </div>
        </div>
    </div>
    <div class="row">
       <div class="col">
           <label for="centroCusto">Centro de custo</label>
           @include('components.layouts.forms.dropdowns.centers-cost-dropdown')
       </div>
        <div class="col-2">
            <label for="percent">% Percentual</label>
            <input type="text" class="form-control" id="percent"
                   wire:model="apportionmentPercent">
        </div>
        <div class="col-1">
            <label for="addApportionment" class="label-number">Adicionar</label>
            <button wire:click="addInput" type="button" class="btn btn-md btn-success">
                <i class="bi bi-plus"></i>
            </button>
        </div>
        <input type="hidden" wire:model="apportionmentInputs">
    </div>
    @foreach($inputs as $index => $input)
        <div class="row">
            <div class="col">
                <label for="centroCusto">Centro de custo</label>
                @include('components.layouts.forms.dropdowns.centers-cost-dropdown')
            </div>
            <div class="col-2">
                <label for="percent">% Percentual</label>
                <input type="text" class="form-control" id="percent"
                       wire:model="apportionmentPercent.{{$index}}">
            </div>
            <div class="col-1">
                <label for="addApportionment" class="label-number">Remover</label>
                <button wire:click="removeInput({{$index}})" type="button" class="btn btn-md btn-success">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <input type="hidden" wire:model="apportionmentInputs.{{$index}}">
        </div>
    @endforeach
    <div class="row text-right mt-2 mb-2">
        <div class="col">
            <button type="submit" class="btn btn-success btn-sm">Salvar</button>
        </div>
    </div>
</form>
