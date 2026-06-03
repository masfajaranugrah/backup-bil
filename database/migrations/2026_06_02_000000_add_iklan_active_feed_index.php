<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iklans', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'iklans_status_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table): void {
            $table->dropIndex('iklans_status_created_at_index');
        });
    }
};
