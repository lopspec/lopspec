<?php

namespace spec\LopSpec\Exception\Fracture;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodNotVisibleExceptionSpec extends ObjectBehavior
{
    function it_is_fracture()
    {
        $this->shouldBeAnInstanceOf('LopSpec\Exception\Fracture\FractureException');
    }
    function it_provides_a_link_to_arguments()
    {
        $this->getArguments()
             ->shouldReturn(['everzet']);
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
        $this->beConstructedWith('No method', $subject, 'setName', ['everzet']);
    }
}
