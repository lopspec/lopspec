<?php

/*
 * This file is part of LopSpec, A php toolset to drive emergent
 * design by specification.
 *
 * (c) Marcello Duarte <marcello.duarte@gmail.com>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LopSpec\Formatter\Presenter;

interface PresenterInterface
{
    /**
     * @param \Exception $exception
     * @param bool       $verbose
     *
     * @return string
     */
    public function presentException(\Exception $exception, $verbose = false);

    /**
     * @param string $string
     *
     * @return string
     */
    public function presentString($string);
    /**
     * @param mixed $value
     *
     * @return string
     */
    public function presentValue($value);
}
