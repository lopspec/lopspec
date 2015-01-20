<?php

namespace spec\LopSpec\Formatter\Presenter\Differ;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayEngineSpec extends ObjectBehavior
{
    function it_does_not_support_anything_else()
    {
        $this->supports('str', 2)
             ->shouldReturn(false);
    }
    function it_is_a_diff_engine()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Formatter\Presenter\Differ\EngineInterface');
    }
    function it_supports_arrays()
    {
        $this->supports([], [1, 2, 3])
             ->shouldReturn(true);
    }
}
