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
                $table->date('vencimento')->after('id_usuario');
                $table->decimal('valor_total', 10, 4)->after('id_usuario');
                $table->decimal('valor_vencimento', 10, 4)->after('valor_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contas_receber', function (Blueprint $table) {
                $table->dropColumn('vencimento');
                $table->dropColumn('valor_vencimento');
                $table->dropColumn('valor_total');
        });
    }
};
