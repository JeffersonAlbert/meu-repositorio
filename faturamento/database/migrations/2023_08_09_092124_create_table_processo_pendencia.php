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
        Schema::create('processo_pendencia', function (Blueprint $table) {
                $table->id();
                $table->integer('id_processo');
                $table->integer('id_processo_vencimento_valor');
                $table->text('observacao');
                $table->json('id_usuario_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processo_pendencia');
    }
};
