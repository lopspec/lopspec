<?php

namespace spec\LopSpec\Formatter;

use LopSpec\Formatter\BasicFormatter;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\IO\IOInterface;
use LopSpec\Listener\StatisticsCollector;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class BasicFormatterSpec extends ObjectBehavior
{
    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }
    function it_returns_a_list_of_subscribed_events()
    {
        $this::getSubscribedEvents()->shouldBe([
                'beforeSuite' => 'beforeSuite',
                'afterSuite' => 'afterSuite',
                'beforeExample' => 'beforeExample',
                'afterExample' => 'afterExample',
                'beforeSpecification' => 'beforeSpecification',
                'afterSpecification' => 'afterSpecification'
            ]
        );
    }
    function let(
        PresenterInterface $presenter,
        IOInterface $io,
        StatisticsCollector $stats
    ) {
        $this->beAnInstanceOf('spec\LopSpec\Formatter\TestableBasicFormatter');
        $this->beConstructedWith($presenter, $io, $stats);
    }
}

class TestableBasicFormatter extends BasicFormatter
{
}
