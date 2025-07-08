<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Traits\ExceptColumnTrait;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerDeposit extends Model
{
    use ExceptColumnTrait, HasFactory, SoftDeletes,Timestamp;

    protected $table = 'customer_deposits';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'amount',
        'emv',
        'expire_at',
        'e2e',
        'status',
        'response_payload',
    ];

    protected $casts = [
        'response_payload' => 'array',
        'amount' => MoneyCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', \App\Enum\StatusEnum::Approved->value);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }
}
