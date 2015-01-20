<?php

namespace spec\LopSpec\Exception\Example;

use LopSpec\ObjectBehavior;

class NotEqualExceptionSpec extends ObjectBehavior
{
    function it_is_failure()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Example\FailureException');
    }
    function it_provides_a_link_to_actual()
    {
        $this->getActual()
             ->shouldReturn(5);
    }
    function it_provides_a_link_to_expected()
    {
        $this->getExpected()->shouldReturn(2);
    }
    function let()
    {
        $this->beConstructedWith('Not equal', 2, 5);
    }
}
