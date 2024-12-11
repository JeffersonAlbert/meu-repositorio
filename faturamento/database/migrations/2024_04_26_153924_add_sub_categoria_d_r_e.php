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
        Schema::create('sub_categoria_dre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dre')->nullable()->constrained('dre');
            $table->string('descricao');
            $table->integer('vinculo_dre')->nullable();
            $table->integer('id_empresa')->nullable();
            $table->integer('id_usuario')->nullable();
            $table->boolean('editable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categoria_dre');
    }
};
