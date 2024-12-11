
<form wire:submit.prevent="saveDRE">
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <input type="hidden" value='receita' name='tipo'>
    <div class="row">
        <div class="col">
            <label class="label-number" for='nome'>Descrição</label>
            <input wire:model="dreDescription"  name='nome' type="text" class="input-login form-control"
                   placeholder="{{ isset($dre->nome) ? $dre->nome : null }}">
        </div>
    </div>
    <div @class(['row', 'mt-1'])>
        <div class="col">
            <label class="label-number" for='dre-pai'>Aparecer em:</label>
            <select wire:model="dreCategory" id="dre-pai" name='dre-pai'
                @class(['input-login', 'select-number', 'form-control', 'form-select'])>
                <option value="{{ $dreType }}">{{ ucfirst($dreType) }}</option>
                @foreach(\App\Models\DRE::where('tipo', $dreType)->get() as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mt-3 row mt-1">
        <div class="col">
            <label class="label-number" for='vinculo-dre'>Vincular a DRE</label>
            <select wire:model="dreBond" id="vinculo-dre" name="vinculo-dre" class="input-login select-number form-control form-select">
                @foreach(\App\Models\VinculoDre::where('tipo', 'todos')
                    ->orWhere('tipo', $dreType)->get() as $vinculo)
                    <option value='{{ $vinculo->id }}'>{{ $vinculo->descricao }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div @class(['row', 'mt-3', 'mb-1', 'text-right'])>
        <div @class(['col'])>
            <button type="submit" wire:loading wire:loading.target="saveDRE"
                    wire:loading.class="btn btn-success btn-sm opacity-50"
                    wire:loading.attr="disabled">
                Salvando...
            </button>
            <button wire:loading.remove
                    type="submit" class="btn btn-success btn-sm">
                Salvar
            </button>
        </div>
    </div>
</form>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('dataSaved', (event) => {
            $('#modalAddFinanceCategory').modal('hide');
        });
    });
</script>


