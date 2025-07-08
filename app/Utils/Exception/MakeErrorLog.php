<?php

namespace App\Utils\Exception;

use App\Models\ErroLog;
use Exception;

class MakeErrorLog
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
        ErroLog::query()->create($data);

        $erroLogId = ErroLog::latest()->first();

        return $erroLogId->id;
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
