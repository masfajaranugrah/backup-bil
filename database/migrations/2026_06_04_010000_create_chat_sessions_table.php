<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('pelanggan_id');
            $table->uuid('pic_id')->nullable();
            $table->uuid('parent_chat_id')->nullable();
            $table->uuid('source_chat_id')->nullable();
            $table->string('chat_type', 30)->default('cs');
            $table->string('division', 50)->default('cs');
            $table->string('status', 30)->default('open');
            $table->text('transfer_reason')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->cascadeOnDelete();
            $table->foreign('pic_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('parent_chat_id')->references('id')->on('chat_sessions')->nullOnDelete();
            $table->foreign('source_chat_id')->references('id')->on('chat_sessions')->nullOnDelete();

            $table->index(['pelanggan_id', 'chat_type', 'status'], 'chat_sessions_customer_type_status_idx');
            $table->index(['parent_chat_id', 'source_chat_id'], 'chat_sessions_chain_idx');
            $table->index(['division', 'status'], 'chat_sessions_division_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
