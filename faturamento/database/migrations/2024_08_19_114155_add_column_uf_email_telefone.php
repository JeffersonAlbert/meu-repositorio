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
        Schema::table('empresa', function (Blueprint $table) {
            $table->string('uf', 2)->nullable()->after('bairro');
            $table->string('email', 100)->nullable()->after('inscricao_estadual');
            $table->string('telefone', 20)->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $dropColumns = ['uf', 'email', 'telefone'];
            $table->dropColumn($dropColumns);
        });
    }
};
