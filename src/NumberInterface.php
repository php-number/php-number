<?php

namespace Number;

interface NumberInterface
{
    public static function create($value, $precision = null, $minLength = 0, $withLeadingZeroes = true);

    public static function createInf($value, $inf, $precision = null, $minLength = 0, $withLeadingZeroes = true);

    public function value();

    public function originalValue();

    /**
     * @param $precision
     * @return \Number\NumberInterface|\Number\ArithmeticNumberInterface
     */
    public function setPrecision($precision);

    public function __toString();

    public function toString();

    public function parts();

    public function realNumber();

    public function decimalNumber($defaultValue = null);

    public function hasDecimal();

    public function precision();

    public function isNegative();

    public function isPositive();

    public function negate();

    public function isNaN();

    /**
     * @return \Number\NumberInterface|\Number\ArithmeticNumberInterface
     */
    public function abs();
}