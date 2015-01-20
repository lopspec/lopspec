<?php

namespace spec\LopSpec\Matcher;

use LopSpec\Exception\Example\FailureException;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ComparisonMatcherSpec extends ObjectBehavior
{
    function it_does_not_match_non_empty_different_value()
    {
        $this->shouldThrow(new FailureException('Expected val1, but got val2.'))
             ->duringPositiveMatch('beLike', 'one_value', ['different_value']);
    }
    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Matcher\MatcherInterface');
    }
    function it_matches_empty_string_using_comparison_operator()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('beLike', '', ['']);
    }
    function it_matches_empty_string_with_emptish_values_using_comparison_operator(
    )
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('beLike', '', [0]);
    }
    function it_matches_false_with_emptish_values_using_comparison_operator()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('beLike', false, ['']);
    }
    function it_matches_not_empty_string_using_comparison_operator()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('beLike', 'chuck', ['chuck']);
    }
    function it_matches_null_with_emptish_values_using_comparison_operator()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('beLike', null, ['']);
    }
    function it_matches_zero_with_emptish_values_using_comparison_operator()
    {
        $this->shouldNotThrow()
             ->duringPositiveMatch('beLike', 0, ['']);
    }
    function it_mismatches_empty_string_using_comparison_operator()
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
            ->duringNegativeMatch('beLike', '', ['']);
    }
    function it_mismatches_empty_string_with_emptish_values_using_comparison_operator(
    )
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
            ->duringNegativeMatch('beLike', '', ['']);
    }
    function it_mismatches_false_with_emptish_values_using_comparison_operator()
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
            ->duringNegativeMatch('beLike', false, ['']);
    }
    function it_mismatches_not_empty_string_using_comparison_operator($matcher)
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
            ->duringNegativeMatch('beLike', 'chuck', ['chuck']);
    }
    function it_mismatches_null_with_emptish_values_using_comparison_operator()
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
            ->duringNegativeMatch('beLike', null, ['']);
    }
    function it_mismatches_on_non_empty_different_value()
    {
        $this->shouldNotThrow()
             ->duringNegativeMatch('beLike', 'one_value', ['another']);
    }
    function it_mismatches_zero_with_emptish_values_using_comparison_operator()
    {
        $this->shouldThrow(new FailureException('Did not expect val1, but got one.'))
            ->duringNegativeMatch('beLike', 0, ['']);
    }
    function it_responds_to_beLike()
    {
        $this->supports('beLike', '', [''])
             ->shouldReturn(true);
    }
    function let(PresenterInterface $presenter)
    {
        $presenter->presentValue(Argument::any())
                  ->willReturn('val1', 'val2');
        $this->beConstructedWith($presenter);
    }
}
