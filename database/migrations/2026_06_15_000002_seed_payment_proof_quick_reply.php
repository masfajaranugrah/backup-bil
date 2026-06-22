<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('quick_replies')) {
            Schema::create('quick_replies', function (Blueprint $table): void {
                $table->id();
                $table->uuid('created_by')->nullable();
                $table->string('shortcut', 48)->unique();
                $table->string('title', 100);
                $table->text('message');
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            });
        }

        DB::table('quick_replies')->updateOrInsert(
            ['shortcut' => '/bukti-tagihan'],
            [
                'created_by' => null,
                'title' => 'Tanya Bukti Pembayaran Tagihan',
                'message' => "Terima kasih atas masukan dan perhatian yang telah Anda sampaikan. Kami sangat menghargai setiap saran untuk meningkatkan kualitas pelayanan kami.\n\nSebagai tindak lanjut, kami ingin menanyakan apakah bukti pembayaran sudah diunggah melalui menu Tagihan? Mohon informasinya agar kami dapat melakukan pengecekan dan membantu proses selanjutnya dengan lebih cepat.\n\nTerima kasih atas kerja sama dan kesabarannya. 🙏",
                'is_active' => true,
                'sort_order' => 10,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('quick_replies')
            ->where('shortcut', '/bukti-tagihan')
            ->delete();
    }
};
