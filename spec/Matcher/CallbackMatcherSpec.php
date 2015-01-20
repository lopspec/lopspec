<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class CallbackMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_if_callback_returns_false($presenter)
    {
        $this->beConstructedWith('custom', function () {
            return false;
        }, $presenter);
        $this->shouldThrow()
             ->duringPositiveMatch('custom', [], []);
    }
    function it_does_not_support_anything_else()
    {
        $this->supports('anything_else', [], [])
             ->shouldReturn(false);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_if_callback_returns_true($presenter)
    {
        $this->beConstructedWith('custom', function () { return true; }, $presenter);
        $this->shouldNotThrow()
             ->duringPositiveMatch('custom', [], []);
    }
    function it_supports_same_alias_it_was_constructed_with()
    {
        $this->supports('custom', [], [])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentValue(Argument::any())
                  ->willReturn('val');
        $presenter->presentString(Argument::any())
                  ->willReturnArgument();
        $this->beConstructedWith('custom', function () {
        }, $presenter);
    }
}
