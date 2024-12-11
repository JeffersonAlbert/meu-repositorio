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
        Schema::create('pagamentos', function (Blueprint $table) {
                $table->id();
                $table->integer('id_empresa');
                $table->integer('id_processo');
                $table->integer('id_processo_vencimento_valor');
                $table->decimal('valor_pago', 10, 4);
                $table->char('forma_pagamento');
                $table->integer('id_banco');
                $table->date('data_pagamento');
                $table->json('comprovantes')->nullable();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
