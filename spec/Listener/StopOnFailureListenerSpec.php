<?php

namespace spec\LopSpec\Listener;

use LopSpec\Console\IO;
use LopSpec\Event\ExampleEvent;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class StopOnFailureListenerSpec extends ObjectBehavior
{
    function it_does_not_throw_an_exception_when_an_example_breaks_and_option_is_not_set(
        ExampleEvent $event
    )
    {
        $event->getResult()
              ->willReturn(ExampleEvent::BROKEN);
        $this->afterExample($event);
    }
    function it_does_not_throw_an_exception_when_an_example_fails_and_option_is_not_set(
        ExampleEvent $event
    )
    {
        $event->getResult()
              ->willReturn(ExampleEvent::FAILED);

        $this->afterExample($event);
    }
    function it_does_not_throw_any_exception_for_unimplemented_examples(ExampleEvent $event)
    {
        $event->getResult()->willReturn(ExampleEvent::PENDING);

        $this->afterExample($event);
    }
    function it_does_not_throw_any_exception_when_example_succeeds(
        ExampleEvent $event
    )
    {
        $event->getResult()
              ->willReturn(ExampleEvent::PASSED);
        $this->afterExample($event);
    }
    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }
    function it_throws_an_exception_when_an_example_breaks_and_option_is_set(ExampleEvent $event, $io)
    {
        $io->isStopOnFailureEnabled()->willReturn(true);
        $event->getResult()->willReturn(ExampleEvent::BROKEN);
        $this->shouldThrow('\LopSpec\Exception\Example\StopOnFailureException')
             ->duringAfterExample($event);
    }
    function it_throws_an_exception_when_an_example_fails_and_option_is_set(
        ExampleEvent $event,
        $io
    )
    {
        $io->isStopOnFailureEnabled()
           ->willReturn(true);
        $event->getResult()
              ->willReturn(ExampleEvent::FAILED);
        $this->shouldThrow('\LopSpec\Exception\Example\StopOnFailureException')
             ->duringAfterExample($event);
    }
    function let(IO $io)
    {
        $io->isStopOnFailureEnabled()
           ->willReturn(false);
        $this->beConstructedWith($io);
    }
}
