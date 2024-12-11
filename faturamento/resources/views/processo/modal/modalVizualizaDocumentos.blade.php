<div class="modal" tabindex="-1" id="modalDocumentosOriginais" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m-0 font-weight-bold text-primary font-size-18px">Vizualizar arquivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive-sm table-head-number table-bordered"
                    style="overflow-x: auto;">
                    <thead class="head-grid-data">
                        <th scope="col">
                            Nome Arquivo
                        </th>
                        <th scope="col">Ação</th>
                    </thead>
                    <tbody class="rel-tb-claro">
                        @foreach ($originalDocs as $doc)
                            <tr>
                                <td
                                    style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $doc }}</td>
                                <td><a target="_blank" href='{{ route('r2.img', ['any' => "uploads/$doc"]) }}'
                                        class="btn btn-success btn-success-number">Ver</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary_01 btn-back-number" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
