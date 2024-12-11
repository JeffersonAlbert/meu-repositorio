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
        Schema::table('processo', function (Blueprint $table) {
                $table->text("numero_nota")->after('id_centro_custo');
                $table->date("emissao_nota")->after('numero_nota');
                $table->text("condicao")->after('valor');
                $table->text("tipo_cobranca")->after('valor');
                $table->integer("id_workflow")->after('id_fornecedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo', function (Blueprint $table) {
                $table->dropColumn("numero_nota");
                $table->dropColumn("emissao_nota");
                $table->dropColumn("condicao");
                $table->dropColumn("id_workflow");
                $table->dropColumn("tipo_cobranca");
        });
    }
};
