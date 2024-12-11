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
        Schema::create('grupo_order_fluxo', function (Blueprint $table) {
                $table->id();
                $table->integer('id_grupo');
                $table->integer('id_fluxo');
                $table->boolean('ativo');
                $table->integer('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_order_fluxo');
    }
};
