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
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('sub_categoria_dre')->nullable()->constrained('sub_categoria_dre');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->dropColumn('sub_categoria_dre');
        });
    }
};
