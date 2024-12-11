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
        Schema::create('contratos', function (Blueprint $table) {
                $table->id();
                $table->integer('id_cliente')->nullable();
                $table->integer('id_fornecedor')->nullable();
                $table->json('files')->nullable();
                $table->date('inicio_contrato')->nullable();
                $table->integer('vigencia');
                $table->decimal('valor', 10, 4)->nullable();
                $table->json('id_produtos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
