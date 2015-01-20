<?php

namespace spec\LopSpec\Matcher;

use ArrayObject;
use LopSpec\Exception\Example\FailureException;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class TypeMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_wrong_class(ArrayObject $object)
    {
        $this->shouldThrow(new FailureException('Expected an instance of stdClass, but got object.'))
             ->duringPositiveMatch('haveType', $object, ['stdClass']);
    }
    function it_does_not_match_wrong_interface(ArrayObject $object)
    {
        $this->shouldThrow(new FailureException('Expected an instance of SessionHandlerInterface, but got object.'))
             ->duringPositiveMatch('haveType', $object,
                 ['SessionHandlerInterface']);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_interface_instance(ArrayObject $object)
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('haveType', $object, ['ArrayAccess']);
    }
    function it_matches_other_class(ArrayObject $object)
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('haveType', $object, ['stdClass']);
    }
    function it_matches_other_interface()
    {
        $this->shouldNotThrow()
            ->duringNegativeMatch('haveType', $this,
                ['SessionHandlerInterface']);
    }
    function it_matches_subclass_instance(ArrayObject $object)
    {
        $this->shouldNotThrow()
            ->duringPositiveMatch('haveType', $object, ['ArrayObject']);
    }
    function it_responds_to_beAnInstanceOf()
    {
        $this->supports('beAnInstanceOf', '', [''])
             ->shouldReturn(true);
    }
    function it_responds_to_haveType()
    {
        $this->supports('haveType', '', [''])
             ->shouldReturn(true);
    }
    function it_responds_to_returnAnInstanceOf()
    {
        $this->supports('returnAnInstanceOf', '', [''])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentString(Argument::any())
                  ->willReturnArgument();
        $presenter->presentValue(Argument::any())
                  ->willReturn('object');
        $this->beConstructedWith($presenter);
    }
}
