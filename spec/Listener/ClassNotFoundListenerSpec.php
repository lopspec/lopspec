<?php

namespace spec\LopSpec\Listener;

use LopSpec\CodeGenerator\GeneratorManager;
use LopSpec\Console\IO;
use LopSpec\Event\ExampleEvent;
use LopSpec\Event\SuiteEvent;
use LopSpec\Locator\ResourceManager;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Exception\Doubler\ClassNotFoundException as ProphecyClassException;

class ClassNotFoundListenerSpec extends ObjectBehavior
{
    function it_does_not_prompt_for_class_generation_if_input_is_not_interactive(
        $exampleEvent,
        $suiteEvent,
        $io,
        PhpspecClassException $exception
    )
    {
        $exampleEvent->getException()
                     ->willReturn($exception);
        $io->isCodeGenerationEnabled()
           ->willReturn(false);
        $this->afterExample($exampleEvent);
        $this->afterSuite($suiteEvent);
        $io->askConfirmation(Argument::any())
           ->shouldNotBeenCalled();
    }
    function it_does_not_prompt_for_class_generation_if_no_exception_was_thrown($exampleEvent, $suiteEvent, $io)
    {
        $io->isCodeGenerationEnabled()->willReturn(true);

        $this->afterExample($exampleEvent);
        $this->afterSuite($suiteEvent);

        $io->askConfirmation(Argument::any())->shouldNotBeenCalled();
    }

    function it_does_not_prompt_for_class_generation_if_non_class_exception_was_thrown($exampleEvent, $suiteEvent, $io, \InvalidArgumentException $exception)
    {
        $exampleEvent->getException()->willReturn($exception);
        $io->isCodeGenerationEnabled()->willReturn(true);

        $this->afterExample($exampleEvent);
        $this->afterSuite($suiteEvent);

        $io->askConfirmation(Argument::any())->shouldNotBeenCalled();
    }

    function it_prompts_for_class_generation_if_prophecy_classnotfoundexception_was_thrown_and_input_is_interactive($exampleEvent, $suiteEvent, $io, ProphecyClassException $exception)
    {
        $exampleEvent->getException()->willReturn($exception);
        $io->isCodeGenerationEnabled()->willReturn(true);

        $this->afterExample($exampleEvent);
        $this->afterSuite($suiteEvent);

        $io->askConfirmation(Argument::any())->shouldHaveBeenCalled();
    }

    function it_prompts_for_method_generation_if_phpspec_classnotfoundexception_was_thrown_and_input_is_interactive($exampleEvent, $suiteEvent, $io, PhpspecClassException $exception)
    {
        $exampleEvent->getException()->willReturn($exception);
        $io->isCodeGenerationEnabled()->willReturn(true);

        $this->afterExample($exampleEvent);
        $this->afterSuite($suiteEvent);

        $io->askConfirmation(Argument::any())->shouldHaveBeenCalled();
    }
    function let(
        IO $io,
        ResourceManager $resourceManager,
        GeneratorManager $generatorManager,
        SuiteEvent $suiteEvent,
        ExampleEvent $exampleEvent
    )
    {
        $io->writeln(Argument::any())
           ->willReturn();
        $io->askConfirmation(Argument::any())
           ->willReturn();
        $this->beConstructedWith($io, $resourceManager, $generatorManager);
    }
}