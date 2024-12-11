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
        Schema::create('recebimento', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('id_contas_receber');
                $table->bigInteger('id_usuario');
                $table->decimal('valor', 10, 4)->nullable();
                $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recebimento');
    }
};
