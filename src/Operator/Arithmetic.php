<?php

namespace Number\Operator;

use Number\Number;
use Number\NumberInterface;
use Number\Operator\Interfaces\ArithmeticInterface;

class Arithmetic implements ArithmeticInterface
{
    /**
     * @param Number $num1
     * @param Number $num2
     *
     * @return \Number\ArithmeticNumberInterface
     */
    public function add(NumberInterface $num1, NumberInterface $num2)
    {
        if (!$this->computeAddOrSub($num1, $num2)) {
            return $this->sub($num1, $num2->negate());
        }

        $precision = $this->getMaxDecimals($num1, $num2);
        $this->fixToNumbers($precision, $num1, $num2);

        $real = $num1->realNumber() + $num2->realNumber();
        $decimal = $num1->decimalNumber('0') + $num2->decimalNumber('0');

        if (strlen($decimal) > $precision) {
            $additional = substr($decimal, 0, strlen($decimal) - $precision);
            $decimal = substr($decimal, strlen($decimal) - $precision);
            $real = $real + $additional;
        }

        return Operator::makeArithmeticNumber($real . '.' . $decimal, $precision);
    }

    public function sub(NumberInterface $num1, NumberInterface $num2)
    {
        if (!$this->computeAddOrSub($num1, $num2)) {
            return $this->add($num1, $num2->negeate());
        }

        $negative = false;
        if ($num2->isNegative()) {
            $num2 = $num2->negate();
            $negative = !$negative;
        }

        list ($num1 , $num2) = $this->addLeadingZeros($num1, $num2);
        if ($num2->value() > $num1->value()) {
            $tmp = $num1;
            $num1 = $num2;
            $num2 = $tmp;
            $negative = !$negative;
        }

        $num1 = Formatter::abs($num1->value());
        $num2 = Formatter::abs($num2->value());

        $nums = str_split($num1);
        for ($i = count($nums) - 1; $i >= 0; $i--) {
            if ($nums[$i] === '.') {
                continue;
            }
            $num2Value = substr($num2, $i, 1);
            if ($num2Value > $nums[$i]) {
                if ($nums[$i - 1] === '.') {
                    $nums[$i - 2] -= 1;
                } else {
                    $nums[$i - 1] -= 1;
                }
                $nums[$i] += 10;
            }

            $nums[$i] -= $num2Value;
        }

        $diff = implode('', $nums);
        if ($negative) {
            $diff = '-' . $diff;
        }

        return Operator::makeArithmeticNumber($diff);
    }

    public function mul(NumberInterface $num1, NumberInterface $num2)
    {
        $totalDecimals = $num1->precision() + $num2->precision();
        $num1 = str_replace('.', '', $num1->value());
        $num2 = str_replace('.', '', $num2->value());
        $product = $num1 * $num2;
        $decimalNumber = substr($product, -$totalDecimals);
        $realNumber = substr($product, 0, strlen($product) - $totalDecimals);

        return Operator::makeArithmeticNumber($realNumber . '.' . $decimalNumber);
    }

    public function div(NumberInterface $num1, NumberInterface $num2)
    {
        $moveDecimal = $num2->precision();
        $numArray = str_split($num1);
        $moved = 0;
        for ($x = 0; $x < count($numArray); $x++) {
            if ($numArray[$x] === '.') {
                $moveDecimal++;
                if (isset($numArray[$x + 1])) {
                    $numArray[$x] = $numArray[$x + 1];
                } else {
                    $numArray[$x] = '0';
                }
                $numArray[$x + 1] = '.';
            }

            if ($moved >= $moveDecimal) {
                break;
            }
        }
        $remainingMoves = $moveDecimal - $moved;
        $dividend = implode('', $numArray);
        $divisor = str_replace('.', '', $num2->value());
        if ($remainingMoves > 0) {
            $dividend .= '0';
        }
        $arrayedDividend = str_split($dividend);
        $quotient = '';
        $remainder = '0';
        $index = strlen($divisor);
        if (strlen($divisor) > strlen($dividend)) {
            $diff = strlen($divisor) - strlen($dividend);
            $quotient = '0.' . str_pad('', $diff - 1, '0');
            $remainder = $dividend . str_pad('', $diff - 1, '0');

        } else {
            $remainder = substr($dividend, 0, strlen($divisor));
            if (strpos($remainder, '.') !== false) {
                $remainder = str_replace('.', '', $remainder);
                $index++;
                $remainder .= $arrayedDividend[$index];
            }
        }

        $startOfInf = count($arrayedDividend) + (strlen($divisor) * 2);
        $isInf = false;
        $last = '';
        $compareToBeforeLast = '';
        do {
            $index++;
            $real = (int) ($remainder / $divisor);
            $quotient .= $real;
            $remainder = (int) ($remainder % $divisor);

            if (strlen($last) === strlen($divisor)) {
                if ($last === $compareToBeforeLast) {
                    $isInf = true;
                    break;
                }
            }

            if ($index > ($startOfInf + strlen($divisor)) && strlen($last) <= strlen($divisor)) {
                $last .= $real;
            } elseif ($index > ($startOfInf + strlen($divisor))) {
                $last = '';
                $compareToBeforeLast = substr($quotient, -(strlen($divisor)));
            }

            if (!isset($arrayedDividend[$index])) {
                $arrayedDividend[$index] = '0';
            }
            $remainder .= $arrayedDividend[$index];
        } while (($remainder == '0' && $index > strlen($dividend)) || !$isInf);

        if ($isInf) {
            return Operator::makeArithmeticNumberInf($quotient, $last);
        } else {
            return Operator::makeArithmeticNumber($quotient);
        }
    }

    /**
     * @param $precision
     * @param string|int|Number ...$nums
     */
    private function fixToNumbers($precision, NumberInterface &...$nums)
    {
        foreach ($nums as &$num) {
            $num = $num->setPrecision($precision);
        }
    }

    private function getMaxDecimals(NumberInterface ...$args)
    {
        $maxDecimal = 0;
        foreach ($args as $arg) {
            if ($arg->precision() > $maxDecimal) {
                $maxDecimal = $arg->precision();
            }
        }

        return $maxDecimal;
    }

    private function computeAddOrSub(NumberInterface $num, NumberInterface $num2)
    {
        return ($num->isNegative() && $num2->isNegative())
            || ($num->isPositive() && $num2->isPositive());
    }

    /**
     * @param \Number\NumberInterface ...$args
     * @return \Number\ArithmeticNumberInterface[]
     */
    private function addLeadingZeros(NumberInterface ...$args)
    {
        $longest = 0;
        $maxDecimal = $this->getMaxDecimals(...$args);
        $nums = [];
        foreach ($args as $arg) {
            $num = Formatter::format($arg->value(), $maxDecimal);
            $abs = Formatter::abs($num);
            if (strlen($abs) > $longest) {
                $longest = strlen($num);
            }
            $num[] = $num;
        }

        foreach ($nums as &$num) {
            $num = Operator::makeArithmeticNumber($num, $maxDecimal, $longest, true);
        }

        return $nums;
    }
}