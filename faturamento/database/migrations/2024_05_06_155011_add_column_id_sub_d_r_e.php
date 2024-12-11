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
            $table->foreignId('id_sub_dre')->nullable()->constrained('sub_categoria_dre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo', function (Blueprint $table) {
            $table->dropColumn('id_sub_dre');
        });
    }
};
