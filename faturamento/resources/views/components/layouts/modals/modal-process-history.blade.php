<div wire:ignore.self class="modal fade secondModal" id="modalProcessHistory"
     tabindex="-1" role="dialog" aria-labelledby="modalProcessHistoryLabel"
     aria-hidden="true" x-data="{ loading: false }">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span @class(['titulo-grid-number', 'font-regular-wt', 'text-center'])>
                    Historico de açoes do processo!
                </span>
                <button type="button" class="close closeModal" data-dismiss="" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div @class(['row', 'mt-2', 'mb-2'])>
                    <div @class(['col'])>
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
                <div @class(['row'])>
                    <div @class(['col'])>
                        <table @class(['table', 'table-responsive-sm', 'table-head-number',
                            'table-hover', 'w-100'])>
                            <thead @class(['head-grid-data'])>
                                <tr>
                                    <th wire:click="sortDirection">
                                        Data
                                        @if($commentSortDirection == 'desc')
                                            <i class="bi bi-chevron-up"></i>
                                        @else
                                            <i class="bi bi-chevron-down"></i>
                                        @endif
                                    </th>
                                    <th>Ação</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody @class(['rel-tb-claro', 'td-grid-font'])>
                            @forelse($processHistrory as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $history->acao }}</td>
                                    <td>{{ $history->user_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Nenhum registro encontrado</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div @class(['row'])>
                    <div @class(['col'])>
                        <div @class(['row'])>
                            <div @class(['col'])>
                                {{ $processHistrory->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div @class(['row'])>
                    <div @class(['col'])>
                        <div @class(['row'])>
                            <div @class(['col'])>
                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
