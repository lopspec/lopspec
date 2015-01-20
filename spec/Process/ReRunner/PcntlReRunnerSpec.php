<?php

namespace spec\LopSpec\Process\ReRunner;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Process\PhpExecutableFinder;

class PcntlReRunnerSpec extends ObjectBehavior
{
    function it_is_a_rerunner()
    {
        $this->shouldHaveType('LopSpec\Process\ReRunner');
    }
    function it_is_not_supported_when_php_process_is_not_found(PhpExecutableFinder $executableFinder)
    {
        $executableFinder->find()->willReturn(false);

        $this->isSupported()->shouldReturn(false);
    }
    function let(PhpExecutableFinder $executableFinder)
    {
        $this->beConstructedWith($executableFinder);
    }
}
