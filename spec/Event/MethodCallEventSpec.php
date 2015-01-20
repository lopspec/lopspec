<?php

namespace spec\LopSpec\Event;

use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Loader\Node\SpecificationNode;
use LopSpec\Loader\Suite;
use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Subject;
use Prophecy\Argument;

class MethodCallEventSpec extends ObjectBehavior
{
    function it_is_an_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
        $this->shouldBeAnInstanceOf('LopSpec\Event\EventInterface');
    }
    function it_provides_a_link_to_arguments()
    {
        $this->getArguments()
             ->shouldReturn(['methodArguments']);
    }
    function it_provides_a_link_to_example($example)
    {
        $this->getExample()->shouldReturn($example);
    }
    function it_provides_a_link_to_method()
    {
        $this->getMethod()
             ->shouldReturn('calledMethod');
    }
    function it_provides_a_link_to_return_value()
    {
        $this->getReturnValue()
             ->shouldReturn('returned value');
    }
    function it_provides_a_link_to_specification($specification)
    {
        $this->getSpecification()
             ->shouldReturn($specification);
    }
    function it_provides_a_link_to_subject($subject)
    {
        $this->getSubject()->shouldReturn($subject);
    }
    function it_provides_a_link_to_suite($suite)
    {
        $this->getSuite()
             ->shouldReturn($suite);
    }
    function let(
        Suite $suite,
        SpecificationNode $specification,
        ExampleNode $example,
        $subject
    )
    {
        $method = 'calledMethod';
        $arguments = ['methodArguments'];
        $returnValue = 'returned value';
        $this->beConstructedWith($example, $subject, $method, $arguments,
            $returnValue);
        $example->getSpecification()
                ->willReturn($specification);
        $specification->getSuite()
                      ->willReturn($suite);
    }
}
