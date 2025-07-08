<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'type',
        'phone',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['financial_summary', 'balance'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CustomerCoinPurchase::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(CustomerDeposit::class);
    }

    public function getBalanceAttribute(): int
    {
        return CustomerTransaction::query()
            ->where('user_id', $this->id)
            ->latest()
            ->first()?->balance_after ?? 0.0;
    }

    public function getFinancialSummaryAttribute(): array
    {
        $depositsQuery = CustomerDeposit::query()
            ->where('user_id', $this->id)
            ->where('status', StatusEnum::Paid->value);

        $depositsTotal = $depositsQuery->count();
        $depositsSum = (int) $depositsQuery->sum('amount');

        $spentQuery = CustomerCoinPurchase::query()
            ->where('user_id', $this->id)
            ->where('status', StatusEnum::Paid->value);

        $spentTotal = $spentQuery->count();
        $spentSum = (int) $spentQuery->sum('brl_amount');

        return [
            'deposits' => [
                'total' => $depositsTotal,
                'sum' => $depositsSum,
            ],
            'spents' => [
                'total' => $spentTotal,
                'sum' => $spentSum,
            ],
            'balance' => $this->getBalanceAttribute(),
        ];
    }

    public function customerProfile(): HasOne
    {
        return $this->hasOne(\App\Models\CustomerProfile::class);
    }
}
