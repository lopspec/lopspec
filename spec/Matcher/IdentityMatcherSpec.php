<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Exception\Example\FailureException;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class IdentityMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_empty_string_with_emptish_values()
    {
        $this->shouldThrow(new FailureException('Expected val1, but got val2.'))
             ->duringPositiveMatch('be', '', [false]);
    }
    function it_does_not_match_false_with_emptish_values()
    {
        $this->shouldThrow(new FailureException('Expected val1, but got val2.'))
             ->duringPositiveMatch('be', false, ['']);
    }
    function it_does_not_match_non_empty_different_value()
    {
        $this->shouldThrow(new FailureException('Expected val1, but got val2.'))
             ->duringPositiveMatch('be', 'one', ['two']);
    }
    function it_does_not_match_null_with_emptish_values()
    {
        $this->shouldThrow(new FailureException('Expected val1, but got val2.'))
             ->duringPositiveMatch('be', null, [false]);
    }
    function it_does_not_match_zero_with_emptish_values()
    {
        $this->shouldThrow(new FailureException('Expected val1, but got val2.'))
             ->duringPositiveMatch('be', 0, [false]);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_empty_strings()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('be', '', ['']);
    }

    function it_matches_not_empty_strings()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('be', 'chuck', ['chuck']);
    }
    function it_mismatches_empty_string()
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
             ->duringNegativeMatch('be', '', ['']);
    }
    function it_mismatches_empty_string_with_emptish_values()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('be', '', [false]);
    }
    function it_mismatches_false_with_emptish_values_using_identity_operator()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('be', false, ['']);
    }
    function it_mismatches_not_empty_string($matcher)
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
             ->duringNegativeMatch('be', 'chuck', ['chuck']);
    }
    function it_mismatches_null_with_emptish_values_using_identity_operator()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('be', null, [false]);
    }
    function it_mismatches_on_non_empty_different_value()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('be', 'one', ['two']);
    }
    function it_mismatches_zero_with_emptish_values_using_identity_operator()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('be', 0, [false]);
    }
    function it_responds_to_be()
    {
        $this->supports('be', '', [''])
             ->shouldReturn(true);
    }
    function it_responds_to_beEqualTo()
    {
        $this->supports('beEqualTo', '', [''])
             ->shouldReturn(true);
    }
    function it_responds_to_equal()
    {
        $this->supports('equal', '', [''])
             ->shouldReturn(true);
    }
    function it_responds_to_return()
    {
        $this->supports('return', '', [''])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentValue(Argument::any())
                  ->willReturn('val1', 'val2');
        $this->beConstructedWith($presenter);
    }
}
