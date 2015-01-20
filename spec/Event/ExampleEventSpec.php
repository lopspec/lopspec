<?php

namespace spec\LopSpec\Event;

use Exception;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Loader\Node\SpecificationNode;
use LopSpec\Loader\Suite;
use LopSpec\ObjectBehavior;

class ExampleEventSpec extends ObjectBehavior
{
    function it_is_an_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
        $this->shouldBeAnInstanceOf('LopSpec\Event\EventInterface');
    }
    function it_provides_a_link_to_example($example)
    {
        $this->getExample()->shouldReturn($example);
    }
    function it_provides_a_link_to_exception($exception)
    {
        $this->getException()
             ->shouldReturn($exception);
    }
    function it_provides_a_link_to_result()
    {
        $this->getResult()
             ->shouldReturn($this->FAILED);
    }
    function it_provides_a_link_to_specification($specification)
    {
        $this->getSpecification()->shouldReturn($specification);
    }
    function it_provides_a_link_to_suite($suite)
    {
        $this->getSuite()->shouldReturn($suite);
    }
    function it_provides_a_link_to_time()
    {
        $this->getTime()->shouldReturn(10);
    }
    function let(
        Suite $suite,
        SpecificationNode $specification,
        ExampleNode $example,
        Exception $exception
    )
    {
        $this->beConstructedWith($example, 10, $this->FAILED, $exception);
        $example->getSpecification()
                ->willReturn($specification);
        $specification->getSuite()
                      ->willReturn($suite);
    }
}
