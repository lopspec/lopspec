<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectStateMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_if_has_state_checker_returns_false()
    {
        $subject = new \ReflectionClass($this);
        $this->shouldThrow('LopSpec\Exception\Example\FailureException')
             ->duringPositiveMatch('haveProperty', $subject, ['other']);
    }
    function it_does_not_match_if_state_checker_returns_false()
    {
        $subject = new \ReflectionClass($this);
        $this->shouldThrow('LopSpec\Exception\Example\FailureException')
             ->duringPositiveMatch('beFinal', $subject, []);
    }
    function it_does_not_match_if_subject_is_callable()
    {
        $subject = function () {
        };
        $this->supports('beCallable', $subject, [])
             ->shouldReturn(false);
    }
    function it_infers_matcher_alias_name_from_methods_prefixed_with_has()
    {
        $subject = new \ReflectionClass($this);
        $this->supports('haveProperty', $subject, ['something'])
             ->shouldReturn(true);
    }
    function it_infers_matcher_alias_name_from_methods_prefixed_with_is()
    {
        $subject = new \ReflectionClass($this);
        $this->supports('beAbstract', $subject, [])
             ->shouldReturn(true);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_if_has_checker_returns_true()
    {
        $subject = new \ReflectionClass($this);

        $this->shouldNotThrow()->duringPositiveMatch('haveMethod', $subject,
            ['it_matches_if_has_checker_returns_true']
        );
    }
    function it_matches_if_state_checker_returns_true()
    {
        $subject = new \ReflectionClass($this);
        $this->shouldNotThrow()
             ->duringPositiveMatch('beUserDefined', $subject, []);
    }
    function it_throws_exception_if_checker_method_not_found()
    {
        $subject = new \ReflectionClass($this);
        $this->shouldThrow('LopSpec\Exception\Fracture\MethodNotFoundException')
             ->duringPositiveMatch('beSimple', $subject, []);
    }
    function it_throws_exception_if_has_checker_method_not_found()
    {
        $subject = new \ReflectionClass($this);
        $this->shouldThrow('LopSpec\Exception\Fracture\MethodNotFoundException')
             ->duringPositiveMatch('haveAnything', $subject, ['str']);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentValue(Argument::any())
                  ->willReturn('val1', 'val2');
        $presenter->presentString(Argument::any())
                  ->willReturnArgument();
        $this->beConstructedWith($presenter);
    }
}
