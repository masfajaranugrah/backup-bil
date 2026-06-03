<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_notification_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('batch_id')->index();
            $table->foreignUuid('tagihan_id')->constrained('tagihans')->cascadeOnDelete();
            $table->foreignUuid('pelanggan_id')->nullable()->constrained('pelanggans')->nullOnDelete();
            $table->string('pelanggan_nomer_id')->nullable();
            $table->string('pelanggan_nama')->nullable();
            $table->string('provider', 32)->nullable();
            $table->string('status', 24)->default('pending')->index();
            $table->text('message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['batch_id', 'status']);
            $table->index(['batch_id', 'provider']);
            $table->unique(['batch_id', 'tagihan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_notification_logs');
    }
};
