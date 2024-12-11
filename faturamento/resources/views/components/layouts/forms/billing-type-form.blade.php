<form wire:submit.prevent="saveBillingType" class="form">
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" class="form-control" id="name" wire:model="billingTypeName">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col', 'text-right'])>
            <button type="submit" class="btn btn-success btn-sm">Salvar</button>
        </div>
    </div>
</form>
