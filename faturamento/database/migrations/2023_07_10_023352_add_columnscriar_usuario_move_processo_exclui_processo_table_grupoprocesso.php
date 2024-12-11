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
        Schema::table('grupo_processos', function (Blueprint $table) {
                $table->boolean('criar_usuario');
                $table->boolean('move_processo');
                $table->boolean('deleta_processo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupo_processos', function (Blueprint $table) {
                $table->dropColumn('criar_usuario');
                $table->dropColumn('move_processo');
                $table->dropColumn('deleta_processo');
        });
    }
};
