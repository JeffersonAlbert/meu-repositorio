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
        Schema::table('processo_pendencia', function (Blueprint $table) {
            $table->integer('id_usuario')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo_pendencia', function (Blueprint $table) {
            $table->dropColumn('id_usuario');
        });
    }
};
