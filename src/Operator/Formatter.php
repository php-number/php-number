<?php

namespace Number\Operator;

class Formatter
{
    public static function format($num, $precision = null, $minLength = 0, $withLeadingZero = true)
    {
        $num = str_replace(',', '', $num);
        $unsigned = static::abs($num);
        $exploded = explode('.', $unsigned);

        if ($precision === null && count($exploded) === 2) {
            $precision = strlen($exploded[1]);
        }

        $exploded[0] = str_pad(ltrim($exploded[0], '0'), 1, '0', STR_PAD_LEFT);
        if ($precision > 0) {
            $exploded[1] = str_pad(rtrim($exploded[1], '0'), $precision, '0', STR_PAD_RIGHT);
            $formatted = implode('.', $exploded);
        } else {
            $formatted = $exploded[0];
        }

        if ($withLeadingZero) {
            $formatted = str_pad($formatted, $minLength, '0', STR_PAD_LEFT);
        }

        if (static::isNegative($num)) {
            return '-' . $formatted;
        } else {
            return $formatted;
        }
    }

    public static function abs($num)
    {
        $formatted = (string) $num;

        $sign = substr($formatted, 0, 1);
        if ($sign === '+' || $sign === '-') {
            $formatted = substr($formatted, 1);
        }

        return $formatted;
    }

    public static function negate($num)
    {
        $formatted = (string) $num;
        if (static::isNegative($formatted)) {
            return static::abs($formatted);
        } else {
            return '-' . static::abs($formatted);
        }
    }

    private static function isNegative($num)
    {
        return substr($num, 0, 1) === '-';
    }
}