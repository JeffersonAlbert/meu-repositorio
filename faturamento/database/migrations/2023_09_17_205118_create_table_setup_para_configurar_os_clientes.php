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
        Schema::create('setup', function (Blueprint $table) {
                $table->id();
                $table->integer('id_empresa');
                $table->integer('dias_antes_vencimento')->default('5');
                $table->integer('dias_sem_movimentacao')->default('3');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup');
    }
};
