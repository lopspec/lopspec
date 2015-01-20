<?php

namespace spec\LopSpec\Exception\Example;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class StopOnFailureExceptionSpec extends ObjectBehavior
{
    function it_has_a_the_result_of_the_last_spec()
    {
        $this->getResult()
             ->shouldReturn(1);
    }
    function it_is_an_example_exception()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Example\ExampleException');
    }
    function let()
    {
        $this->beConstructedWith('Message', 0, null, 1);
    }
}
