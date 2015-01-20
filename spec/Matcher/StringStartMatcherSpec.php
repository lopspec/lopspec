<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class StringStartMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_strings_that_do_not_start_with_specified_prefix()
    {
        $this->shouldThrow()
             ->duringPositiveMatch('startWith', 'everzet', ['av']);
    }
    function it_does_not_match_strings_that_do_start_with_specified_prefix()
    {
        $this->shouldThrow()
             ->duringNegativeMatch('startWith', 'everzet', ['ev']);
    }
    function it_does_not_support_anything_else()
    {
        $this->supports('startWith', [], [])
             ->shouldReturn(false);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_strings_that_do_not_start_with_specified_prefix()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('startWith', 'everzet', ['av']);
    }
    function it_matches_strings_that_start_with_specified_prefix()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('startWith', 'everzet', ['ev']);
    }
    function it_supports_startWith_keyword_and_string_subject()
    {
        $this->supports('startWith', 'hello, everzet', ['hello'])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentString(Argument::type('string'))
                  ->willReturnArgument();
        $this->beConstructedWith($presenter);
    }
}
