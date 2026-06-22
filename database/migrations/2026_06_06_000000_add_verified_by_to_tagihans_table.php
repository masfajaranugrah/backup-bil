<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihans', function (Blueprint $table): void {
            $table->foreignUuid('verified_by')
                ->nullable()
                ->after('tanggal_pembayaran')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('verified_by');
        });
    }
};
