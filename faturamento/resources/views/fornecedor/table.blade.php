<div class="row mb-3">
    <div class="col-9"></div>
    <div class="col-3 text-right">
        <a href="{{ route("fornecedor.create") }}" class="btn btn-sm btn-success btn-success-number">
            <i class="bi bi-plus"></i>
            Novo
        </a>
    </div>
</div>
<div class="row">
    <table class="table table-responsive-sm table-head-number table-bordered">
        <thead class="head-grid-data" style="background-color: #e9e9e9">
            <th scope="col">
                <div class="form-check align-middle">
                    <input class="form-check-input" type="checkbox" id="checkboxUsuario">
                    <label class="form-check-label" for="checkboxUsuario">Fornecedor</label>
                </div>
            </th>
            <th scope="col">Documento</th>
            <th scope="col">Telefone</th>
            <th scope="col">Ultima edição</th>
            <th scope="col">Ações</th>
        </thead>
        <tbody class="rel-tb-claro">
            @foreach($fornecedores as $fornecedor)
            <tr class="text-center td-grid-font align-middle">
                <td>{{ $fornecedor->nome }}</td>
                <td>{{ $fornecedor->cpf_cnpj }}</td>
                <td>{{ $fornecedor->telefone }}</td>
                <th>{{ date('d/m/Y H:i:s', strtotime($fornecedor->updated_at)) }}</th>
                <th>
                    <button onclick="window.location.href='{{ route('fornecedor.edit', ['fornecedor' => $fornecedor->id]) }}'" class="btn btn-sm btn-success btn-success-number">Editar</button>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('usuarios.modalConfirmaDesativarUsuario')
