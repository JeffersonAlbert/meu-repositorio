<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('processo', function (Blueprint $table) {

            if (Schema::hasColumn('processo', 'id_fornecedor')) {
                $table->index('id_fornecedor', 'idx_processo_id_fornecedor');
            }
            if (Schema::hasColumn('processo', 'id_produto')) {
                $table->index('id_produto', 'idx_processo_id_produto');
            }
            if (Schema::hasColumn('processo', 'id_centro_custos')) {
                $table->index('id_centro_custos', 'idx_processo_id_centro_custos');
            }
            if (Schema::hasColumn('processo', 'id_rateio')) {
                $table->index('id_rateio', 'idx_processo_id_rateio');
            }
            if (Schema::hasColumn('processo', 'id_sub_dre')) {
                $table->index('id_sub_dre', 'idx_processo_id_sub_dre');
            }
        });

        Schema::table('pagamentos', function (Blueprint $table) {
            if (Schema::hasColumn('pagamentos', 'id_processo') && Schema::hasColumn('pagamentos', 'id_processo_vencimento_valor')) {
                $table->index(['id_processo', 'id_processo_vencimento_valor'], 'idx_pagamentos_id_processo_id_processo_vencimento_valor');
            }
            if (Schema::hasColumn('pagamentos', 'id_banco')) {
                $table->index('id_banco', 'idx_pagamentos_id_banco');
            }
            if (Schema::hasColumn('pagamentos', 'forma_pagamento')) {
                $table->index('forma_pagamento', 'idx_pagamentos_forma_pagamento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo', function (Blueprint $table) {
            $table->dropIndex('idx_processo_id_fornecedor');
            $table->dropIndex('idx_processo_id_produto');
            $table->dropIndex('idx_processo_id_centro_custos');
            $table->dropIndex('idx_processo_id_rateio');
            $table->dropIndex('idx_processo_id_sub_dre');
        });

        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropIndex('idx_pagamentos_id_processo_id_processo_vencimento_valor');
            $table->dropIndex('idx_pagamentos_id_banco');
            $table->dropIndex('idx_pagamentos_forma_pagamento');
        });
    }
};
