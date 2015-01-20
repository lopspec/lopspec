<?php

namespace spec\LopSpec\Loader\Node;

use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Loader\Suite;
use LopSpec\Locator\ResourceInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;
use ReflectionClass;

class SpecificationNodeSpec extends ObjectBehavior
{
    function it_is_countable()
    {
        $this->shouldImplement('Countable');
    }
    function it_provides_a_count_of_examples(ExampleNode $example)
    {
        $this->addExample($example);
        $this->addExample($example);
        $this->addExample($example);
        $this->count()
             ->shouldReturn(3);
    }
    function it_provides_a_link_to_class($class)
    {
        $this->getClassReflection()->shouldReturn($class);
    }
    function it_provides_a_link_to_examples(ExampleNode $example)
    {
        $this->addExample($example);
        $this->addExample($example);
        $this->addExample($example);
        $this->getExamples()
             ->shouldReturn([$example, $example, $example]);
    }
    function it_provides_a_link_to_resource($resource)
    {
        $this->getResource()->shouldReturn($resource);
    }

    function it_provides_a_link_to_suite(Suite $suite)
    {
        $this->setSuite($suite);
        $this->getSuite()->shouldReturn($suite);
    }
    function it_provides_a_link_to_title()
    {
        $this->getTitle()
             ->shouldReturn('specification node');
    }
    public function let(ReflectionClass $class, ResourceInterface $resource)
    {
        $this->beConstructedWith('specification node', $class, $resource);
    }
}
