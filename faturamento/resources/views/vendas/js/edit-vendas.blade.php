<script>
    $(document).ready(async function (){
        @if(isset($venda))
            let dados = @json($venda);

            let dadosVenda = @json(json_decode($venda->dados_venda));

            console.log(dados);

            showLoader();

            $('#tipo_desconto').val(dadosVenda.tipo_desconto);

            if(dadosVenda.tipo_desconto == 'currency'){
                $(".btn-currency").click();
            }

            if(dadosVenda.tipo_desconto == 'percent') {
                $(".btn-percent").click();
            }

            $("#desconto").val(dadosVenda.desconto);

            $("#frete").val(dadosVenda.frete);

            let idCliente = `[data-id="${dados.cliente_id}"]`;
            $('#dropdown-clientes-items').find(idCliente).click();

            let idSubDre = `[data-id="${dadosVenda.sub_categoria_dre}"]`;
            $('#dropdown-categoria-financeira-items').find(idSubDre).click();

            let centroCusto = `[data-id="${dadosVenda.centroCusto}"]`;
            $('#dropdown-centro-custo-items').find(centroCusto).click();

            let competencia = dadosVenda.data;
            $('#competencia').val(competencia);

            try{
                let products =  await getProducts(dadosVenda.produtoId);
                $.each(products, function(key, value){
                    if(key == 0) {
                        console.log('aqui');
                        $('.produto').text(value.produto);
                        $('.produto').val(value.id);
                        $('.quantidade').val(dadosVenda.quantidade[key]);
                        $('.valor-unitario').val(dadosVenda.valor_unitario[key]);
                        $('.valor-total').val(dadosVenda.valor_total[key]);
                        $('.descricao').val(dadosVenda.observacao[key]);
                    }
                    if(key > 0){
                        $('#adicionar-produtos').trigger('click');
                        let id = `[data-id="${value.id}"]`;
                        $('#dropdown-produtos-items'+key).find(id).click();
                        $('#observacao'+key).val(dadosVenda.observacao[key]);
                        $('#quantidade'+key).val(dadosVenda.quantidade[key]).focus().blur();
                        $('#valor_unitario'+key).val(dadosVenda.valor_unitario[key]).focus().blur();
                    }
                    console.log(key);
                });
                $("#desconto").focus();
                $("#frete").focus();
                $("#frete").blur();
            } catch(error) {
                console.error('Erro ao buscar produtos:', error);
            }

            function initializeForm() {
                return new Promise((resolve, reject) => {
                    try {
                        let methodPaymentId = `[data-id="${dadosVenda['forma-pagamento']}"]`;
                        $(".dropdown-forma-pagamento-items").find(methodPaymentId).click();
                        let accountBilling = `[data-id="${dadosVenda['contaRecebimento']}"]`;
                        $(".dropdown-recebimento-items").find(accountBilling).click();
                        $("#condicao_pagamento").val(dadosVenda.condicao_pagamento).change();
                        $("#parcelas").val(dadosVenda.parcelas).focus().blur();
                        console.log('teste de execucao');
                        resolve();
                    } catch(error) {
                        reject('Erro ao inicializar o formulário');
                    }
                });
            }

            initializeForm().then(() => {
                console.log(dadosVenda.parcelas);
                for(let i = 0; i < dadosVenda.parcelas; i++){
                    $(`#data_vencimento${i+1}`).val(dadosVenda.data_vencimento[i]);
                    $(`#valor_receber${i+1}`).val(dadosVenda.valor_receber[i]);
                    $(`#representacao_percent${i+1}`).val(dadosVenda.representacao_percent[i]);
                    $(`#descricao${i+1}`).val(dadosVenda.descricao[i]);
                    console.log('teste de execucao 2');
                }
            }).catch(error => {
                console.error(error);
            })

            hideLoader();
            @else
        @endif
    });
</script>
