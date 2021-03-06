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
namespace LopSpec\Exception\Example;

use Exception;

/**
 * Class StopOnFailureException holds information about stop on failure exception
 */
class StopOnFailureException extends ExampleException
{
    public function __construct($message = "", $code = 0, Exception $previous = null, $result = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->result = $result;
    }
    public function getResult()
    {
        return $this->result;
    }
    /**
     * @type integer
     */
    private $result;
}
