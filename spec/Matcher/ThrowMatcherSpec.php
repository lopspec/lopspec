<?php

namespace spec\LopSpec\Matcher;

use ArrayObject;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Unwrapper;
use Prophecy\Argument;

class ThrowMatcherSpec extends ObjectBehavior
{
    function it_accepts_a_method_during_which_an_exception_should_be_thrown(
        ArrayObject $arr
    )
    {
        $arr->ksort()
            ->willThrow('\Exception');
        $this->positiveMatch('throw', $arr, ['\Exception'])
             ->during('ksort', []);
    }
    function it_accepts_a_method_during_which_an_exception_should_not_be_thrown(
        ArrayObject $arr
    )
    {
        $this->negativeMatch('throw', $arr, ['\Exception'])
             ->during('ksort', []);
    }
    function it_supports_the_throw_alias_for_object_and_exception_name()
    {
        $this->supports('throw', '', [])
             ->shouldReturn(true);
    }
    function let(Unwrapper $unwrapper, PresenterInterface $presenter)
    {
        $unwrapper->unwrapAll(Argument::any())
                  ->willReturnArgument();
        $presenter->presentValue(Argument::any())
                  ->willReturn('val1', 'val2');
        $this->beConstructedWith($unwrapper, $presenter);
    }
}
