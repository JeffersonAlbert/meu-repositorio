
<div wire:ignore>
    <select @class(['form-control', 'input-login', 'selectValue']) style="width:100%;">
        <option value="all">Todos</option>
        @foreach(\App\Models\Clientes::where('id_empresa', auth()
            ->user()->id_empresa)->orderBy('nome')->get() as $clientes)
            <option value="{{ $clientes['id'] }}">{{ $clientes['nome'] }}</option>
        @endforeach
    </select>
</div>
