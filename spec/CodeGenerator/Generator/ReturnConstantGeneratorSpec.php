<?php

namespace spec\LopSpec\CodeGenerator\Generator;

use LopSpec\CodeGenerator\TemplateRenderer;
use LopSpec\Console\IO;
use LopSpec\Locator\ResourceInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Util\Filesystem;
use Prophecy\Argument;

class ReturnConstantGeneratorSpec extends ObjectBehavior
{
    function it_does_not_support_anything_else(ResourceInterface $resource)
    {
        $this->supports($resource, 'anything_else', [])
             ->shouldReturn(false);
    }
    function it_is_a_generator()
    {
        $this->shouldHaveType('LopSpec\CodeGenerator\Generator\GeneratorInterface');
    }

    function it_supports_returnConstant_generation(ResourceInterface $resource)
    {
        $this->supports($resource, 'returnConstant', [])
             ->shouldReturn(true);
    }
    function its_priority_is_0()
    {
        $this->getPriority()->shouldReturn(0);
    }
    function let(IO $io, TemplateRenderer $templates, Filesystem $filesystem)
    {
        $this->beConstructedWith($io, $templates, $filesystem);
    }
}
