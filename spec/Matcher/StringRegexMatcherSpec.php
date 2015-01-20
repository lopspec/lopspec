<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class StringRegexMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_strings_that_do_match_specified_regex()
    {
        $this->shouldThrow()
             ->duringNegativeMatch('match', 'everzet', ['/^ev.*et$/']);
    }
    function it_does_not_match_strings_that_do_not_match_specified_regex()
    {
        $this->shouldThrow()
             ->duringPositiveMatch('match', 'everzet', ['/md/']);
    }
    function it_does_not_support_anything_else()
    {
        $this->supports('match', [], [])
             ->shouldReturn(false);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_strings_that_do_not_match_specified_regex()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('match', 'everzet', ['/md/']);
    }
    function it_matches_strings_that_match_specified_regex()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('match', 'everzet', ['/ev.*et/']);
    }
    function it_supports_match_keyword_and_string_subject()
    {
        $this->supports('match', 'hello, everzet', ['/hello/'])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentString(Argument::type('string'))
                  ->willReturnArgument();
        $this->beConstructedWith($presenter);
    }
}
