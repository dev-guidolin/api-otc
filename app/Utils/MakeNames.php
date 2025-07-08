<?php

namespace App\Utils;

class MakeNames
{
    private static array $maleNames = [
        'João', 'Pedro', 'Lucas', 'Gabriel', 'Matheus', 'Guilherme', 'Felipe', 'Rafael', 'Bruno', 'Henrique',
        'Gustavo', 'Tiago', 'Fernando', 'Leonardo', 'Caio', 'Eduardo', 'André', 'Ricardo', 'Diego', 'Igor',
        'Vitor', 'Rodrigo', 'Marcelo', 'Alexandre', 'Daniel', 'Maurício', 'Renato', 'Júlio', 'Thiago', 'Luiz',
        'Samuel', 'Otávio', 'Vinícius', 'Victor', 'César', 'Wagner', 'Fábio', 'Cláudio', 'Antônio', 'Alberto',
        'Paulo', 'Rogério', 'Marcos', 'Carlos', 'Jorge', 'Roberto', 'Sérgio', 'Márcio', 'Emanuel', 'Christian',
    ];

    private static array $femaleNames = [
        'Maria', 'Ana', 'Joana', 'Patrícia', 'Juliana', 'Amanda', 'Mariana', 'Beatriz', 'Camila', 'Gabriela',
        'Rafaela', 'Fernanda', 'Larissa', 'Letícia', 'Aline', 'Bianca', 'Carla', 'Diana', 'Sabrina', 'Roberta',
        'Tatiana', 'Alessandra', 'Priscila', 'Cristiane', 'Simone', 'Vanessa', 'Daniela', 'Bruna', 'Thaís', 'Flávia',
        'Claudia', 'Regina', 'Adriana', 'Lorena', 'Isabel', 'Renata', 'Carolina', 'Luciana', 'Andréia', 'Viviane',
        'Emanuela', 'Suzana', 'Caroline', 'Verônica', 'Débora', 'Kelly', 'Nathalia', 'Elaine', 'Tatiane', 'Rosana',
    ];

    /**
     * Retorna um nome masculino randomizado.
     */
    public static function randomMaleName(): string
    {
        return self::$maleNames[array_rand(self::$maleNames)];
    }

    /**
     * Retorna um nome feminino randomizado.
     */
    public static function randomFemaleName(): string
    {
        return self::$femaleNames[array_rand(self::$femaleNames)];
    }

    /**
     * Retorna um nome randomizado, masculino ou feminino.
     */
    public static function randomName(): string
    {
        $allNames = array_merge(self::$maleNames, self::$femaleNames);

        return $allNames[array_rand($allNames)];
    }

    /**
     * Retorna a lista completa de nomes masculinos.
     */
    public static function maleNames(): array
    {
        return self::$maleNames;
    }

    /**
     * Retorna a lista completa de nomes femininos.
     */
    public static function femaleNames(): array
    {
        return self::$femaleNames;
    }

    /**
     * Retorna a lista completa de todos os nomes.
     */
    public static function allNames(): array
    {
        return array_merge(self::$maleNames, self::$femaleNames);
    }
}
