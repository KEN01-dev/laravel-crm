<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'target_id');
    }

    public function scopeAction($q, ?string $action)
    {
        return $q->when($action, fn ($q) => $q->where('action', $action));
    }

    public function scopeDateBetween($q, ?string $from, ?string $to)
    {
        return $q->when($from && $to, fn ($q) => $q->whereBetween('created_at', [$from, $to]));
    }

    public function scopeIpLike($q, ?string $ip)
    {
        return $q->when($ip, fn ($q) => $q->where('meta->ip', 'like', "%{$ip}%"));
    }
}
