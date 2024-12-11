
<div wire:ignore>
    <select @class(['form-control', 'input-login', 'selectValue']) style="width:100%;">
        <option value="all">Todos</option>
        @foreach(\App\Models\Fornecedor::where('id_empresa', auth()
            ->user()->id_empresa)->orderBy('nome')->get() as $fornecedor)
            <option value="{{ $fornecedor['id'] }}">{{ $fornecedor['nome'] }}</option>
        @endforeach
    </select>
</div>
