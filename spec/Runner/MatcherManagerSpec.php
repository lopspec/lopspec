<?php

namespace spec\LopSpec\Runner;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class MatcherManagerSpec extends ObjectBehavior
{
    function it_searches_in_registered_matchers(MatcherInterface $matcher)
    {
        $matcher->getPriority()->willReturn(0);
        $matcher->supports('startWith', 'hello, world', ['hello'])
                ->willReturn(true);

        $this->add($matcher);
        $this->find('startWith', 'hello, world', ['hello'])
             ->shouldReturn($matcher);
    }
    function it_searches_matchers_by_their_priority(
        MatcherInterface $matcher1, MatcherInterface $matcher2
    ) {
        $matcher1->getPriority()->willReturn(2);
        $matcher1->supports('startWith', 'hello, world', ['hello'])
                 ->willReturn(true);
        $matcher2->getPriority()->willReturn(5);
        $matcher2->supports('startWith', 'hello, world', ['hello'])
                 ->willReturn(true);

        $this->add($matcher1);
        $this->add($matcher2);
        $this->find('startWith', 'hello, world', ['hello'])
             ->shouldReturn($matcher2);
    }
    function it_throws_MatcherNotFoundException_if_matcher_not_found()
    {
        $this->shouldThrow('LopSpec\Exception\Wrapper\MatcherNotFoundException')
             ->duringFind('startWith', 'hello, world', ['hello']);
    }
    function let(PresenterInterface $presenter)
    {
        $this->beConstructedWith($presenter);
    }
}
