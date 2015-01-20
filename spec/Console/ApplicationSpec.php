<?php

namespace spec\LopSpec\Console;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ApplicationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('LopSpec\Console\Application');
    }
    function let()
    {
        $this->beConstructedWith('test');
    }
}
