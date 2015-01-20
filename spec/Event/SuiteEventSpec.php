<?php

namespace spec\LopSpec\Event;

use LopSpec\Event\ExampleEvent as Example;
use LopSpec\Loader\Suite;
use LopSpec\ObjectBehavior;

class SuiteEventSpec extends ObjectBehavior
{
    function it_can_be_told_that_the_suite_is_worth_rerunning()
    {
        $this->markAsWorthRerunning();
        $this->isWorthRerunning()
             ->shouldReturn(true);
    }
    function it_defaults_to_saying_suite_is_not_worth_rerunning()
    {
        $this->isWorthRerunning()
             ->shouldReturn(false);
    }
    function it_is_an_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
        $this->shouldBeAnInstanceOf('LopSpec\Event\EventInterface');
    }
    function it_provides_a_link_to_result()
    {
        $this->getResult()
             ->shouldReturn(Example::FAILED);
    }
    function it_provides_a_link_to_suite($suite)
    {
        $this->getSuite()->shouldReturn($suite);
    }
    function it_provides_a_link_to_time()
    {
        $this->getTime()->shouldReturn(10);
    }
    function let(Suite $suite)
    {
        $this->beConstructedWith($suite, 10, Example::FAILED);
    }
}
