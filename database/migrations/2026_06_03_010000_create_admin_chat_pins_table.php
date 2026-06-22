<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_chat_pins', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->foreignUuid('pinned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('pelanggan_id', 'admin_chat_pins_pelanggan_unique');
            $table->index('created_at', 'admin_chat_pins_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_chat_pins');
    }
};
