<?php

/*
 * This file is part of LopSpec, A php toolset to drive emergent
 * design by specification.
 *
 * (c) Marcello Duarte <marcello.duarte@gmail.com>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LopSpec\Listener;

use LopSpec\CodeGenerator\GeneratorManager;
use LopSpec\Console\IO;
use LopSpec\Event\ExampleEvent;
use LopSpec\Event\SuiteEvent;
use LopSpec\Exception\Fracture\ClassNotFoundException as PhpSpecClassException;
use LopSpec\Locator\ResourceManagerInterface;
use Prophecy\Exception\Doubler\ClassNotFoundException as ProphecyClassException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClassNotFoundListener implements EventSubscriberInterface
{
    public function __construct(IO $io, ResourceManagerInterface $resources, GeneratorManager $generator)
    {
        $this->io        = $io;
        $this->resources = $resources;
        $this->generator = $generator;
    }
    public static function getSubscribedEvents()
    {
        return [
            'afterExample' => ['afterExample', 10],
            'afterSuite'   => ['afterSuite', -10],
        ];
    }
    public function afterExample(ExampleEvent $event)
    {
        if (null === $exception = $event->getException()) {
            return;
        }

        if (!($exception instanceof PhpSpecClassException) &&
            !($exception instanceof ProphecyClassException)) {
            return;
        }

        $this->classes[$exception->getClassname()] = true;
    }
    public function afterSuite(SuiteEvent $event)
    {
        if (!$this->io->isCodeGenerationEnabled()) {
            return;
        }

        foreach ($this->classes as $classname => $_) {
            $message = sprintf('Do you want me to create `%s` for you?', $classname);

            try {
                $resource = $this->resources->createResource($classname);
            } catch (\RuntimeException $e) {
                continue;
            }

            if ($this->io->askConfirmation($message)) {
                $this->generator->generate($resource, 'class');
                $event->markAsWorthRerunning();
            }
        }
    }
    private $classes = [];
    private $generator;
    private $io;
    private $resources;
}
