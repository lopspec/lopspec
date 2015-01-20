<?php

namespace spec\LopSpec\Formatter\Html;

use LopSpec\Event\ExampleEvent;
use LopSpec\Formatter\Presenter\PresenterInterface as Presenter;
use LopSpec\Formatter\Template;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportItemFactorySpec extends ObjectBehavior
{
    function it_creates_a_ReportBrokenItem(
        ExampleEvent $event,
        Presenter $presenter
    )
    {
        $event->getResult()
              ->willReturn(ExampleEvent::BROKEN);
        $this->create($event, $presenter)
             ->shouldHaveType('LopSpec\Formatter\Html\ReportFailedItem');
    }
    function it_creates_a_ReportFailedItem(
        ExampleEvent $event,
        Presenter $presenter
    ) {
        $event->getResult()
              ->willReturn(ExampleEvent::FAILED);
        $this->create($event, $presenter)
             ->shouldHaveType('LopSpec\Formatter\Html\ReportFailedItem');
    }
    function it_creates_a_ReportPassedItem(ExampleEvent $event, Presenter $presenter)
    {
        $event->getResult()->willReturn(ExampleEvent::PASSED);
        $this->create($event, $presenter)
             ->shouldHaveType('LopSpec\Formatter\Html\ReportPassedItem');
    }
    function it_creates_a_ReportPendingItem(ExampleEvent $event, Presenter $presenter)
    {
        $event->getResult()->willReturn(ExampleEvent::PENDING);
        $this->create($event, $presenter)
             ->shouldHaveType('LopSpec\Formatter\Html\ReportPendingItem');
    }
    function let(Template $template)
    {
        $this->beConstructedWith($template);
    }
}
