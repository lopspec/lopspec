<?php

namespace spec\LopSpec\Formatter;

use LopSpec\Event\ExampleEvent;
use LopSpec\Formatter\Html\IO;
use LopSpec\Formatter\Html\ReportItem;
use LopSpec\Formatter\Html\ReportItemFactory;
use LopSpec\Formatter\Presenter\PresenterInterface as Presenter;
use LopSpec\Listener\StatisticsCollector;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlFormatterSpec extends ObjectBehavior
{
    const EVENT_TITLE = 'it works';
    function it_delegates_the_reporting_to_the_event_type_line_reporter(
        ExampleEvent $event, ReportItem $item, ReportItemFactory $factory,
        Presenter $presenter)
    {
        $factory->create($event, $presenter)->willReturn($item);
        $item->write(Argument::any())->shouldBeCalled();
        $this->afterExample($event);
    }
    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }
    function let(
        ReportItemFactory $factory,
        Presenter $presenter,
        IO $io,
        StatisticsCollector $stats
    ) {
        $this->beConstructedWith($factory, $presenter, $io, $stats);
    }
}
