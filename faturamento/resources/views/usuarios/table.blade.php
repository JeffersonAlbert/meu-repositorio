<div class="row mb-3">
    <div class="col-9"></div>
    <div class="col-3 text-right">
        <a href="{{ route("usuarios.create") }}" class="btn btn-sm btn-success btn-success-number">
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
                    <label class="form-check-label" for="checkboxUsuario">Usuário</label>
                </div>
            </th>
            <th scope="col">Email</th>
            <th scope="col">Ultima edição</th>
            <th scope="col">Filiais</th>
            <th scope="col">Ações</th>
        </thead>
        <tbody class="rel-tb-claro">
            @foreach($users as $user)
            <tr class="text-center td-grid-font align-middle">
                <th>{{ $user->name }} {{ $user->last_name }}</th>
                <th>{{ $user->email }}</th>
                <th>{{ date('d/m/Y H:i:s', strtotime($user->updated_at)) }}</th>
                <th>{{ $user->filial_nome }}</th>
                <th>
                    @if($user->enabled)
                    <button class="btn btn-sm btn-danger openConfirmModalDisable" data-id="{{ $user->id }}">Desabilitar</button>
                    @else
                    <button class="btn btn-sm btn-success-number openConfirmModalEnable" data-id="{{ $user->id }}">Habilitar</button>
                    @endif
                    <button onclick="window.location.href='{{ route('usuarios.edit', ['usuario' => $user->id]) }}'" class="btn btn-sm btn-success btn-success-number">Editar</button>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('usuarios.modalConfirmaDesativarUsuario')
