<?php

namespace Number;

use Number\Operator\Formatter;
use Number\Traits\ArithmeticTrait;

class Number implements ArithmeticNumberInterface
{
    use ArithmeticTrait;

    private $value;
    private $precision = null;
    private $parts = null;
    private $minLength = 0;
    private $withLeadingZeroes = true;
    private $inf = false;
    private $infNumber = '';

    private function init()
    {
        if ($this->value === null) {
            $this->parts();
        }
    }

    public function __construct($value, $precision = null, $minLength = 0, $withLeadingZeroes = true)
    {
        if ($value instanceof NumberInterface) {
            $this->value = $value->value();
        } elseif (is_float($value)) {
            throw new \Exception('Float is not accepted, pass string, int or instance of Number\NumberInterface only');
        }
        $this->precision = $precision;
        $this->minLength = $minLength;
        $this->withLeadingZeroes = $withLeadingZeroes;

        $this->init();
    }

    public static function create($value, $precision = null, $minLength = 0, $withLeadingZeroes = true)
    {
        return new static($value, $precision, $minLength, $withLeadingZeroes);
    }

    public static function createInf($value, $inf, $precision = null, $minLength = 0, $withLeadingZeroes = true)
    {
        $number = new static($value, $precision, $minLength, $withLeadingZeroes);
        $number->inf = true;
        $number->infNumber = (string) $inf;

        return $number;
    }

    /**
     * @return string
     */
    public function value()
    {
        return Formatter::format($this->value, $this->precision(), $this->minLength, $this->withLeadingZeroes);
    }

    public function originalValue()
    {
        return $this->value;
    }

    public function setPrecision($precision)
    {
        return new static($this->value, $precision);
    }

    public function __toString()
    {
        return $this->value();
    }

    public function toString()
    {
        return $this->value();
    }

    public function parts()
    {
        if ($this->parts === null) {
            $parts = exp('.', $this->value());
            $this->parts = [
                'real' => $parts[0],
                'decimal' => isset($parts[1]) ? $parts[1] : null,
            ];
        }

        return $this->parts;
    }

    public function realNumber()
    {
        return $this->parts()['real'];
    }

    public function decimalNumber($defaultValue = null)
    {
        if ($this->parts()['decimal'] === null) {
            return (string) $defaultValue;
        }

        return $this->parts()['decimal'];
    }

    public function hasDecimal()
    {
        return $this->parts()['decimal'] !== null;
    }

    public function precision()
    {
        if ($this->precision === null) {
            $parts = explode('.', $this->value);
            if (count($parts) === 2) {
                $this->precision = strlen($parts[1]);
            } else {
                $this->precision = 0;
            }
        }

        return $this->precision;
    }

    public function isNegative()
    {
        return substr($this->value, 0, 1) === '-';
    }

    public function isPositive()
    {
        return !$this->isNegative();
    }

    public function negate()
    {
        return $this->times('-1');
    }

    public function toNumber()
    {
        return $this;
    }

    public function abs()
    {
        return new static(Formatter::abs($this->value()), $this->precision());
    }

    public function isNaN()
    {
        return !is_numeric($this->value);
    }

    public function isInf()
    {
        return $this->inf;
    }
}