<?php

namespace spec\LopSpec\Event;

use LopSpec\Event\ExampleEvent as Example;
use LopSpec\Loader\Node\SpecificationNode;
use LopSpec\Loader\Suite;
use LopSpec\ObjectBehavior;

class SpecificationEventSpec extends ObjectBehavior
{
    function it_is_an_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
        $this->shouldBeAnInstanceOf('LopSpec\Event\EventInterface');
    }
    function it_provides_a_link_to_result()
    {
        $this->getResult()
             ->shouldReturn(Example::FAILED);
    }
    function it_provides_a_link_to_specification($specification)
    {
        $this->getSpecification()->shouldReturn($specification);
    }
    function it_provides_a_link_to_suite($suite)
    {
        $this->getSuite()
             ->shouldReturn($suite);
    }
    function it_provides_a_link_to_time()
    {
        $this->getTime()->shouldReturn(10);
    }
    function let(Suite $suite, SpecificationNode $specification)
    {
        $this->beConstructedWith($specification, 10, Example::FAILED);
        $specification->getSuite()
                      ->willReturn($suite);
    }
}
