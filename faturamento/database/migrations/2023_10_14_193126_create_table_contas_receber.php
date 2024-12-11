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
        Schema::create('contas_receber', function (Blueprint $table) {
                $table->id();
                $table->integer('id_cliente');
                $table->integer('id_contrato')->nullable();
                $table->integer('id_produto')->nullable();
                $table->integer('id_servico')->nullable();
                $table->integer('id_usuario');
                $table->json('valores');
                $table->text('observacao');
                $table->json('vencimentos');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_receber');
    }
};
