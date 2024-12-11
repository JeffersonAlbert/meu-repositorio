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
        Schema::table('approved_processo', function (Blueprint $table) {
            $table->integer('id_processo_vencimento_valor')->after('id_processo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approved_processo', function (Blueprint $table) {
            $table->integer('id_processo_vencimento_valor');
        });
    }
};
