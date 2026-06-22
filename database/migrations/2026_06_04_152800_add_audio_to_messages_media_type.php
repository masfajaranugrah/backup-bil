<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('messages', 'media_type')) {
            return;
        }

        DB::statement("ALTER TABLE messages MODIFY media_type ENUM('image', 'video', 'audio') NULL");
    }

    public function down(): void
    {
        if (!Schema::hasColumn('messages', 'media_type')) {
            return;
        }

        DB::statement("ALTER TABLE messages MODIFY media_type ENUM('image', 'video') NULL");
    }
};
