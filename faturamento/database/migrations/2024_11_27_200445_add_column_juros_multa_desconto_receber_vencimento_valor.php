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
        Schema::table('receber_vencimento_valor', function (Blueprint $table) {
            $table->decimal('juros', 10, 2)->nullable()->after('valor');
            $table->decimal('multa', 10, 2)->nullable()->after('juros');
            $table->decimal('desconto', 10, 2)->nullable()->after('multa');
            $table->text('observacao')->nullable()->after('desconto');
            $table->text('id_bank')->nullable()->after('observacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receber_vencimento_valor', function (Blueprint $table) {
            $table->dropColumn('juros');
            $table->dropColumn('multa');
            $table->dropColumn('desconto');
            $table->dropColumn('observacao');
            $table->dropColumn('id_bank');
        });
    }
};
