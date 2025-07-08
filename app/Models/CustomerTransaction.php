<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerTransaction extends Model
{
    use HasFactory, SoftDeletes, Timestamp;

    protected $fillable = [
        'user_id',
        'amount',
        'direction',
        'balance_before',
        'balance_after',
        'transaction_type',
        'status',
        'transaction_id',
    ];

    protected $casts = [
        'amount' => MoneyCast::class,
        'balance_before' => MoneyCast::class,
        'balance_after' => MoneyCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
