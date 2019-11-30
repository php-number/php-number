<?php

namespace Number\Traits;

use Number\ArithmeticNumberInterface;
use Number\Operator\Operator;

trait ArithmeticTrait
{
    public function dividedBy($num)
    {
        if (!($num instanceof ArithmeticNumberInterface)) {
            $num = Operator::makeArithmeticNumber($num);
        }

        return Operator::arithmetic()->div($this->toNumber(), $num);
    }

    public function plus($num)
    {
        if (!($num instanceof ArithmeticNumberInterface)) {
            $num = Operator::makeArithmeticNumber($num);
        }

        return Operator::arithmetic()->add($this->toNumber(), $num);
    }

    public function minus($num)
    {
        if (!($num instanceof ArithmeticNumberInterface)) {
            $num = Operator::makeArithmeticNumber($num);
        }

        return Operator::arithmetic()->add($this->toNumber(), $num);
    }

    public function times($num)
    {
        return Operator::arithmetic()->mul($this->toNumber(), $num);
    }

    public static function add(...$args)
    {
        $num = Operator::makeArithmeticNumber('0');
        foreach ($args as $arg) {
            $num = $num->plus($arg);
        }

        return $num;
    }

    public static function mul(...$args)
    {
        $num = Operator::makeArithmeticNumber(null);
        foreach ($args as $arg) {
            if ($num->isNaN()) {
                $num = Operator::makeArithmeticNumber($arg);
            } else {
                $num = $num->times($num);
            }
        }

        return $num;
    }

    public static function sub(...$args)
    {
        $num = Operator::makeArithmeticNumber(null);
        foreach ($args as $arg) {
            if ($num->isNaN()) {
                $num = Operator::makeArithmeticNumber($arg);
            } else {
                $num = $num->minus($num);
            }
        }

        return $num;
    }

    public static function div(...$args)
    {
        $num = Operator::makeArithmeticNumber(null);
        foreach ($args as $arg) {
            if ($num->isNaN()) {
                $num = Operator::makeArithmeticNumber($arg);
            } else {
                $num = $num->dividedBy($num);
            }
        }

        return $num;
    }

    public function toNumber()
    {
        throw new \Exception('Method toNumber() must be implemented');
    }
}