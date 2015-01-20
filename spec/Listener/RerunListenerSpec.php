<?php

namespace spec\LopSpec\Listener;

use LopSpec\Event\SuiteEvent;
use LopSpec\ObjectBehavior;
use LopSpec\Process\ReRunner;
use Prophecy\Argument;

class RerunListenerSpec extends ObjectBehavior
{
    function it_does_not_tell_the_rerunner_to_rerun_if_it_is_not_worth_doing_so(SuiteEvent $suiteEvent, ReRunner $reRunner)
    {
        $suiteEvent->isWorthRerunning()->willReturn(false);

        $this->afterSuite($suiteEvent);

        $reRunner->reRunSuite()->shouldNotHaveBeenCalled();
    }
    function it_subscribes_to_aftersuite()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
        $this->getSubscribedEvents()
             ->shouldHaveKey('afterSuite');
    }
    function it_tells_the_rerunner_to_rerun_if_it_is_worth_doing_so(SuiteEvent $suiteEvent, ReRunner $reRunner)
    {
        $suiteEvent->isWorthRerunning()->willReturn(true);

        $this->afterSuite($suiteEvent);

        $reRunner->reRunSuite()->shouldHaveBeenCalled();
    }
    function let(ReRunner $reRunner)
    {
        $this->beConstructedWith($reRunner);
    }
}
