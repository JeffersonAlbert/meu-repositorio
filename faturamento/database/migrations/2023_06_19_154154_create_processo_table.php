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
        Schema::create('processo', function (Blueprint $table) {
                $table->id();
                $table->integer('id_user');
                $table->integer('id_fornecedor');
                $table->json('id_centro_custo');
                $table->char('valor');
                $table->integer('parcelas');
                $table->json('dt_parcelas');
                $table->integer('user_ultima_alteracao');
                $table->json('doc_name');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processo');
    }
};
