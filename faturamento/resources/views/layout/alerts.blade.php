<script>
function formatarData(data) {
  const meses = [
    "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
    "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
  ];

  const partes = data.split(/[-T:]/); // Divide a data em partes

  const dia = partes[2];
  const mes = meses[parseInt(partes[1]) - 1]; // Subtrai 1 para corresponder ao índice do array
  const ano = partes[0];

  return `${dia} ${mes} de ${ano}`;
}

$(document).ready(function(){
    $('.dropdown-list').on('click', function(e) {
        e.stopPropagation(); // Impede que o dropdown seja fechado quando seu conteúdo é clicado
    });

    // Exemplo de evento de clique em um botão para fechar o dropdown
    $('.close-dropdown').on('click', function(e) {
        e.stopPropagation(); // Impede que o dropdown seja fechado quando o "X" é clicado
         $('.dropdown').toggleClass('show');
    });

    $.ajax({
        url: "{{ route('messages.alerts') }}",
        type: "POST",
        data:{ _token: "{{ csrf_token() }}" },
        success: function(response) {
            $(".badge-alert").text(response.length);
            $(".dropdown-list .custom-alert").remove();
            let dataHtml = "";
            $.each(response, function(field, data){
                let dataFormatada = formatarData(data.created_at);
                dataHtml += `<a class="dropdown-item custom-alert d-flex align-items-center" data-id="${data.id}" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-danger">
                                        <i class="bi bi-envelope-exclamation-fill text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">${dataFormatada}</div>
                                    <div>
                                        <span class="font-weight-bold">${data.id}: ${data.message}</span>
                                    </div>
                                     <div class="icon-circle bg-success check">
                                        <i class="bi bi-check-circle-fill text-white"></i>
                                    </div>
                                </div>
                            </a>`;
            });
            console.log(response);
            $(".custom-dropdown h6").after(dataHtml);
        }
    });

     // Adicione um evento de clique para o ícone de check
    $('.dropdown-menu').on('click', '.check', function (e) {
        e.preventDefault();

        // Obtenha o data-id do item clicado
        var itemId = $(this).closest('.dropdown-item').data('id');
        var $item = $(this).closest('.dropdown-item');

        // Envie uma solicitação AJAX para marcar o item como lido
        $.ajax({
            url: "{{route('messages.read')}}", // Substitua pela URL adequada
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: itemId,
            },
            success: function (response) {
                // O item foi marcado como lido com sucesso
                // Você pode atualizar a interface de usuário conforme necessário
                $item.remove();

            },
            error: function (error) {
                console.error('Erro ao marcar como lido:', error);
            }
        });
    });
});
</script>
