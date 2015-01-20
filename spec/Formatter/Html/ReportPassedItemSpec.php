<?php

namespace spec\LopSpec\Formatter\Html;

use LopSpec\Event\ExampleEvent;
use LopSpec\Formatter\Html\Template;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportPassedItemSpec extends ObjectBehavior
{
    const EVENT_TITLE = 'it works';
    function it_writes_a_pass_message_for_a_passing_example(Template $template, ExampleEvent $event)
    {
        $event->getTitle()->willReturn(self::EVENT_TITLE);
        $template->render(Template::DIR . '/Template/ReportPass.html', [
            'title' => self::EVENT_TITLE
        ])
                 ->shouldBeCalled();
        $this->write();
    }
    function let(Template $template, ExampleEvent $event)
    {
        $this->beConstructedWith($template, $event);
    }
}
