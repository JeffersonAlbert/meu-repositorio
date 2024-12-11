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
        Schema::create('rateio_setup', function (Blueprint $table) {
                $table->id();
                $table->char('nome');
                $table->json('centro_custo_id_percent');
                $table->bigInteger('id_empresa');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rateio_setup');
    }
};
