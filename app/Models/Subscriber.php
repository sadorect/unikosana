<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'token',
        'is_active',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Subscriber $subscriber) {
            $subscriber->token ??= Str::random(40);
        });
    }
}
