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
        Schema::table('filial', function (Blueprint $table) {
                $table->char('inscricao_estadual')->after('cnpj')->nullable();
                $table->char('cep')->after('razao_social');
                $table->char('endereco')->after('cep');
                $table->char('cidade')->after('endereco');
                $table->char('numero')->after('cidade');
                $table->char('complemento')->after('numero')->nullable();
                $table->char('bairro')->after('numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filial', function (Blueprint $table) {
                $table->dropColumn('inscricao_estadual');
                $table->dropColumn('cep');
                $table->dropColumn('endereco');
                $table->dropColumn('cidade');
                $table->dropColumn('numero');
                $table->dropColumn('complemento');
                $table->dropColumn('bairro');

        });
    }
};
