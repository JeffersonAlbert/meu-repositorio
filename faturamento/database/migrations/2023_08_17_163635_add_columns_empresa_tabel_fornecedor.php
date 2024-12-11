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
            Schema::table('fornecedor', function (Blueprint $table) {
                $table->char('razao_social')->nullable();
                $table->char('endereco')->nullable();
                $table->char('numero')->nullable();
                $table->char('complemento')->nullable();
                $table->char('bairro')->nullable();
                $table->char('cep')->nullable();
                $table->char('inscrica_estadual')->nullable();
            $table->char('telefone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedor', function (Blueprint $table) {
                $table->dropColumn('razao_social');
                $table->dropColumn('endereco');
                $table->dropColumn('numero');
                $table->dropColumn('complemento');
                $table->dropColumn('bairro');
                $table->dropColumn('cep');
                $table->dropColumn('inscrica_estadual');
                $table->dropColumn('telefone');
        });
    }
};
