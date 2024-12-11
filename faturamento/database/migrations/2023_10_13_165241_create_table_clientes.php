<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
                $table->id();
                $table->integer('id_empresa');
                $table->char('nome');
                $table->char('cpf_cnpj');
                $table->char('razao_social')->nullable();
                $table->char('endereco');
                $table->char('numero');
                $table->char('complemento')->nullable();
                $table->char('bairro');
                $table->char('cep');
                $table->char('inscricao_estadual')->nullable();
                $table->char('telefone')->nullable();
                $table->char('cidade');
                $table->string('searchable')->virtualAs(("CONCAT(cpf_cnpj, ' ', nome, ' ')"));
                $table->timestamps();
            });
            DB::statement('CREATE INDEX clientes_searchable_btree ON clientes (searchable)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
