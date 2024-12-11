<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fornecedor', function (Blueprint $table) {
            $table->string('searchable')->virtualAs(("CONCAT(cpf_cnpj, ' ', nome, ' ')"));
        });
            DB::statement('CREATE INDEX fornecedor_searchable_btree ON fornecedor (searchable)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedor', function (Blueprint $table) {
            $table->dropColumn('searchable');
        });

        DB::statement('DROP INDEX IF EXISTS fornecedor_searchable_btree');
    }
};
