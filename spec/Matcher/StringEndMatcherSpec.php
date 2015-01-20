<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class StringEndMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_strings_that_do_not_start_with_specified_prefix()
    {
        $this->shouldThrow()
             ->duringPositiveMatch('endWith', 'everzet', ['tez']);
    }
    function it_does_not_match_strings_that_do_start_with_specified_prefix()
    {
        $this->shouldThrow()
             ->duringNegativeMatch('endWith', 'everzet', ['zet']);
    }
    function it_does_not_support_anything_else()
    {
        $this->supports('endWith', [], [])
             ->shouldReturn(false);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_strings_that_do_not_start_with_specified_prefix()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('endWith', 'everzet', ['tez']);
    }
    function it_matches_strings_that_start_with_specified_prefix()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('endWith', 'everzet', ['zet']);
    }
    function it_supports_endWith_keyword_and_string_subject()
    {
        $this->supports('endWith', 'hello, everzet', ['everzet'])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentString(Argument::type('string'))
                  ->willReturnArgument();
        $this->beConstructedWith($presenter);
    }
}
