<?php

namespace spec\LopSpec\Exception;

use LopSpec\ObjectBehavior;
use ReflectionMethod;

class ExceptionSpec extends ObjectBehavior
{
    function it_could_have_a_cause(ReflectionMethod $cause)
    {
        $this->setCause($cause);
        $this->getCause()->shouldReturn($cause);
    }
    function it_extends_basic_exception()
    {
        $this->shouldBeAnInstanceOf('Exception');
    }
}
