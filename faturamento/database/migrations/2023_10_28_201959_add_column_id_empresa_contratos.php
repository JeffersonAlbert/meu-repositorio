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
        Schema::table('contratos', function (Blueprint $table) {
                $table->bigInteger('id_empresa')->after('id');
                $table->char('nome')->after('id_empresa')->change();
                $table->date('vigencia_inicial')->after('vigencia');
                $table->date('vigencia_final')->after('vigencia_inicial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
                $table->dropColumn('id_empresa');
                $table->dropColumn('vigencia_inicial');
                $table->dropColumn('vigencia_final');
        });
    }
};
