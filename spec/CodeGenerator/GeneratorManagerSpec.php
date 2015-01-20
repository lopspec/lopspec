<?php

namespace spec\LopSpec\CodeGenerator;

use LopSpec\CodeGenerator\Generator\GeneratorInterface;
use LopSpec\Locator\ResourceInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class GeneratorManagerSpec extends ObjectBehavior
{
    function it_chooses_generator_by_priority(
        GeneratorInterface $generator1, GeneratorInterface $generator2, ResourceInterface $resource
    ) {
        $generator1->supports($resource, 'class', ['class' => 'CustomLoader'])
            ->willReturn(true);
        $generator1->getPriority()->willReturn(0);
        $generator2->supports($resource, 'class', ['class' => 'CustomLoader'])
            ->willReturn(true);
        $generator2->getPriority()->willReturn(2);

        $generator1->generate($resource, ['class' => 'CustomLoader'])->shouldNotBeCalled();
        $generator2->generate($resource, ['class' => 'CustomLoader'])->shouldBeCalled();

        $this->registerGenerator($generator1);
        $this->registerGenerator($generator2);
        $this->generate($resource, 'class', ['class' => 'CustomLoader']);
    }
    function it_throws_exception_if_no_generator_found(ResourceInterface $resource)
    {
        $this->shouldThrow()->duringGenerate($resource, 'class', ['class' => 'CustomLoader']);
    }
    function it_uses_registered_generators_to_generate_code(
        GeneratorInterface $generator, ResourceInterface $resource
    ) {
        $generator->getPriority()->willReturn(0);
        $generator->supports($resource, 'specification', [])->willReturn(true);
        $generator->generate($resource, [])->shouldBeCalled();

        $this->registerGenerator($generator);
        $this->generate($resource, 'specification');
    }
}
