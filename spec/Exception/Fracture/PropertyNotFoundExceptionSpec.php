<?php

namespace spec\LopSpec\Exception\Fracture;

use LopSpec\ObjectBehavior;

class PropertyNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_fracture()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Fracture\FractureException');
    }
    function it_provides_a_link_to_property()
    {
        $this->getProperty()
             ->shouldReturn('attributes');
    }
    function it_provides_a_link_to_subject($subject)
    {
        $this->getSubject()->shouldReturn($subject);
    }
    function let($subject)
    {
        $this->beConstructedWith('No method', $subject, 'attributes');
    }
}
