<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tagihans', function (Blueprint $table): void {
            if (! Schema::hasColumn('tagihans', 'edited_by')) {
                $table->foreignUuid('edited_by')
                    ->nullable()
                    ->after('verified_by')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('tagihans', 'edited_at')) {
                $table->timestamp('edited_at')->nullable()->after('edited_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table): void {
            if (Schema::hasColumn('tagihans', 'edited_by')) {
                $table->dropConstrainedForeignId('edited_by');
            }

            if (Schema::hasColumn('tagihans', 'edited_at')) {
                $table->dropColumn('edited_at');
            }
        });
    }
};
