<?php

namespace spec\LopSpec\Wrapper\Subject\Expectation;

use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Subject;
use Prophecy\Argument;

class NegativeSpec extends ObjectBehavior
{
    function it_calls_a_negative_match_on_matcher(MatcherInterface $matcher)
    {
        $alias = 'somealias';
        $subject = 'subject';
        $arguments = [];

        $matcher->negativeMatch($alias, $subject, $arguments)->shouldBeCalled();
        $this->match($alias, $subject, $arguments);
    }
    function let(MatcherInterface $matcher)
    {
        $this->beConstructedWith($matcher);
    }
}
