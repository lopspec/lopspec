<?php

namespace spec\LopSpec\Loader;

use LopSpec\Loader\Node\SpecificationNode;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class SuiteSpec extends ObjectBehavior
{
    function it_is_countable()
    {
        $this->shouldImplement('Countable');
    }
    function it_provides_a_count_of_examples(SpecificationNode $spec)
    {
        $this->addSpecification($spec);
        $this->addSpecification($spec);
        $this->addSpecification($spec);
        $spec->count(Argument::any())
             ->willReturn(5);
        $this->count()
             ->shouldReturn(15);
    }
    function it_provides_a_link_to_specifications(SpecificationNode $spec)
    {
        $this->addSpecification($spec);
        $this->addSpecification($spec);
        $this->addSpecification($spec);
        $this->getSpecifications()
             ->shouldReturn([$spec, $spec, $spec]);
    }
}
