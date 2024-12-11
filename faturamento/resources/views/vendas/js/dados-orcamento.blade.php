<script>
    $(document).ready(async function(){
        showLoader();

        await getClient({{ $dadosVenda->clienteId }})
            .then(response => {
                $('.nome-cliente').text(response.nome);
            })
            .catch(error => {
                console.log(error);
            });


        await getProducts( @json($dadosVenda->produtoId) )
            .then(response => {
                let i = 0;
                let descricao = @json($dadosVenda->observacao);
                let quantidade = @json($dadosVenda->quantidade);
                let valorUnitario = @json($dadosVenda->valor_unitario);
                let subtotal = @json($dadosVenda->valor_total);
                response.forEach(produto => {
                    let row = `
                        <div class="row ml-1">
                            <div class="col-3">
                                <span>${produto.produto}</span>
                            </div>
                            <div class="col-3">
                                <span>${descricao[i]}</span>
                            </div>
                            <div class="col-1">
                                <span>${quantidade[i]}</span>
                            </div>
                            <div class="col-2">
                                <span>${valorUnitario[i]}</span>
                            </div>
                            <div class="col-3 text-end">
                                <span>${subtotal[i]}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr class="sidebar-divider"></hr>
                            </div>
                        </div>
                    `;

                    $('.addRows').append(row.replace('null', ''));
                    i++;
                });
            })
            .catch(error => {
                console.log(error);
            });

        hideLoader();
    })
</script>
