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

use LopSpec\Event\ExampleEvent;
use LopSpec\Event\SpecificationEvent;
use LopSpec\Event\SuiteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StatisticsCollector implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'afterSpecification' => ['afterSpecification', 10],
            'afterExample'       => ['afterExample', 10],
            'beforeSuite'        => ['beforeSuite', 10],
        ];
    }
    public function afterExample(ExampleEvent $event)
    {
        $this->globalResult = max($this->globalResult, $event->getResult());

        switch ($event->getResult()) {
            case ExampleEvent::PASSED:
                $this->passedEvents[] = $event;
                break;
            case ExampleEvent::PENDING:
                $this->pendingEvents[] = $event;
                break;
            case ExampleEvent::SKIPPED:
                $this->skippedEvents[] = $event;
                break;
            case ExampleEvent::FAILED:
                $this->failedEvents[] = $event;
                break;
            case ExampleEvent::BROKEN:
                $this->brokenEvents[] = $event;
                break;
        }
    }
    public function afterSpecification(SpecificationEvent $event)
    {
        $this->totalSpecs++;
    }
    public function beforeSuite(SuiteEvent $suiteEvent)
    {
        $this->totalSpecsCount = count($suiteEvent->getSuite()->getSpecifications());
    }
    public function getAllEvents()
    {
        return array_merge(
            $this->passedEvents,
            $this->pendingEvents,
            $this->skippedEvents,
            $this->failedEvents,
            $this->brokenEvents
        );
    }
    public function getBrokenEvents()
    {
        return $this->brokenEvents;
    }
    public function getCountsHash()
    {
        return [
            'passed'  => count($this->getPassedEvents()),
            'pending' => count($this->getPendingEvents()),
            'skipped' => count($this->getSkippedEvents()),
            'failed'  => count($this->getFailedEvents()),
            'broken'  => count($this->getBrokenEvents()),
        ];
    }
    public function getEventsCount()
    {
        return count($this->getAllEvents());
    }
    public function getFailedEvents()
    {
        return $this->failedEvents;
    }
    public function getGlobalResult()
    {
        return $this->globalResult;
    }
    public function getPassedEvents()
    {
        return $this->passedEvents;
    }
    public function getPendingEvents()
    {
        return $this->pendingEvents;
    }
    public function getSkippedEvents()
    {
        return $this->skippedEvents;
    }
    public function getTotalSpecs()
    {
        return $this->totalSpecs;
    }
    public function getTotalSpecsCount()
    {
        return $this->totalSpecsCount;
    }
    private $brokenEvents = [];
    private $failedEvents = [];
    private $globalResult = 0;
    private $passedEvents = [];
    private $pendingEvents = [];
    private $skippedEvents = [];
    private $totalSpecs = 0;
    private $totalSpecsCount = 0;
}
