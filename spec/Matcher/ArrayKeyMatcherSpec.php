<?php

namespace spec\LopSpec\Matcher;

use ArrayObject;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayKeyMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_ArrayObject_without_provided_offset(
        ArrayObject $array
    )
    {
        $array->offsetExists('abc')
              ->willReturn(false);
        $this->shouldThrow()
             ->duringPositiveMatch('haveKey', $array, ['abc']);
    }
    function it_does_not_match_array_without_specified_key()
    {
        $this->shouldThrow()
             ->duringPositiveMatch('haveKey', [1, 2, 3], ['abc']);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_ArrayObject_with_provided_offset(ArrayObject $array)
    {
        $array->offsetExists('abc')->willReturn(true);
        $this->shouldNotThrow()
             ->duringPositiveMatch('haveKey', $array, ['abc']);
    }
    function it_matches_ArrayObject_without_specified_offset(ArrayObject $array)
    {
        $array->offsetExists('abc')
              ->willReturn(false);
        $this->shouldNotThrow()
             ->duringNegativeMatch('haveKey', $array, ['abc']);
    }
    function it_matches_array_with_specified_key()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('haveKey', ['abc' => 123], ['abc']);
    }
    function it_matches_array_with_specified_key_even_if_there_is_no_value()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('haveKey', ['abc' => null], ['abc']);
    }
    function it_matches_array_without_specified_key()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('haveKey', [1, 2, 3], ['abc']);
    }
    function it_responds_to_haveKey()
    {
        $this->supports('haveKey', [], [''])
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
