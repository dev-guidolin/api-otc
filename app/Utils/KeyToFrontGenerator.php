<?php

namespace App\Utils;

class KeyToFrontGenerator
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $data): string
    {
        $encrypted = '';
        for ($i = 0, $iMax = strlen($data); $i < $iMax; $i++) {
            $encrypted .= chr(ord($data[$i]) ^ ord($this->key[$i % strlen($this->key)]));
        }

        return base64_encode($encrypted);
    }

    public function decrypt(string $encryptedData): string
    {
        $data = base64_decode($encryptedData);
        $decrypted = '';
        for ($i = 0, $iMax = strlen($data); $i < $iMax; $i++) {
            $decrypted .= chr(ord($data[$i]) ^ ord($this->key[$i % strlen($this->key)]));
        }

        return $decrypted;
    }
}
