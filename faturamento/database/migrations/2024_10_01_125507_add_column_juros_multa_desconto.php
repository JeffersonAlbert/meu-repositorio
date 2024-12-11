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
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->decimal('juros', 10, 2)->nullable()->after('valor_pago');
            $table->decimal('multa', 10, 2)->nullable()->after('juros');
            $table->decimal('desconto', 10, 2)->nullable()->after('multa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('juros');
            $table->dropColumn('multa');
            $table->dropColumn('desconto');
        });
    }
};
