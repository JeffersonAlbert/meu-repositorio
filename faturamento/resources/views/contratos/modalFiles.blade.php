<!-- Modal -->
<div class="modal fade" id="modalFiles" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Arquivos contratos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="messages-arquivos"></div>
                <table class="table table-responsive-xl">
                    <thead>
                        <th>File</th>
                        <th>Ações</th>
                    </thead>
                    <tbody id="tabela-corpo">
                        @forelse(json_decode($contrato->files)->files as $file)
                        <tr>
                            <td>{{ $file }}</td>
                            <td><a class="btn btn-success btn-sm baixar-arquivo" data-file="{{ $file }}">Baixar</a></td>
                        </tr>
                        @empty
                        <tr>Nada aqui</tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>
