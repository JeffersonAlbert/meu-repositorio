<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
                $table->char('endereco')->after('razao_social');
                $table->char('numero')->after('endereco');
                $table->char('complemento')->after('numero')->nullable();
                $table->char('bairro')->after('complemento');
                $table->char('cep')->after('bairro');
                $table->char('inscricao_estadual')->after('cpf_cnpj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
                $table->dropColumn('endereco');
                $table->dropColumn('numero');
                $table->dropColumn('complemento');
                $table->dropColumn('bairro');
                $table->dropColumn('cep');
                $table->dropColumn('inscricao_estadual');
        });
    }
};
