<?php

namespace spec\LopSpec\Runner;

use LopSpec\Event\ExampleEvent;
use LopSpec\Exception\Example\StopOnFailureException;
use LopSpec\Loader\Node\SpecificationNode;
use LopSpec\Loader\Suite;
use LopSpec\ObjectBehavior;
use LopSpec\Runner\SpecificationRunner;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SuiteRunnerSpec extends ObjectBehavior
{
    function it_dispatches_afterSuite_event_with_result_and_time(
        $suite,
        $specRunner,
        $dispatcher
    )
    {
        $specRunner->run(Argument::any())
                   ->will(function () {
                       // Wait a few microseconds to ensure that the spec passes even on fast machines
                       usleep(10);

                       return ExampleEvent::FAILED;
                   });

        $this->run($suite);
        $dispatcher->dispatch('afterSuite', Argument::that(function ($event) {
            return ($event->getTime() > 0)
                   && ($event->getResult() == ExampleEvent::FAILED);
        }))
                   ->shouldHaveBeenCalled();
    }
    function it_dispatches_events_before_and_after_the_suite(
        $suite,
        $dispatcher
    )
    {
        $this->run($suite);
        $dispatcher->dispatch('beforeSuite',
            Argument::type('LopSpec\Event\SuiteEvent'))
                   ->shouldHaveBeenCalled();
        $dispatcher->dispatch('afterSuite',
            Argument::type('LopSpec\Event\SuiteEvent'))
                   ->shouldHaveBeenCalled();
    }
    function it_returns_a_broken_result_when_one_spec_is_broken($suite, $specRunner, $spec1, $spec2)
    {
        $specRunner->run($spec1)->willReturn(ExampleEvent::FAILED);
        $specRunner->run($spec2)->willReturn(ExampleEvent::BROKEN);

        $this->run($suite)->shouldReturn(ExampleEvent::BROKEN);
    }
    function it_returns_a_failed_result_when_one_spec_failed($suite, $specRunner, $spec1, $spec2)
    {
        $specRunner->run($spec1)->willReturn(ExampleEvent::FAILED);
        $specRunner->run($spec2)->willReturn(ExampleEvent::PENDING);

        $this->run($suite)->shouldReturn(ExampleEvent::FAILED);
    }
    function it_returns_a_successful_result_when_all_specs_in_suite_pass(
        $suite,
        $specRunner,
        $spec1,
        $spec2
    ) {
        $specRunner->run($spec1)
                   ->willReturn(ExampleEvent::PASSED);
        $specRunner->run($spec2)
                   ->willReturn(ExampleEvent::PASSED);
        $this->run($suite)
             ->shouldReturn(ExampleEvent::PASSED);
    }
    function it_runs_all_specs_in_the_suite_through_the_specrunner(
        $suite,
        $specRunner,
        $spec1,
        $spec2
    )
    {
        $this->run($suite);
        $specRunner->run($spec1)
                   ->shouldHaveBeenCalled();
        $specRunner->run($spec2)
                   ->shouldHaveBeenCalled();
    }
    function it_stops_running_subsequent_specs_when_a_spec_throws_a_StopOnFailureException(
        $suite,
        $specRunner,
        $spec1,
        $spec2
    )
    {
        $specRunner->run($spec1)
                   ->willThrow(new StopOnFailureException());

        $this->run($suite);
        $specRunner->run($spec2)
                   ->shouldNotBeenCalled();
    }
    function let(
        EventDispatcher $dispatcher,
        SpecificationRunner $specRunner,
        Suite $suite,
        SpecificationNode $spec1,
        SpecificationNode $spec2
    ) {
        $this->beConstructedWith($dispatcher, $specRunner);
        $suite->getSpecifications()
              ->willReturn([$spec1, $spec2]);
    }
}
