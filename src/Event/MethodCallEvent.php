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
namespace LopSpec\Event;

use LopSpec\Loader\Node\ExampleNode;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MethodCallEvent holds information about method call events
 */
class MethodCallEvent extends Event implements EventInterface
{
    /**
     * @param ExampleNode $example
     * @param mixed       $subject
     * @param string      $method
     * @param array       $arguments
     * @param mixed       $returnValue
     */
    public function __construct(ExampleNode $example, $subject, $method, $arguments, $returnValue = null)
    {
        $this->example = $example;
        $this->subject = $subject;
        $this->method = $method;
        $this->arguments = $arguments;
        $this->returnValue = $returnValue;
    }
    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    /**
     * @return ExampleNode
     */
    public function getExample()
    {
        return $this->example;
    }
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    /**
     * @return mixed
     */
    public function getReturnValue()
    {
        return $this->returnValue;
    }
    /**
     * @return \LopSpec\Loader\Node\SpecificationNode
     */
    public function getSpecification()
    {
        return $this->example->getSpecification();
    }
    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * @return \LopSpec\Loader\Suite
     */
    public function getSuite()
    {
        return $this->example->getSpecification()
                             ->getSuite();
    }
    /**
     * @type array
     */
    private $arguments;
    /**
     * @type \LopSpec\Loader\Node\ExampleNode
     */
    private $example;
    /**
     * @type string
     */
    private $method;
    /**
     * @type mixed
     */
    private $returnValue;
    /**
     * @type mixed
     */
    private $subject;
}
