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
        Schema::table('processo_vencimento_valor', function (Blueprint $table) {
            $table->boolean('aprovado')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo_vencimento_valor', function (Blueprint $table) {
            $table->boolean('aprovado')->default(null)->change();
        });
    }
};
