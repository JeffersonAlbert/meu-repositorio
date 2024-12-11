<div @class(['row'])>
    <div @class(['col'])>
        <label @class(['label-number'])>Comentário</label>
        <textarea id="textAreaComment" wire:model="addToDoComment"
                  @class(['input-login', 'textAreaComment']) rows="5"></textarea>
        <div class="text-danger font-extra-light-dt charCount">0 caracteres</div>
        @if(isset($errorMessages['emptyComment']))
            <span @class(['text-danger'])>{{ $errorMessages['emptyComment'][0] }}</span>
        @endif
    </div>
</div>
<div @class(['row', 'mt-2'])>
    <div @class(['col', 'text-right'])>
        <button type="submit" @class(['btn', 'btn-sm', 'btn-success', 'sendComment']) disabled>
            Salvar
        </button>
    </div>
</div>
<div @class(['row', 'mt-3', 'w-100'])>
    <div @class(['col', 'w-100']) style="max-height: 400px; overflow-y: auto;">
        <table @class(['table', 'table-responsive-sm', 'table-head-number', 'table-hover', 'w-100'])>
            <thead @class(['head-grid-data'])>
                <tr>
                    <th>Data</th>
                    <th>Comentário</th>
                    <th>Usuário</th>
                </tr>
            </thead>
            <tbody @class(['rel-tb-claro' ,'td-grid-font'])>
            @forelse($processAddToDo as $toDo)
                <tr>
                    <td @class(['text-center'])>{{ date('d/m/Y H:i:s', strtotime($toDo['created_at'])) }}</td>
                    <td @class(['text-center'])>{{ $toDo['observacao'] }}</td>
                    <td @class(['text-center'])>{{ $toDo['user_name'] }}</td>
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
