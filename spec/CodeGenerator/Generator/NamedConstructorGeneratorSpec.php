<?php

namespace spec\LopSpec\CodeGenerator\Generator;

use LopSpec\CodeGenerator\TemplateRenderer;
use LopSpec\Console\IO;
use LopSpec\Locator\ResourceInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Util\Filesystem;
use Prophecy\Argument;

class NamedConstructorGeneratorSpec extends ObjectBehavior
{
    function it_does_not_support_anything_else(ResourceInterface $resource)
    {
        $this->supports($resource, 'anything_else', [])
             ->shouldReturn(false);
    }
    function it_generates_static_constructor_method_from_resource($io, $tpl, $fs, ResourceInterface $resource)
    {
        $codeWithoutMethod = <<<CODE
<?php

namespace Acme;

class App
{
}

CODE;
        $codeWithMethod = <<<CODE
<?php

namespace Acme;

class App
{
METHOD
}

CODE;
        $values = [
            '%methodName%' => 'setName',
            '%arguments%'  => '$argument1',
            '%returnVar%'  => '$app',
            '%className%'  => 'App',
            '%constructorArguments%' => ''
        ];

        $resource->getSrcFilename()->willReturn('/project/src/Acme/App.php');
        $resource->getSrcClassname()->willReturn('Acme\App');
        $resource->getName()->willReturn('App');

        $tpl->render('named_constructor_create_object', $values)->willReturn(null);
        $tpl->renderString(Argument::type('string'), $values)->willReturn('METHOD');

        $fs->getFileContents('/project/src/Acme/App.php')->willReturn($codeWithoutMethod);
        $fs->putFileContents('/project/src/Acme/App.php', $codeWithMethod)->shouldBeCalled();
        $this->generate($resource,
            ['name' => 'setName', 'arguments' => ['jmurphy']]);
    }
    function it_is_a_generator()
    {
        $this->shouldBeAnInstanceOf('LopSpec\CodeGenerator\Generator\GeneratorInterface');
    }
    function it_supports_static_constructor_generation(
        ResourceInterface $resource
    ) {
        $this->supports($resource, 'named_constructor', [])
             ->shouldReturn(true);
    }
    function its_priority_is_0()
    {
        $this->getPriority()
             ->shouldReturn(0);
    }
    function let(IO $io, TemplateRenderer $tpl, Filesystem $fs)
    {
        $this->beConstructedWith($io, $tpl, $fs);
    }
}
