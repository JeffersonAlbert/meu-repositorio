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
        Schema::table('movimetacao_estoque', function (Blueprint $table) {
            $table->foreignId('fornecedor_id')->constrained('fornecedor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimetacao_estoque', function (Blueprint $table) {
            $table->dropForeign(['fornecedor_id']);
            $table->dropColumn('fornecedor_id');
        });
    }
};
