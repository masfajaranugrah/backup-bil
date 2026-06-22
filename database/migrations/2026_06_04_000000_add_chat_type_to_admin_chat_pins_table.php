<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_chat_pins', function (Blueprint $table): void {
            if (!Schema::hasColumn('admin_chat_pins', 'chat_type')) {
                $table->string('chat_type', 20)->default('admin')->after('pinned_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_chat_pins', function (Blueprint $table): void {
            if (Schema::hasColumn('admin_chat_pins', 'chat_type')) {
                $table->dropColumn('chat_type');
            }
        });
    }
};
