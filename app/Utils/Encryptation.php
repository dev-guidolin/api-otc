<?php

namespace App\Utils;

use JsonException;

class Encryptation
{
    public static function encryptwithKey($string, $key = 64): string
    {
        $result = '';
        $string = base64_encode($string);

        for ($i = 0, $k = strlen($string); $i < $k; $i++) {
            $char = $string[$i];
            $char = chr(ord($char) + $key);
            $result .= $char;
        }

        return base64_encode($result);
    }

    public static function decryptWithKey($stringEncoded, $key = 64): string
    {
        $result = '';
        $string = base64_decode($stringEncoded);

        for ($i = 0, $k = strlen($string); $i < $k; $i++) {
            $char = $string[$i];
            $char = chr(ord($char) - $key);
            $result .= $char;
        }

        return base64_decode($result);
    }

    /**
     * @throws JsonException
     */
    public static function encryptArray($data, $key = 12): string
    {
        $serializedData = json_encode($data, JSON_THROW_ON_ERROR);

        return encrypt($serializedData, $key);
    }

    /**
     * @throws JsonException
     */
    public static function decryptArray($encryptedData, $key = 12): array
    {
        $decryptedData = decrypt($encryptedData, $key);

        return json_decode($decryptedData, true, 512, JSON_THROW_ON_ERROR);
    }
}
