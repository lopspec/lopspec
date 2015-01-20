<?php

namespace spec\LopSpec\Exception\Fracture;

use LopSpec\ObjectBehavior;

class InterfaceNotImplementedExceptionSpec extends ObjectBehavior
{
    function it_is_fracture()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Fracture\FractureException');
    }
    function it_provides_a_link_to_interface()
    {
        $this->getInterface()
             ->shouldReturn('ArrayAccess');
    }
    function it_provides_a_link_to_subject($subject)
    {
        $this->getSubject()->shouldReturn($subject);
    }
    function let($subject)
    {
        $this->beConstructedWith('Not equal', $subject, 'ArrayAccess');
    }
}
