<script>
// Função que será chamada quando qualquer botão for clicado
function onClickBotao(event) {
  const loader = $('#loader');

  // Exibir o loader
  loader.show();

  // Simular o carregamento com um atraso (apenas para exibir o loader)
  setTimeout(function () {
    // Coloque aqui o código que você deseja executar após o carregamento
    // Por exemplo, você pode fazer uma requisição Ajax ou redirecionar para outra página

    // Após a conclusão do carregamento, esconda o loader
    loader.hide();
  }, 1000); // Ajuste o tempo de atraso para exibir o loader conforme necessário
}

// Adicione o evento de clique a todos os botões da página
function showLoader() {
  $('#loader').show();
}

function hideLoader() {
  setTimeout(function() {
    $('#loader').hide();
  }, 1000); // 1000 milissegundos = 1 segundo
}

async function getSubDreName(subDre) {
    return new Promise((resolve, reject) => {
        $.post("{{ route('autocomplete.get-sub-dre-name') }}", {
            subDre: subDre,
            _token: "{{ csrf_token() }}"
        })
            .done(response => {
                resolve(response);
            })
            .fail(error => {
                reject(error);
            });
    });
}

async function getCentroCustoName(centroCusto) {
    return new Promise((resolve, reject) => {
        $.post("{{ route('autocomplete.get-centro-custo') }}", {
            centroCusto: centroCusto,
            _token: "{{ csrf_token() }}"
        })
            .done(response => {
                resolve(response);
            })
            .fail(error => {
                reject(error);
            });
    });
}

async function getClient(clientId){
    return new Promise((resolve, reject) => {
        $.post("{{ route('autocomplete.get-client') }}", {
            clientId: clientId,
            _token: "{{ csrf_token() }}"
        })
            .done(response => {
                resolve(response);
            })
            .fail(error => {
                reject(error);
            });
    });
}

async function getProducts(produtosId){
    return new Promise((resolve, reject) => {
        $.post("{{ route('autocomplete.get-products') }}", {
            produtosId: produtosId,
            _token: "{{ csrf_token() }}"
        })
            .done(response => {
                resolve(response);
            })
            .fail(error => {
                reject(error);
            });
    });
}
</script>
