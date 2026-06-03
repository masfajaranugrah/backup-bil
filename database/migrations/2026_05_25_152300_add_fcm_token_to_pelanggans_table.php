<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table): void {
            if (!Schema::hasColumn('pelanggans', 'fcm_token')) {
                $table->text('fcm_token')->nullable()->after('webpushr_sid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table): void {
            if (Schema::hasColumn('pelanggans', 'fcm_token')) {
                $table->dropColumn('fcm_token');
            }
        });
    }
};
