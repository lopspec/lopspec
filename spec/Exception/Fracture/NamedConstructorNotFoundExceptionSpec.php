<?php

namespace spec\LopSpec\Exception\Fracture;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class NamedConstructorNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_fracture()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Fracture\FractureException');
    }
    function it_provides_a_link_to_arguments()
    {
        $this->getArguments()
             ->shouldReturn(['jmurphy']);
    }
    function it_provides_a_link_to_methodName()
    {
        $this->getMethodName()->shouldReturn('setName');
    }
    function it_provides_a_link_to_subject($subject)
    {
        $this->getSubject()
             ->shouldReturn($subject);
    }
    function let($subject)
    {
        $this->beConstructedWith('No named constructor', $subject, 'setName',
            ['jmurphy']);
    }
}
