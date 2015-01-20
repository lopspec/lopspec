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
namespace LopSpec\Formatter;

use LopSpec\Event\ExampleEvent;
use LopSpec\Event\SpecificationEvent;
use LopSpec\Event\SuiteEvent;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\IO\IOInterface as IO;
use LopSpec\Listener\StatisticsCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class BasicFormatter implements EventSubscriberInterface
{
    public function __construct(PresenterInterface $presenter, IO $io, StatisticsCollector $stats)
    {
        $this->presenter = $presenter;
        $this->io = $io;
        $this->stats = $stats;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        $events = [
            'beforeSuite', 'afterSuite',
            'beforeExample', 'afterExample',
            'beforeSpecification', 'afterSpecification'
        ];

        return array_combine($events, $events);
    }
    /**
     * @param ExampleEvent $event
     */
    public function afterExample(ExampleEvent $event)
    {
    }
    /**
     * @param SpecificationEvent $event
     */
    public function afterSpecification(SpecificationEvent $event)
    {
    }
    /**
     * @param SuiteEvent $event
     */
    public function afterSuite(SuiteEvent $event)
    {
    }
    /**
     * @param ExampleEvent $event
     */
    public function beforeExample(ExampleEvent $event)
    {
    }
    /**
     * @param SpecificationEvent $event
     */
    public function beforeSpecification(SpecificationEvent $event)
    {
    }
    /**
     * @param SuiteEvent $event
     */
    public function beforeSuite(SuiteEvent $event)
    {
    }
    /**
     * @return IO
     */
    protected function getIO()
    {
        return $this->io;
    }
    /**
     * @return PresenterInterface
     */
    protected function getPresenter()
    {
        return $this->presenter;
    }
    /**
     * @return StatisticsCollector
     */
    protected function getStatisticsCollector()
    {
        return $this->stats;
    }
    /**
     * @type IO
     */
    private $io;
    /**
     * @type PresenterInterface
     */
    private $presenter;
    /**
     * @type StatisticsCollector
     */
    private $stats;
}
