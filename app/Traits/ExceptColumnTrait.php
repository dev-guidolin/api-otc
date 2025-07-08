<?php

namespace App\Traits;

trait ExceptColumnTrait
{
    public function exceptColumns(array $columns): array
    {
        return collect($this->toArray())
            ->except($columns)
            ->all();
    }
}
