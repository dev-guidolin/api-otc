<?php

namespace App\Utils;

use App\Models\CustomerDeposit;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Random\RandomException;

class Generator
{
    /**
     * @throws RandomException
     * @throws RandomException
     */
    public static function cpfGen($mascara = 1): string
    {
        $n1 = random_int(0, 9);
        $n2 = random_int(0, 9);
        $n3 = random_int(0, 9);
        $n4 = random_int(0, 9);
        $n5 = random_int(0, 9);
        $n6 = random_int(0, 9);
        $n7 = random_int(0, 9);
        $n8 = random_int(0, 9);
        $n9 = random_int(0, 9);

        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }

        $retorno = '';
        if ($mascara === 1) {
            $retorno = ''.$n1.$n2.$n3.'.'.$n4.$n5.$n6.'.'.$n7.$n8.$n9.'-'.$d1.$d2;
        } else {
            $retorno = ''.$n1.$n2.$n3.$n4.$n5.$n6.$n7.$n8.$n9.$d1.$d2;
        }

        return $retorno;
    }

    /**
     * @throws RandomException
     */
    public static function randomNumbers(int $length): string
    {
        if ($length <= 0) {
            throw new InvalidArgumentException('Length must be a positive integer.');
        }
        $characters = '0123456789';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * @throws RandomException
     */
    public static function randomAlphanumeric(int $length, string $case = 'default'): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($case === 'low') {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        } elseif ($case === 'upper') {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * @throws RandomException
     */
    public static function randomDate(string $startDate, string $endDate, bool $timeStamp = false): string
    {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        $randomTimestamp = random_int($startTimestamp, $endTimestamp);

        if ($timeStamp) {
            return (string) $randomTimestamp;
        }

        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    /**
     * @throws RandomException
     */
    public static function generateTxid($length = 32): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $txid = '';
        for ($i = 0; $i < $length; $i++) {
            $txid .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return 'FNX'.$txid;
    }

    /**
     * Mock para gerar um EndToEndId
     * Exemplo real: E000381232023101212345678901234567
     *
     * @throws RandomException
     */
    public static function endToEndId(): string
    {
        // "E" + código do PSP (mockado como 00038123) + data + parte randômica
        $psp = '00038123'; // Código PSP fictício
        $date = date('Ymd'); // AAAAMMDD
        $random = strtoupper(bin2hex(random_bytes(8))); // 16 caracteres

        return "E{$psp}{$date}".substr($random, 0, 14); // total com até 32 caracteres
    }

    /**
     * Mock para gerar um código EMV QR Pix
     * Obs: este é um mock, não um EMV válido para QR Pix real
     *
     * @throws RandomException
     */
    public static function emv(): string
    {
        // Padrão Pix EMV simulado (não válido para QR real)
        $txid = self::generateTxid(25);

        return "00020126580014BR.GOV.BCB.PIX0136mock@pix.com.br520400005303986540510.005802BR5913Nome Exemplo6009SAO PAULO62190515{$txid}6304MOCK";
    }

    public static function externalId(int $length = 18): string
    {
        do {
            // Exemplo: "FNX" + string aleatória alfanumérica
            $externalId = 'COIN'.Str::upper(Str::random($length - 3));

            $existsInDeposits = CustomerDeposit::where('external_id', $externalId)->exists();

        } while ($existsInDeposits);

        return $externalId;
    }
}
