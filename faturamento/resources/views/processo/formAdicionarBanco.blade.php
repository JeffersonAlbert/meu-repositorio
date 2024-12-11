<form id="formAdicionarBanco" method="POST" action="{{ route('bancos.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-4">
            <input class="form-control input-login" name="banco_nome" placeholder="Nome do banco">
        </div>
        <div class="form-group col-md-4">
            <input class="form-control input-login" name="banco_agencia" placeholder="Agencia aqui">
        </div>
        <div class="from-group col-md-4">
            <input class="form-control input-login" name="banco_conta" placeholder="Conta aqui">
        </div>
    </div>
</form>
