<?php

namespace App\Utils;

readonly class Money
{
    public int $int;

    public float $float;

    public string $currency;

    public function __construct(int $int)
    {
        $this->int = $int;
        $this->float = $int / 100;
        $this->currency = 'R$ '.number_format($this->float, 2, ',', '.');
    }

    public static function from(int $cent): self
    {
        return new self($cent);
    }

    public static function currencyToInt(string $value): Money
    {
        $cleanValue = (int) preg_replace('/\D/', '', $value);

        return new self($cleanValue);
    }

    public static function plus(int $value, int $add): Money
    {
        return new self($value + $add);
    }

    public static function minus(int $first, int $seccond): Money
    {
        return new self($first - $seccond);
    }

    public static function divide(int $first, int $seccond): Money
    {
        return new self($first / $seccond);
    }

    public static function multiply(int $first, int $seccond): Money
    {
        return new self($first * $seccond);
    }

    public static function addTax(int $itemPrice, float $tax): Money
    {
        $calc = ($tax / $itemPrice) * 100;

        return new self($calc);
    }

    public static function formatarDinheiroBR(float $valor): string
    {
        return number_format($valor, 2, ',', '.');
    }
}
