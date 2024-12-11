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
            $table->char('codigo_produto')->nullable()->after('ean');
            $table->bigInteger('id_empresa')->after('id');
            $table->char('tipo')->after('produto');
            $table->decimal('valor', 10, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('codigo_produto');
            $table->dropColumn("id_empresa");
        });
    }
};
