<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayContainMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_array_without_specified_value()
    {
        $this->shouldThrow()
             ->duringPositiveMatch('contain', [1, 2, 3], ['abc']);
        $this->shouldThrow('LopSpec\Exception\Example\FailureException')
             ->duringPositiveMatch('contain', [1, 2, 3], [new \stdClass()]);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_array_with_specified_value()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('contain', ['abc'], ['abc']);
    }
    function it_matches_array_without_specified_value()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('contain', [1, 2, 3], ['abc']);
    }
    function it_responds_to_contain()
    {
        $this->supports('contain', [], [''])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentValue(Argument::any())
                  ->willReturn('countable');
        $presenter->presentString(Argument::any())
                  ->willReturnArgument();
        $this->beConstructedWith($presenter);
    }
}
