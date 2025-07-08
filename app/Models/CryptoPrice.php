<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class CryptoPrice extends Model
{
    use Timestamp;

    protected $fillable = [
        'tether',
        'bitcoin',
    ];
}
