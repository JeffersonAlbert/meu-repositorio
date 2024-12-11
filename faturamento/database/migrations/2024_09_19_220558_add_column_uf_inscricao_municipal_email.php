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
        Schema::table('fornecedor', function (Blueprint $table) {
            $table->string('uf', 2)->nullable()->after('bairro');
            $table->string('inscricao_municipal', 20)->nullable()->after('uf');
            $table->string('email', 100)->nullable()->after('inscricao_municipal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedor', function (Blueprint $table) {
            $table->dropColumn('uf');
            $table->dropColumn('inscricao_municipal');
            $table->dropColumn('email');
        });
    }
};
