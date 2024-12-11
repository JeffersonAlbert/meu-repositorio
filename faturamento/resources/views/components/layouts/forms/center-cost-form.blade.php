<form wire:submit.prevent="saveCenterCost">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="centerCostName">Nome do centro de custo</label>
                <input type="text" class="form-control" id="centerCostName"
                       wire:model="centerCostName">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="centerCostDescription">Descrição do centro de custo</label>
            <textarea type="text" class="form-control" id="centerCostDescription"
                      wire:model="centerCostDescription"></textarea>
        </div>
    </div>
    <div class="row text-right mt-2 mb-2">
        <div class="col">
            <button type="submit" class="btn btn-success btn-sm">Salvar</button>
        </div>
    </div>
</form>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('dataSaved', (event) => {
            $('#modalCenterCost').modal('hide');
        });
    });
</script>
