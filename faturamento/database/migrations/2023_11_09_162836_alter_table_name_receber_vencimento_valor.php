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
        Schema::rename('receber_vencimente_valor', 'receber_vencimento_valor');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
