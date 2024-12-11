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
        Schema::table('dre', function (Blueprint $table) {
                $table->integer('id_usuario')->nullable()->after('id');
                $table->integer('id_empresa')->nullable()->after('id_usuario');
                $table->boolean('editable')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dre', function (Blueprint $table) {
                $table->dropColumn('id_usuario');
                $table->dropColumn('id_empresa');
        });
    }
};
