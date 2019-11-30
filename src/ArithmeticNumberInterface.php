<?php

namespace Number;

interface ArithmeticNumberInterface extends NumberInterface
{
    /**
     * @param string|int|\Number\ArithmeticNumberInterface $num
     * @return \Number\ArithmeticNumberInterface
     */
    public function plus($num);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface $num
     * @return \Number\ArithmeticNumberInterface
     */
    public function times($num);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface $num
     * @return \Number\ArithmeticNumberInterface
     */
    public function minus($num);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface $num
     * @return \Number\ArithmeticNumberInterface
     */
    public function dividedBy($num);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface ...$args
     * @return \Number\ArithmeticNumberInterface
     */
    public static function add(...$args);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface ...$args
     * @return \Number\ArithmeticNumberInterface
     */
    public static function mul(...$args);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface ...$args
     * @return \Number\ArithmeticNumberInterface
     */
    public static function sub(...$args);

    /**
     * @param string|int|\Number\ArithmeticNumberInterface ...$args
     * @return \Number\ArithmeticNumberInterface
     */
    public static function div(...$args);
}