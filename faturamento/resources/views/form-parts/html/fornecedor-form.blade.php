<div id="loader" class="loader"
     wire:loading.target="searchSupplierCpfCnpj, saveSupplier">
    Carregando...
</div>
<form wire:submit.prevent="saveSupplier">
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div @class(['card', 'border', 'border-success'])>
        <span @class(['card-title', 'titulo-grid-number', 'font-regular-wt', 'text-center'])>
            Dados do fornecedor
        </span>
        <div @class(['card-body'])>
            <div @class(['row'])>
                <div @class(['col']) x-data="{ timer: null }">
                    <label class="label-number" for="cnpj">CNPJ/CPF</label>
                    <input x-on:input.debounce.500ms="clearTimeout(timer); timer = setTimeout(() => { $wire.searchSupplierCpfCnpj() }, 500)"
                           x-on:input="$wire.fornecedorCpfCnpj = $event.target.value"
                           name="cnpj" type="text" class="input-login form-control" placeholder=""
                           value="" oninput="this.value = this.value.replace(/[./-]/g, '')">
                    @error('fornecedorCpfCnpj') <span class="text-danger font-extra-light-dt">{{ $message }}</span> @enderror
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="razao_social">Razao social</label>
                    <input wire:model.debounce.500ms="fornecedorRazaoSocial" name="razao_social" type="text" class="input-login form-control" placeholder="" value="">
                    @error('fornecedorRazaoSocial') <span class="text-danger font-extra-light-dt">{{ $message }}</span> @enderror
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="nome">Nome fantasia</label>
                    <input wire:model.debounce.500ms="fornecedorNomeFantasia" name="nome" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="email">Email</label>
                    <input wire:model.debounce.500ms="fornecedorEmail" name="email" type="email" class="input-login form-control" placeholder="" value="">
                    @error('fornecedorEmail') <span class="text-danger font-extra-light-dt">{{ $message }}</span> @enderror
                </div>
            </div>
            <div @class(['row', 'mt-1'])>
                <div @class(['col'])>
                    <label class="label-number" for="cnpj">Inscrição municipal</label>
                    <input wire:model.debounce.500ms="fornecedorInscricaoMunicipal" name="inscricao_municipal" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="cnpj">Inscrição estadual</label>
                    <input wire:model.debounce.500ms="fornecedorInscricaoEstadual" name="inscricao_mestadual" type="text" class="input-login form-control" placeholder="" value="">
                </div>
            </div>
            <div @class(['row', 'mt-1'])>
                <div @class(['col'])>
                    <label class="label-number" for="cep">CEP</label>
                    <input wire:model.debounce.500ms="fornecedorCep" name="cep" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="endereco">Endereço</label>
                    <input wire:model.debounce.500ms="fornecedorEndereco" name="endereco" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="numero">Número</label>
                    <input wire:model.debounce.500ms="fornecedorNumero" name="numero" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="cidade">Cidade</label>
                    <input wire:model.debounce.500ms="fornecedorCidade" name="cidade" type="text" class="input-login form-control" placeholder="" value="">
                </div>
            </div>
            <div @class(['row', 'mt-1'])>
                <div @class(['col'])>
                    <label class="label-number" for="complemento">Complemento</label>
                    <input wire:model.debounce.500ms="fornecedorComplemento" name="complemento" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="bairro">Bairro</label>
                    <input wire:model.debounce.500ms="fornecedorBairro" name="bairro" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col-1'])>
                    <label class="label-number" for="uf">Estado</label>
                    <select wire:model="fornecedorUf" name="uf" class="input-login form-control">
                        <option value="">{{ isset($fornecedorUf) ? $fornecedorUf : 'Uf' }}</option>
                        @foreach($ufs as $key => $uf)
                            <option value="{{ $key }}">{{ $uf }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div @class(['row', 'mt-1'])>
                <div @class(['col'])>
                    <label class="label-number" for="telefone">Telefone</label>
                    <input wire:model.debounce.500ms="fornecedorTelefone" name="telefone" type="text" class="input-login form-control" placeholder="" value="">
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="observacao">Dados pagamento do fornecedor</label>
                    <textarea wire:model.debounce.500ms="fornecedorDadosPagamento" name="forma_pagamento" class="input-login form-control" placeholder="Dados de pagamento para o fornecedor"></textarea>
                </div>
                <div @class(['col'])>
                    <label class="label-number" for="observacao">Observação</label>
                    <textarea wire:model.debounce.500ms="fornecedorObservacao" name="observacao" class="input-login form-control" placeholder="Observação"></textarea>
                </div>
            </div>
        </div>
        <div @class(['card-footer', 'text-right'])>
            <button type="submit" wire:loading wire:loading.target="saveSupplier"
                    wire:loading.class="btn btn-success btn-sm opacity-50"
                    wire:loading.attr="disabled">
                Salvando...
            </button>
            <button wire:loading.remove
                    type="submit" class="btn btn-success btn-sm">
                Salvar
            </button>
        </div>
    </div>
</form>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('dataSaved', (event) => {
            $('#modalAddClient').modal('hide');
        });
    });
</script>
