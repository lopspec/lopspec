<?php

namespace spec\LopSpec\Formatter\Html;

use LopSpec\Event\ExampleEvent;
use LopSpec\Formatter\Html\Template;
use LopSpec\Formatter\Presenter\PresenterInterface as Presenter;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportFailedItemSpec extends ObjectBehavior
{
    const BACKTRACE = "#42 /some/path/to/SomeException.php";
    const CODE = 'code';
    const EVENT_MESSAGE = 'oops';
    const EVENT_TITLE = 'it does not works';
    function it_writes_a_fail_message_for_a_failing_example(Template $template, ExampleEvent $event, Presenter $presenter)
    {
        $event->getTitle()->willReturn(self::EVENT_TITLE);
        $event->getMessage()->willReturn(self::EVENT_MESSAGE);
        $event->getBacktrace()->willReturn(self::$BACKTRACE);
        $event->getException()->willReturn(new \Exception());
        $template->render(Template::DIR . '/Template/ReportFailed.html', [
            'title' => self::EVENT_TITLE,
            'message' => self::EVENT_MESSAGE,
            'backtrace' => self::BACKTRACE,
            'code' => self::CODE,
            'index' => 1,
            'specification' => 1
        ])
                 ->shouldBeCalled();
        $presenter->presentException(Argument::cetera())->willReturn(self::CODE);
        $this->write(1);
    }
    function let(Template $template, ExampleEvent $event, Presenter $presenter)
    {
        $this->beConstructedWith($template, $event, $presenter);
    }
    static $BACKTRACE
        = [
            ['line' => 42, 'file' => '/some/path/to/SomeException.php']
        ];
}
