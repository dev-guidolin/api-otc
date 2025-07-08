<?php

namespace App\Casts;

use App\Utils\StringHandle;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return (int) $value; // <-- força tipo inteiro sempre
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value) {
            return (int) StringHandle::onlyNumbers($value); // ex: "R$ 50,00" → 5000
        }

        return 0;
    }
}
