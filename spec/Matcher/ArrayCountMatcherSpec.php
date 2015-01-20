<?php

namespace spec\LopSpec\Matcher;

use ArrayObject;
use LopSpec\Exception\Example\FailureException;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayCountMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_proper_countable_count(ArrayObject $countable)
    {
        $countable->count()
                  ->willReturn(5);
        $this->shouldThrow(new FailureException('Expected countable to have 4 items, but got 5.'))
             ->duringPositiveMatch('haveCount', $countable, [4]);
    }
    function it_does_not_match_wrong_array_count()
    {
        $this->shouldThrow(new FailureException('Expected countable to have 2 items, but got 3.'))
             ->duringPositiveMatch('haveCount', [1, 2, 3], [2]);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_proper_array_count()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('haveCount', [1, 2, 3], [3]);
    }

    function it_matches_proper_countable_count(ArrayObject $countable)
    {
        $countable->count()->willReturn(4);
        $this->shouldNotThrow()
             ->duringPositiveMatch('haveCount', $countable, [4]);
    }
    function it_mismatches_wrong_array_count()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('haveCount', [1, 2, 3], [2]);
    }
    function it_mismatches_wrong_countable_count(ArrayObject $countable)
    {
        $countable->count()->willReturn(5);
        $this->shouldNotThrow()
             ->duringNegativeMatch('haveCount', $countable, [4]);
    }
    function it_responds_to_haveCount()
    {
        $this->supports('haveCount', [], [''])
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
