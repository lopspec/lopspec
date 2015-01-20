<?php

namespace spec\LopSpec\Process\ReRunner;

use LopSpec\ObjectBehavior;
use LopSpec\Process\ReRunner;
use LopSpec\Process\ReRunner\PlatformSpecificReRunner;
use Prophecy\Argument;

class CompositeReRunnerSpec extends ObjectBehavior
{
    function it_invokes_the_first_supported_child_to_rerun_the_suite_even_if_later_children_are_supported(
        PlatformSpecificReRunner $reRunner1, PlatformSpecificReRunner $reRunner2
    ) {
        $reRunner1->isSupported()->willReturn(true);
        $reRunner2->isSupported()->willReturn(true);

        $reRunner1->reRunSuite()->shouldBeCalled();

        $this->reRunSuite();

        $reRunner1->reRunSuite()->shouldHaveBeenCalled();
        $reRunner2->reRunSuite()->shouldNotHaveBeenCalled();
    }
    function it_is_a_rerunner()
    {
        $this->shouldHaveType('LopSpec\Process\ReRunner');
    }
    function it_skips_early_child_if_it_is_not_supported_and_invokes_runsuite_on_later_supported_child(
        PlatformSpecificReRunner $reRunner1, PlatformSpecificReRunner $reRunner2
    ) {
        $reRunner1->isSupported()->willReturn(false);
        $reRunner2->isSupported()->willReturn(true);

        $reRunner2->reRunSuite()->willReturn();

        $this->reRunSuite();

        $reRunner1->reRunSuite()->shouldNotHaveBeenCalled();
        $reRunner2->reRunSuite()->shouldHaveBeenCalled();
    }
    function let(
        PlatformSpecificReRunner $reRunner1,
        PlatformSpecificReRunner $reRunner2
    ) {
        $this->beConstructedWith([
                $reRunner1->getWrappedObject(),
                $reRunner2->getWrappedObject()
            ]);
    }
}
