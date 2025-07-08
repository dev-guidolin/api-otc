<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enum\StatusEnum;
use App\Traits\ExceptColumnTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCoinPurchase extends Model
{
    use ExceptColumnTrait, HasFactory, SoftDeletes;

    protected $table = 'customer_coin_purchases';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'market',
        'coin',
        'brl_amount',
        'coin_amount',
        'original_price_per_unit',
        'fee',
        'fee_price_per_unit',
        'brl_cost',
        'profit',
        'wallet',
        'network',
        'hash',
        'status',
        'finished_at',
        'obs',
    ];

    protected $casts = [
        'brl_amount' => MoneyCast::class,
        'original_price_per_unit' => MoneyCast::class,
        'fee' => 'float',
        'fee_price_per_unit' => MoneyCast::class,
        'brl_cost' => MoneyCast::class,
        'profit' => MoneyCast::class,
        'finished_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Status helpers
    public function isPending(): bool
    {
        return $this->status === StatusEnum::Created->value;
    }

    public function isCompleted(): bool
    {
        return $this->status === StatusEnum::Completed->value;
    }
}
