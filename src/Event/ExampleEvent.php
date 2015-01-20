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
 * Class ExampleEvent holds the information about the example event
 */
class ExampleEvent extends Event implements EventInterface
{
    /**
     * Spec is broken
     */
    const BROKEN = 4;
    /**
     * Spec failed
     */
    const FAILED = 3;
    /**
     * Spec passed
     */
    const PASSED  = 0;
    /**
     * Spec is pending
     */
    const PENDING = 1;
    /**
     * Spec is skipped
     */
    const SKIPPED = 2;
    /**
     * @param ExampleNode  $example
     * @param float|null   $time
     * @param integer|null $result
     * @param \Exception   $exception
     */
    public function __construct(ExampleNode $example, $time = null, $result = null,
                                \Exception $exception = null)
    {
        $this->example   = $example;
        $this->time      = $time;
        $this->result    = $result;
        $this->exception = $exception;
    }
    /**
     * @return array
     */
    public function getBacktrace()
    {
        return $this->exception->getTrace();
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
     * @return string
     */
    public function getMessage()
    {
        return $this->exception->getMessage();
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
     * @return \LopSpec\Loader\Suite
     */
    public function getSuite()
    {
        return $this->getSpecification()
                    ->getSuite();
    }
    /**
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->example->getTitle();
    }
    /**
     * @type \LopSpec\Loader\Node\ExampleNode
     */
    private $example;
    /**
     * @type \Exception
     */
    private $exception;
    /**
     * @type integer
     */
    private $result;
    /**
     * @type float
     */
    private $time;
}
