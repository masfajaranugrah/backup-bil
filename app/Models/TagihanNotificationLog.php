<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TagihanNotificationLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'batch_id',
        'tagihan_id',
        'pelanggan_id',
        'pelanggan_nomer_id',
        'pelanggan_nama',
        'provider',
        'status',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
