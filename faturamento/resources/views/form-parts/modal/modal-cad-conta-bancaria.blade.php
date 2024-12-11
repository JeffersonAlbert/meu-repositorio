{{-- modal conta bancaria --}}
<div class="modal fade" id="modal-cad-conta-bancaria" 
	tabindex="-1" role="dialog" aria-labelledby="modal-cad-conta-bancaria" 
	aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="font-regular-wt text-processo" 
					id="modal-cad-conta-bancaria">Cadastrar conta bancaria</h5>
                <button type="button" class="close" 
					data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="enviarContaBancaria" class="form-group" action="{{ route('bancos.store') }}" method="POST">
				@csrf
				<div class="modal-body">
					<div class="messages-modal-cad-banco"></div>
					<input type="hidden" id="id_centro_custo_edit" name="id_centro_custo_edit" value="">
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="banco_nome" class="required label-number">Nome banco</label>
							<input id="banco_nome" class="form-control input-login" name="banco_nome">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="banco_agencia" class="label-number required">Agência</label>
							<input id="banco_agencia" class="form-control input-login" name="banco_agencia">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="banco_conta" class="label-number required">Conta</label>
							<input id="banco_conta" class="form-control input-login" name="banco_conta">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary font-weigtn-bold" 
						id="voltarContaBancaria" data-dismiss="modal">
							Voltar
					</button>
					<button type="submit" class="btn btn-success" 
						id="inserirContaBancaria" disabled>
							Confirmar
					</button>
				</div>
            </form>
        </div>
    </div>
</div>
