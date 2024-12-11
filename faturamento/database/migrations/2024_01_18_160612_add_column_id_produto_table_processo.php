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
        Schema::table('processo', function (Blueprint $table) {
            $table->bigInteger('id_produto')->nullable();
            $table->bigInteger('id_servico')->nullable();
            $table->bigInteger('id_centro_custos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo', function (Blueprint $table) {
            $table->dropColumn('id_produto');
            $table->dropColumn('id_servico');
            $table->dropColumn('id_centro_custos');
        });
    }
};
