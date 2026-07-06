<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'amount',
        'currency',
        'status',
        'reference',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function getFormattedAmountAttribute(): string
    {
        return strtoupper($this->currency) . ' ' . number_format($this->amount / 100, 2);
    }
}
