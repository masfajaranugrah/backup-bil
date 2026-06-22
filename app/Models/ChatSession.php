<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    use HasUuids;

    protected $fillable = [
        'pelanggan_id',
        'pic_id',
        'parent_chat_id',
        'source_chat_id',
        'chat_type',
        'division',
        'status',
        'transfer_reason',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function parentChat(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_chat_id');
    }

    public function sourceChat(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_chat_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_chat_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_session_id');
    }
}
