<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Customer extends Model
{
    protected $fillable = [
        'owner_user_id',
        'name',
        'company',
        'email',
        'phone',
        'status',
        'notes',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
