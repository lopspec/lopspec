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

class HtmlFormatter extends BasicFormatter
{
    public function __construct(Html\ReportItemFactory $reportItemFactory, PresenterInterface $presenter, IO $io, StatisticsCollector $stats)
    {
        $this->reportItemFactory = $reportItemFactory;

        parent::__construct($presenter, $io, $stats);
    }
    /**
     * @param ExampleEvent $event
     */
    public function afterExample(ExampleEvent $event)
    {
        $reportLine = $this->reportItemFactory->create($event,
            $this->getPresenter());
        $reportLine->write($this->index - 1);
        $this->getIO()
             ->write(PHP_EOL);
    }
    /**
     * @param SpecificationEvent $specification
     */
    public function afterSpecification(SpecificationEvent $specification)
    {
        include __DIR__ . "/Html/Template/ReportSpecificationEnds.html";
    }
    /**
     * @param SuiteEvent $suite
     */
    public function afterSuite(SuiteEvent $suite)
    {
        include __DIR__ . "/Html/Template/ReportSummary.html";
        include __DIR__ . "/Html/Template/ReportFooter.html";
    }
    /**
     * @param SpecificationEvent $specification
     */
    public function beforeSpecification(SpecificationEvent $specification)
    {
        $index = $this->index++;
        $name = $specification->getTitle();
        include __DIR__ . "/Html/Template/ReportSpecificationStarts.html";
    }
    /**
     * @param SuiteEvent $suite
     */
    public function beforeSuite(SuiteEvent $suite)
    {
        include __DIR__ . "/Html/Template/ReportHeader.html";
    }
    /**
     * @type int
     */
    private $index = 1;
    /**
     * @type Html\ReportItemFactory
     */
    private $reportItemFactory;
}
