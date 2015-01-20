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

use LopSpec\Console\IO;
use LopSpec\Event\ExampleEvent;
use LopSpec\Exception\Example\StopOnFailureException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StopOnFailureListener implements EventSubscriberInterface
{
    /**
     * @param IO $io
     */
    public function __construct(IO $io)
    {
        $this->io = $io;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'afterExample' => ['afterExample', -100],
        ];
    }
    /**
     * @param ExampleEvent $event
     *
     * @throws \LopSpec\Exception\Example\StopOnFailureException
     */
    public function afterExample(ExampleEvent $event)
    {
        if (!$this->io->isStopOnFailureEnabled()) {
            return;
        }

        if ($event->getResult() === ExampleEvent::FAILED
         || $event->getResult() === ExampleEvent::BROKEN) {
            throw new StopOnFailureException('Example failed', 0, null, $event->getResult());
        }
    }
    /**
     * @type IO
     */
    private $io;
}
