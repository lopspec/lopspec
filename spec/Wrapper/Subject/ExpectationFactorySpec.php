<?php

namespace spec\LopSpec\Wrapper\Subject;

use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Runner\MatcherManager;
use LopSpec\Wrapper\Subject;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ExpectationFactorySpec extends ObjectBehavior
{
    function it_creates_negative_expectations(
        MatcherManager $matchers,
        MatcherInterface $matcher,
        Subject $subject
    )
    {
        $matchers->find(Argument::cetera())
                 ->willReturn($matcher);
        $subject->__call('getWrappedObject', [])
                ->willReturn(new \stdClass());
        $decoratedExpecation = $this->create('shouldNotbe', $subject);
        $decoratedExpecation->shouldHaveType('LopSpec\Wrapper\Subject\Expectation\Decorator');
        $decoratedExpecation->getNestedExpectation()
                            ->shouldHaveType('LopSpec\Wrapper\Subject\Expectation\Negative');
    }
    function it_creates_negative_throw_expectations(
        MatcherManager $matchers,
        MatcherInterface $matcher,
        Subject $subject
    )
    {
        $matchers->find(Argument::cetera())->willReturn($matcher);
        $subject->__call('getWrappedObject', [])
                ->willReturn(new \stdClass());
        $expectation = $this->create('shouldNotThrow', $subject);
        $expectation->shouldHaveType('LopSpec\Wrapper\Subject\Expectation\NegativeThrow');
    }
    function it_creates_positive_expectations(
        MatcherManager $matchers,
        MatcherInterface $matcher,
        Subject $subject
    )
    {
        $matchers->find(Argument::cetera())->willReturn($matcher);
        $subject->__call('getWrappedObject', [])
                ->willReturn(new \stdClass());
        $decoratedExpecation = $this->create('shouldBe', $subject);
        $decoratedExpecation->shouldHaveType('LopSpec\Wrapper\Subject\Expectation\Decorator');
        $decoratedExpecation->getNestedExpectation()
                            ->shouldHaveType('LopSpec\Wrapper\Subject\Expectation\Positive');
    }
    function it_creates_positive_throw_expectations(MatcherManager $matchers, MatcherInterface $matcher, Subject $subject)
    {
        $matchers->find(Argument::cetera())->willReturn($matcher);
        $subject->__call('getWrappedObject', [])
                ->willReturn(new \stdClass());
        $expectation = $this->create('shouldThrow', $subject);
        $expectation->shouldHaveType('LopSpec\Wrapper\Subject\Expectation\PositiveThrow');
    }
    function let(
        ExampleNode $example,
        EventDispatcherInterface $dispatcher,
        MatcherManager $matchers
    )
    {
        $this->beConstructedWith($example, $dispatcher, $matchers);
    }
}
