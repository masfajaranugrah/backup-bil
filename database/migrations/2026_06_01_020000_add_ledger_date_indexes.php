<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->index('tanggal_masuk', 'incomes_tanggal_masuk_index');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->index('tanggal_keluar', 'expenses_tanggal_keluar_index');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropIndex('incomes_tanggal_masuk_index');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('expenses_tanggal_keluar_index');
        });
    }
};
