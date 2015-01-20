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
use LopSpec\Matcher\MatcherInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ExpectationEvent holds information about the expectation event
 */
class ExpectationEvent extends Event implements EventInterface
{
    /**
     * Expectation broken
     */
    const BROKEN = 2;
    /**
     * Expectation failed
     */
    const FAILED  = 1;
    /**
     * Expectation passed
     */
    const PASSED = 0;
    /**
     * @param ExampleNode      $example
     * @param MatcherInterface $matcher
     * @param mixed            $subject
     * @param string           $method
     * @param array            $arguments
     * @param integer          $result
     * @param \Exception       $exception
     */
    public function __construct(ExampleNode $example, MatcherInterface $matcher, $subject,
                                $method, $arguments, $result = null, $exception = null)
    {
        $this->example = $example;
        $this->matcher = $matcher;
        $this->subject = $subject;
        $this->method = $method;
        $this->arguments = $arguments;
        $this->result = $result;
        $this->exception = $exception;
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
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
    /**
     * @return MatcherInterface
     */
    public function getMatcher()
    {
        return $this->matcher;
    }
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    /**
     * @return integer
     */
    public function getResult()
    {
        return $this->result;
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
     * @type \Exception
     */
    private $exception;
    /**
     * @type \LopSpec\Matcher\MatcherInterface
     */
    private $matcher;
    /**
     * @type string
     */
    private $method;
    /**
     * @type integer
     */
    private $result;
    /**
     * @type mixed
     */
    private $subject;
}
