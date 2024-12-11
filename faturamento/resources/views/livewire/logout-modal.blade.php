<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pronto para sair? TESTE LIVEWIRE</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" title="Fechar">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecione "Sair" abaixo se estiver pronto para encerrar sua sessão atual.</div>
                <div class="modal-footer">
                    <button class="btn btn-info btn-back-number" type="button" data-dismiss="modal">Voltar</button>
                        <a class="btn btn-success btn-back-number-saida" style="padding: 6px 20px 6px 20px; border-radius: 6px" href="{{ route('signout') }}">Sair</a>

                </div>
            </div>
        </div>
    </div>