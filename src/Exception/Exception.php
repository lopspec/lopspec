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
namespace LopSpec\Exception;

use ReflectionFunctionAbstract;

/**
 * PhpSpec base exception
 */
class Exception extends \Exception
{
    /**
     * @return ReflectionFunctionAbstract
     */
    public function getCause()
    {
        return $this->cause;
    }
    /**
     * @param ReflectionFunctionAbstract $cause
     */
    public function setCause(ReflectionFunctionAbstract $cause)
    {
        $this->cause = $cause;
    }
    /**
     * @type ReflectionFunctionAbstract
     */
    private $cause;
}
