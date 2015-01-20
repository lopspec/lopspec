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
use LopSpec\Exception\Fracture\NamedConstructorNotFoundException;
use LopSpec\Locator\ResourceManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NamedConstructorNotFoundListener implements EventSubscriberInterface
{
    public function __construct(IO $io, ResourceManager $resources, GeneratorManager $generator)
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

        if (!$exception instanceof NamedConstructorNotFoundException) {
            return;
        }

        $this->methods[get_class($exception->getSubject()).'::'.$exception->getMethodName()] = $exception->getArguments();
    }
    public function afterSuite(SuiteEvent $event)
    {
        if (!$this->io->isCodeGenerationEnabled()) {
            return;
        }

        foreach ($this->methods as $call => $arguments) {
            list($classname, $method) = explode('::', $call);
            $message = sprintf('Do you want me to create `%s()` for you?', $call);

            try {
                $resource = $this->resources->createResource($classname);
            } catch (\RuntimeException $e) {
                continue;
            }

            if ($this->io->askConfirmation($message)) {
                $this->generator->generate($resource, 'named_constructor', [
                    'name'      => $method,
                    'arguments' => $arguments
                ]);
                $event->markAsWorthRerunning();
            }
        }
    }
    private $generator;
    private $io;
    private $methods = [];
    private $resources;
}
