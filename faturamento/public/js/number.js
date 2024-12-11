// Função para ocultar o elemento informativo e armazenar o estado
function hideInformativoAndStoreState() {
    $('#informativo').hide();
    localStorage.setItem('informativoOculto', 'true');
}

// Verifica se o elemento informativo deve ser ocultado
const informativoOculto = localStorage.getItem('informativoOculto');
if (informativoOculto === 'true') {
    $('#informativo').addClass('secao');
}

// Adiciona um evento de clique para ocultar o elemento informativo e armazenar o estado
$('button#closeInfo').on('click', function(){
    console.log('Informativo ocultado');
    hideInformativoAndStoreState();
});

$('a.periodo').on('click', function(e){
    e.preventDefault();
    let val = $(this).data('value');
    let periodoConvertido = converteAcoesRapidasParaData(val);
});

$(document).on('keyup', 'input.currency-type', function(){
    let value = $(this).val();

    // Remove tudo que não é dígito
    value = value.replace(/\D/g, '');

    // Converte para número inteiro e depois para string formatada
    let intValue = parseInt(value);
    if (!intValue) {
        $(this).val('');
        return;
    }

    // Formata o número como moeda brasileira
    let formattedValue = (intValue / 100).toFixed(2).replace('.', ',');

    // Adiciona os pontos de milhar
    formattedValue = formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    $(this).val(formattedValue);
    //console.log('Valor formatado:', formattedValue);
});

$(document).ready(function () {
    $('.modal').on('shown.bs.modal', function () {
        // Rola para o topo do modal
        $(this).scrollTop(0);
    });
});

$(document).ready(function () {
    $('#switch-imposto').on('change', function () {
        if($(this).prop('checked')) {
            $('#dropdowntesteButton').attr('disabled', true);
        }else{
            $('#dropdowntesteButton').attr('disabled', false);
        }
    });
});
