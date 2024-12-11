// Função para mostrar o elemento informativo e limpar o estado
function showInformativoAndClearState() {
    $('#informativo').show().removeClass('secao');
    localStorage.removeItem('informativoOculto');
}

// Adiciona um evento de clique para mostrar o elemento informativo e limpar o estado
$('.logo-principal').on('click', function(){
    console.log('Voltar à condição inicial');
    showInformativoAndClearState();
});
