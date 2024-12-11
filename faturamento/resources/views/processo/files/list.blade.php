<div class="col-12">
    <table class="table table-responsive-sm table-head-number table-bordered">
        <thead class="head-grid-data">
            <th>Arquivo</th>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Ações</th>
        </thead>
        <tbody class="rel-tb-claro">
        @foreach(json_decode($processo->files_types_desc) as $key => $filesTypesDesc)
            <tr>
                <td class="td-grid-font align-middle name-file">{{ $filesTypesDesc->fileName }}</td>
                <td class="td-grid-font align-middle type-file">{{ ucfirst(str_replace('_', ' ', $filesTypesDesc->fileType)) }}</td>
                <td class="td-grid-font align-middle desc-file">{{ $filesTypesDesc->fileDesc }}</td>
                <td class="td-grid-font align-middle">
                    <a href='{{ asset("uploads/$filesTypesDesc->fileName") }}' target="_blank" id="vizualizarArquivo" class="btn-sm btn-success btn">
                        <i class="bi bi-eye"></i>
                    </a>
                    <button id="removerArquivo" data-id="{{ $processo->id}}" class="btn-sm btn-danger btn removerArquivo">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    <table>
</div>
