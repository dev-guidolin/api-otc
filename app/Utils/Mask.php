<?php

namespace App\Utils;

class Mask
{
    const CNPJ = '##.###.###/####-##';

    const CPF = '###.###.###-##';

    const IP = '###.###.##.###';

    /**
     * Método document.
     * Este método formata um valor de acordo com uma máscara, usando as constantes definidas nesta classe.
     * As constantes disponíveis são:
     * - CPF
     * - CNPJ
     *
     * @param  string  $val  O valor a ser formatado.
     * @param  string  $mask  A máscara a ser aplicada.
     * @return string O valor formatado.
     */
    public static function document(string $val, string $mask): string
    {
        $mask = match ($mask) {
            'cnpj' => self::CNPJ,
            'cpf' => self::CPF,
            'ip' => self::IP,
        };

        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] === '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }

    public static function formatPhoneNumber($phone): array|false|string|null
    {
        // Remove qualquer caractere que não seja dígito
        $phone = preg_replace('/\D/', '', $phone);

        // Verifica se o telefone tem 11 dígitos (código de área + número)
        if (strlen($phone) !== 11) {
            return false;
        }

        // Aplica a máscara ao telefone
        return preg_replace(
            '/(\d{2})(\d{5})(\d{4})/',
            '($1) $2-$3',
            $phone
        );
    }
}
