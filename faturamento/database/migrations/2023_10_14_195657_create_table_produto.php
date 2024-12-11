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
        Schema::create('produtos', function (Blueprint $table) {
                $table->id();
                $table->char('produto');
                $table->decimal('valor', 10, 4);
                $table->decimal('valor_custo', 10, 4)->nullable();
                $table->decimal('margem_value', 10, 4)->nullable();
                $table->decimal('margem_percent', 10, 2)->nullable();
                $table->char('ean')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};
