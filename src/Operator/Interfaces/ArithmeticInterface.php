<?php

namespace Number\Operator\Interfaces;

use Number\NumberInterface;

interface ArithmeticInterface
{
    public function add(NumberInterface $num1, NumberInterface $num2);

    public function sub(NumberInterface $num1, NumberInterface $num2);

    public function mul(NumberInterface $num1, NumberInterface $num2);

    public function div(NumberInterface $num1, NumberInterface $num2);
}