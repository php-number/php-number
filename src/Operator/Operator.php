<?php

namespace Number\Operator;

use Number\Number;

class Operator
{
    static $config = [
        'numberClass' => Number::class,
        'arithmeticNumberClass' => Number::class,
        'arithmetic' => Arithmetic::class,
    ];

    /**
     * @var \Number\Operator\Interfaces\ArithmeticInterface
     */
    private static $arithmetic = null;

    public static function arithmetic()
    {
        if (static::$arithmetic === null) {
            $class = static::$config['arithmetic'];
            static::$arithmetic = new $class();
        }

        return static::$arithmetic;
    }

    /**
     * @param $num
     * @param $precision
     *
     * @return \Number\Number
     */
    public static function makeNumber($num, $precision, $minLength = 0, $withLeadingZero = true)
    {
        $class = static::$config['numberClass'];

        return new $class($num, $precision, $minLength, $withLeadingZero);
    }

    /**
     * @param $num
     * @param $precision
     *
     * @return \Number\ArithmeticNumberInterface
     */
    public static function makeArithmeticNumber($num, $precision, $minLength = 0, $withLeadingZero = true)
    {
        $class = static::$config['arithmeticNumberClass'];

        return $class::create($num, $precision, $minLength, $withLeadingZero);
    }

    public static function makeArithmeticNumberInf($num, $inf, $precision = null, $minLength = 0, $withLeadingZeroes = true)
    {
        $class = static::$config['arithmeticNumberClass'];

        return $class::createInf($num, $inf, $precision, $minLength, $withLeadingZeroes);
    }
}