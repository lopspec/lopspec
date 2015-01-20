<?php

namespace spec\LopSpec\Wrapper\Subject\Expectation;

use LopSpec\Event\ExpectationEvent;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Subject\Expectation\ExpectationInterface;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherDecoratorSpec extends ObjectBehavior
{
    function it_decorates_expectation_with_broken_event(
        ExpectationInterface $expectation,
        EventDispatcherInterface $dispatcher
    )
    {
        $alias = 'be';
        $subject = new \stdClass();
        $arguments = [];
        $expectation->match(Argument::cetera())
                    ->willThrow('\RuntimeException');
        $dispatcher->dispatch('beforeExpectation',
            Argument::type('LopSpec\Event\ExpectationEvent'))
                   ->shouldBeCalled();
        $dispatcher->dispatch('afterExpectation',
            Argument::which('getResult', ExpectationEvent::BROKEN))
                   ->shouldBeCalled();
        $this->shouldThrow('\RuntimeException')
             ->duringMatch($alias, $subject, $arguments);
    }
    function it_decorates_expectation_with_failed_event(ExpectationInterface $expectation, EventDispatcherInterface $dispatcher)
    {
        $alias = 'be';
        $subject = new \stdClass();
        $arguments = [];
        $expectation->match(Argument::cetera())
                    ->willThrow('LopSpec\Exception\Example\FailureException');
        $dispatcher->dispatch('beforeExpectation',
            Argument::type('LopSpec\Event\ExpectationEvent'))
                   ->shouldBeCalled();
        $dispatcher->dispatch('afterExpectation', Argument::which('getResult', ExpectationEvent::FAILED))->shouldBeCalled();
        $this->shouldThrow('LopSpec\Exception\Example\FailureException')
             ->duringMatch($alias, $subject, $arguments);
    }
    function it_dispatches_before_and_after_events(
        EventDispatcherInterface $dispatcher
    )
    {
        $alias = 'be';
        $subject = new \stdClass();
        $arguments = [];
        $dispatcher->dispatch('beforeExpectation',
            Argument::type('LopSpec\Event\ExpectationEvent'))
                   ->shouldBeCalled();
        $dispatcher->dispatch('afterExpectation',
            Argument::which('getResult', ExpectationEvent::PASSED))
                   ->shouldBeCalled();
        $this->match($alias, $subject, $arguments);
    }
    function it_implements_the_interface_of_the_decorated()
    {
        $this->shouldImplement('LopSpec\Wrapper\Subject\Expectation\ExpectationInterface');
    }
    function let(
        ExpectationInterface $expectation,
        EventDispatcherInterface $dispatcher,
        MatcherInterface $matcher,
        ExampleNode $example
    ) {
        $this->beConstructedWith($expectation, $dispatcher, $matcher, $example);
    }
}
