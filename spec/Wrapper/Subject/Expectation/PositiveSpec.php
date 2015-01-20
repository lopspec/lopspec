<?php

namespace spec\LopSpec\Wrapper\Subject\Expectation;

use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class PositiveSpec extends ObjectBehavior
{
    function it_calls_a_positive_match_on_matcher(MatcherInterface $matcher)
    {
        $alias = 'somealias';
        $subject = 'subject';
        $arguments = [];

        $matcher->positiveMatch($alias, $subject, $arguments)->shouldBeCalled();
        $this->match($alias, $subject, $arguments);
    }
    function let(MatcherInterface $matcher)
    {
        $this->beConstructedWith($matcher);
    }
}
