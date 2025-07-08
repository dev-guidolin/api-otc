<?php

namespace App\Utils;

class StringHandle
{
    public static function generateSlug(?string $value): string
    {
        if (! $value || trim($value) === '') {
            return '';
        }

        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value)));
    }

    public static function onlyNumbers(?string $value): string
    {
        return preg_replace('/\D/', '', $value ?? '');
    }

    public static function normalizeString(?string $value): string
    {
        return strtolower(trim($value ?? ''));
    }

    public static function titleCase(?string $value): string
    {
        return ucwords(trim($value ?? ''));
    }

    public static function toFloat(?string $value): float
    {
        $numbers = self::onlyNumbers($value);

        return (float) ((int) $numbers / 100);
    }

    public static function sanitizeDomain(?string $value): string
    {
        $url = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $value ?? '');

        return rtrim($url, '/');
    }

    public static function formatCurrencyBRL(float|int|string $value): string
    {
        return 'R$ '.number_format((float) $value / 100, 2, ',', '.');
    }

    public static function documentType(?string $value): string
    {
        $numbers = self::onlyNumbers($value);

        if (strlen($numbers) === 11) {
            return 'cpf';
        }

        return strlen($numbers) === 14 ? 'cnpj' : 'invalido';
    }

    public static function estadoDoCpf(?string $cpf): ?string
    {
        $cpf = self::onlyNumbers($cpf);

        if (strlen($cpf) !== 11) {
            return null;
        }

        $digito = (int) $cpf[8];

        $estados = [
            0 => 'RS',
            1 => 'DF, GO, MT, MS, TO',
            2 => 'AC, AM, AP, PA, RO, RR',
            3 => 'CE, MA, PI',
            4 => 'AL, PB, PE, RN',
            5 => 'BA, SE',
            6 => 'MG',
            7 => 'ES, RJ',
            8 => 'SP',
            9 => 'PR, SC',
        ];

        return $estados[$digito] ?? null;
    }
}
