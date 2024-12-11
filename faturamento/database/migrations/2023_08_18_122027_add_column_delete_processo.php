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
                $table->boolean('deletado')->after('observacao')->default(false);
                $table->text('obs_delete')->after('deletado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processo', function (Blueprint $table) {
            $table->dropColumn('deletado');
                $table->dropColumn('obs_delete');
        });
    }
};
