<div class="form-row">
    <div class="col-12">
        <label for="bankName">Nome do banco</label>
        <input type="text" class="input-login form-control" id="bankName" wire:model="bankName">
        @error('bankName') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
<div class="form-row">
    <div class="col-12">
        <label for="bankAgency">Agência</label>
        <input type="text" class="input-login form-control" id="bankAgency" wire:model="bankAgency">
        @error('bankAgency') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
<div class="form-row">
    <div class="col-12">
        <label for="bankAccount">Conta</label>
        <input type="text" class="input-login form-control" id="bankAccount" wire:model="bankAccount">
        @error('bankAccount') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
<div class="form-row text-right">
    <div class="col">
        <button type="button" class="btn btn-secondary mt-3" data-dismiss="modal">Cancelar</button>
    </div>
    <div class="col">
        <button type="submit" class="btn btn-success mt-3">Adicionar</button>
    </div>
</div>
