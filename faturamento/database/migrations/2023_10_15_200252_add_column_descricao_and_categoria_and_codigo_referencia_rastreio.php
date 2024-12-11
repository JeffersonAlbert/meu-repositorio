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
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->date('competencia');
                $table->text('descricao');
                $table->boolean('rateio')->nullable();
                $table->integer('id_categoria');
                $table->integer('id_centro_custo')->nullable();
                $table->char('codigo_referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contas_receber', function (Blueprint $table) {
             $table->dropColumn('competencia');
                $table->dropColumn('descricao');
                $table->dropColumn('rateio')->nullable();
                $table->dropColumn('id_categoria');
                $table->dropColumn('id_centro_custo')->nullable();
                $table->dropColumn('codigo_referencia');
        });
    }
};
