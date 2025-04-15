<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'collection', // JSON alanını Collection olarak kullan
    ];

    /**
     * Eylemin ilgili olduğu modeli getirir (Post, User vb.).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Eylemi gerçekleştiren kullanıcıyı getirir.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 