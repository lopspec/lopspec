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
namespace LopSpec\Wrapper\Subject\Expectation;

use Exception;
use LopSpec\Event\ExpectationEvent;
use LopSpec\Exception\Example\FailureException;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Matcher\MatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherDecorator extends Decorator implements ExpectationInterface
{
    /**
     * @param ExpectationInterface     $expectation
     * @param EventDispatcherInterface $dispatcher
     * @param MatcherInterface         $matcher
     * @param ExampleNode              $example
     */
    public function __construct(ExpectationInterface $expectation, EventDispatcherInterface $dispatcher, MatcherInterface $matcher, ExampleNode $example)
    {
        $this->setExpectation($expectation);
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->example = $example;
    }
    /**
     * @param  string  $alias
     * @param  mixed   $subject
     * @param  array   $arguments
     *
*@return boolean
     *
     * @throws \Exception
     * @throws \LopSpec\Exception\Example\FailureException
     * @throws \Exception
     */
    public function match($alias, $subject, array $arguments = [])
    {
        $this->dispatcher->dispatch(
            'beforeExpectation',
            new ExpectationEvent($this->example, $this->matcher, $subject, $alias, $arguments)
        );

        try {
            $result = $this->getExpectation()->match($alias, $subject, $arguments);
            $this->dispatcher->dispatch(
                'afterExpectation',
                new ExpectationEvent($this->example, $this->matcher, $subject, $alias, $arguments, ExpectationEvent::PASSED)
            );
        } catch (FailureException $e) {
            $this->dispatcher->dispatch(
                'afterExpectation',
                new ExpectationEvent($this->example, $this->matcher, $subject, $alias, $arguments, ExpectationEvent::FAILED, $e)
            );

            throw $e;
        } catch (Exception $e) {
            $this->dispatcher->dispatch(
                'afterExpectation',
                new ExpectationEvent($this->example, $this->matcher, $subject, $alias, $arguments, ExpectationEvent::BROKEN, $e)
            );

            throw $e;
        }

        return $result;
    }
    /**
     * @type \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @type \LopSpec\Loader\Node\ExampleNode
     */
    private $example;
    /**
     * @type \LopSpec\Matcher\MatcherInterface
     */
    private $matcher;
}
