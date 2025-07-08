<?php

namespace App\Utils;

use App\Models\ErroLog;
use Exception;

class ErrorLog
{
    public static function handle(Exception $exception, ?string $info = null): int
    {
        $data = [
            'info' => $info ?? null,
            'error' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return ErroLog::query()->create($data)->id;
    }

    public static function guzzleHandle($exception, ?string $info = null): int
    {
        $data = [
            'info' => $info ?? null,
            'error' => $exception->getResponse()->getBody()->getContents(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return ErroLog::query()->insertGetId($data);
    }
}
