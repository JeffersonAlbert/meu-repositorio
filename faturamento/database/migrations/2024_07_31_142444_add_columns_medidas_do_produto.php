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
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('unidade_medida')->nullable()->after('valor');
            $table->string('peso_bruto')->nullable()->after('unidade_medida');
            $table->string('comprimento')->nullable()->after('peso_bruto');
            $table->string('largura')->nullable()->after('comprimento');
            $table->string('altura')->nullable()->after('largura');
            $table->string('peso_liquido')->nullable()->after('unidade_medida');
            $table->string('estoque_minimo')->nullable()->after('peso_liquido');
            $table->string('estoque_maximo')->nullable()->after('estoque_minimo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('unidade_medida');
            $table->dropColumn('peso_bruto');
            $table->dropColumn('comprimento');
            $table->dropColumn('largura');
            $table->dropColumn('altura');
            $table->dropColumn('peso_liquido');

        });
    }
};
