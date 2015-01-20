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
namespace LopSpec\Exception\Fracture;

/**
 * Class MethodInvocationException holds information about method invocation
 * exceptions
 */
abstract class MethodInvocationException extends FractureException
{
    /**
     * @param string $message
     * @param mixed  $subject
     * @param string $method
     * @param array  $arguments
     */
    public function __construct($message, $subject, $method, array $arguments = [])
    {
        parent::__construct($message);

        $this->subject   = $subject;
        $this->method    = $method;
        $this->arguments = $arguments;
    }
    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->method;
    }
    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * @type array
     */
    private $arguments;
    /**
     * @type string
     */
    private $method;
    /**
     * @type mixed
     */
    private $subject;
}
