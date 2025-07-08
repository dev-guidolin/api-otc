<?php

namespace App\Utils;

class Encryptor
{
    protected static string $cipher = 'AES-256-CBC';

    public static function encrypt(string $value): string
    {
        $key = self::getKey();
        $iv = substr(hash('sha256', env('APP_ENCRYPT_IV', 'iv-default')), 0, 16); // 16 bytes para AES

        return openssl_encrypt($value, self::$cipher, $key, 0, $iv);
    }

    public static function decrypt(string $encrypted): string
    {
        $key = self::getKey();
        $iv = substr(hash('sha256', env('APP_ENCRYPT_IV', 'iv-default')), 0, 16);

        return openssl_decrypt($encrypted, self::$cipher, $key, 0, $iv);
    }

    protected static function getKey(): string
    {
        return substr(hash('sha256', env('APP_ENCRYPT_KEY', 'minha-chave-secreta')), 0, 32); // 32 bytes
    }
}
