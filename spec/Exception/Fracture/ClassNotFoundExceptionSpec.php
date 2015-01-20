<?php

namespace spec\LopSpec\Exception\Fracture;

use LopSpec\ObjectBehavior;

class ClassNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_fracture()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Fracture\FractureException');
    }
    function it_provides_a_link_to_classname()
    {
        $this->getClassname()->shouldReturn('stdClass');
    }
    function let()
    {
        $this->beConstructedWith('Not equal', 'stdClass');
    }
}
