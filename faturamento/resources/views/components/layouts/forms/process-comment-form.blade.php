<div @class(['row'])>
    <div @class(['col'])>
        <label @class(['label-number'])>Comentário</label>
        <textarea id="textAreaComment" wire:model="processComment"
                  @class(['input-login', 'textAreaComment']) rows="5"></textarea>
        <div class="text-danger font-extra-light-dt charCount">0 caracteres</div>
        @if(isset($errorMessages['emptyComment']))
            <span @class(['text-danger'])>{{ $errorMessages['emptyComment'][0] }}</span>
        @endif
    </div>
</div>
<div @class(['row', 'mt-3'])>
    <div @class(['col-10'])>
        <div @class(['dropdown', 'show'])>
            <button @class(['btn', 'btn-secondary', 'btn-sm', 'dropdown-toggle']) type="button"
                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$this->selectedUser}}
            </button>
            <div @class(['dropdown-menu']) aria-labelledby="dropdownMenuButton">
                <a wire:click="selectUser(0, 'Usuários')" @class(['dropdown-item'])>Todos</a>
                @foreach($usersList as $user)
                    <a wire:click="selectUser({{ $user['id'] }}, '{{ $user['name'] }}')" @class(['dropdown-item'])>{{ $user['name'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div @class(['col'])>
        <button id="sendComment" type="submit" @class(['btn', 'btn-success', 'btn-sm', 'sendComment']) disabled>
            Salvar
        </button>
    </div>
</div>
<div @class(['row', 'mt-3', 'w-100'])>
    <div @class(['col', 'w-100']) style="max-height: 400px; overflow-y: auto;">
        <table @class(['table', 'table-responsive-sm', 'table-head-number', 'table-hover', 'w-100'])>
            <thead @class(['head-grid-data'])>
            <tr>
                <th wire:click="sortDirection" @class(['text-center'])>
                    Data
                    @if($commentSortDirection == 'desc')
                        <i class="bi bi-chevron-up"></i>
                    @else
                        <i class="bi bi-chevron-down"></i>
                    @endif
                </th>
                <th @class(['text-center'])>Comentário</th>
                <th @class(['text-center'])>Usuário</th>
            </tr>
            </thead>
            <tbody @class(['rel-tb-claro' ,'td-grid-font'])>
            @forelse($processObservation as  $observation)
                <tr>
                    <td @class(['text-center'])>{{ date('d/m/Y H:i:s', strtotime($observation['created_at'])) }}</td>
                    <td @class(['text-center'])>{{ $observation['observacao'] }}</td>
                    <td @class(['text-center'])>{{ $observation['user_name'] }}</td>
                </tr>
            @empty
                <tr>
                    <td @class(['text-center']) colspan="3">Nenhum comentário cadastrado</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
