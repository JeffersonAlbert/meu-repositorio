<script>
$(document).ready(function(){

    $('#enviarDREReceita').on('click', function(){
        let dadosFormulario = $('#formAddDRE').serialize();
        let route = "{{ route('dre.store') }}";
        $.ajax({
            type: 'POST',
            data: dadosFormulario,
            url: route,
            success: function(data){
                console.log(data);
                window.location.href = "{{ route('dre.index') }}";
            },
            error: function(xhr, status, error){
                console.log(error);
            }
        });
    });

    $('#enviarDREDespesa').on('click', function(){
        console.log('clickou');
        let dadosFormulario = $('#formAddDREDespesa').serialize();
        let route = "{{ route('dre.store') }}";
        $.ajax({
            type: 'POST',
            data: dadosFormulario,
            url: route,
            success: function(data){
                console.log(data);
                window.location.href = "{{ route('dre.index') }}";
            },
            error: function(xhr, status, error){
                console.log(error);
            }
        });
    });

    $('.editarDespesa').on('click', function(){
        console.log('editar despesa');
        let textoSubCategoria = $(this).closest('.row.ml-3').find('.sub_categoria')
            .text().trim().replace(/^-\s*/, '');
        let id = $(this).data('id');
        console.log(id);
        $('#descricaoDespesa').val(textoSubCategoria);
        pegaDrePai(id);
        $('#tipo').val('despesa');
        $('#receitaDespesaId').val(id);
        $('#editDespesa').modal();
    });

    $('.editarReceita').on('click', function(){
        console.log('editar receita');
        let textoSubCategoria = $(this).closest('.row.ml-3').find('.sub_categoria')
            .text().trim().replace(/^-\s*/, '');
        let id = $(this).data('id');
        console.log(id);
        $('#descricaoDespesa').val(textoSubCategoria);
        pegaDrePai(id);
        $('#tipo').val('receita');
        $('#receitaDespesaId').val(id);
        $('#editDespesa').modal();
    });

    function pegaDrePai(id){
        let route = "{{ route('dre.edit', ':id') }}";
        $.get(route.replace(':id', id), function(data){
           $('#dre-pai-edit').empty();
           let dre_id = data.registro.dre_id;
           tipo = data.registro.tipo == 'despesa' ? 'Despesas' : 'Receitas';
           $('#dre-pai-edit').append(`<option value=${dre_id}>${data.registro.dre_pai}</option>
               <option value=${data.registro.tipo}>${tipo}</option>`);
           $.each(data.dre, function(key, value){
                $('#dre-pai-edit').append(`<option value=${value.id}>${value.nome}</option>`);
           });
           $('#vinculo-dre-edit').empty();
           console.log(data.registro.vinculo_id)
           $('#vinculo-dre-edit').append(`<option value=${data.registro.vinculo_id}>${data.registro.vinculo_desc}</option>`);
           $.each(data.vinculo, function(vinculoKey, vinculoValue){
               $('#vinculo-dre-edit').append(`<option value=${vinculoValue.id}>${vinculoValue.descricao}</option>`);
           });
        });
    }

    $('.editarReceita').on('click', function(){
        console.log('editar receita');
        $('#editReceita').modal
        $('#decricaoReceita').val();
    });

    $('#enviarEdicao').on('click', function(){
        console.log('teste de envio de edicao de despesa');
        dadosFormulario = $('#formEditDRE').serialize();
        var id = $('#receitaDespesaId').val();
        let route = "{{ route('dre.update', ['dre' => ':id']) }}";
        $.ajax({
            type: 'POST',
            data: dadosFormulario,
            url: route.replace(':id', id),
            success: function(data){
                console.log(data);
                window.location.href = "{{ route('dre.index') }}";
            },
            error: function(xhr, status, error){
                console.log(error);
            }
        });
    });
});
</script>
