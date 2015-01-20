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
namespace LopSpec\Wrapper;

class DelayedCall
{
    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        $this->callable = $callable;
    }
    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func($this->callable, $method, $arguments);
    }
    /**
     * @type callable
     */
    private $callable;
}
