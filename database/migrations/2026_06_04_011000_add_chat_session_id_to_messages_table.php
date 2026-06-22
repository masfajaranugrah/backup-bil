<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table): void {
            if (!Schema::hasColumn('messages', 'chat_session_id')) {
                $table->uuid('chat_session_id')->nullable()->after('id');
                $table->foreign('chat_session_id')->references('id')->on('chat_sessions')->nullOnDelete();
                $table->index(['chat_session_id', 'created_at'], 'messages_chat_session_created_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table): void {
            if (Schema::hasColumn('messages', 'chat_session_id')) {
                $table->dropForeign(['chat_session_id']);
                $table->dropIndex('messages_chat_session_created_idx');
                $table->dropColumn('chat_session_id');
            }
        });
    }
};
