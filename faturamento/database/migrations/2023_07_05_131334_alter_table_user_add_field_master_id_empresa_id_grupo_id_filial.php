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
        Schema::table('users', function (Blueprint $table) {
                $table->integer('id_empresa')->nullable()->after('password');
                $table->boolean('master')->nullable()->after('id_empresa');
                $table->integer('id_grupo')->nullable()->after('master');
                $table->json('id_filiais')->nullable()->after('id_empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
                $table->dropColumn('id_empresa');
                $table->dropColumn('master');
                $table->dropColumn('id_grupo');
                $table->dropColumn('id_filiais');
        });
    }
};
