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
        Schema::create('receber_vencimente_valor', function (Blueprint $table) {
                $table->id();
                $table->integer('id_contas_receber');
                $table->date('vencimento');
                $table->decimal('valor', 10, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recebiveis_valor');
    }
};
