<?php

namespace App\Utils;

class PixHelper
{
    public static function identificarTipoChavePix(string $chave): string
    {
        if (filter_var($chave, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        if (preg_match('/^\+?[1-9][0-9]{7,14}$/', $chave)) {
            return 'telefone';
        }

        if (preg_match('/^\d{11}$/', $chave)) {
            return 'cpf';
        }

        if (preg_match('/^\d{14}$/', $chave)) {
            return 'cnpj';
        }

        if (preg_match('/^[a-f0-9]{32}$/i', $chave)) {
            return 'aleatoria';
        }

        return 'desconhecida';
    }
}
