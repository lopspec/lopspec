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
namespace LopSpec\Formatter\Html;

use LopSpec\Event\ExampleEvent;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Formatter\Template as TemplateInterface;

class ReportItemFactory
{
    /**
     * @param TemplateInterface $template
     */
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }
    /**
     * @param ExampleEvent       $event
     * @param PresenterInterface $presenter
     *
     * @return ReportFailedItem|ReportPassedItem|ReportPendingItem
     */
    public function create(ExampleEvent $event, PresenterInterface $presenter)
    {
        switch ($event->getResult()) {
            case ExampleEvent::PASSED:
                return new ReportPassedItem($this->template, $event);
            case ExampleEvent::PENDING:
                return new ReportPendingItem($this->template, $event);
            case ExampleEvent::SKIPPED:
                return new ReportSkippedItem($this->template, $event);
            case ExampleEvent::FAILED:
            case ExampleEvent::BROKEN:
                return new ReportFailedItem($this->template, $event, $presenter);
            default:
                $this->invalidResultException($event->getResult());
        }
    }
    /**
     * @param integer $result
     *
     * @throws InvalidExampleResultException
     */
    private function invalidResultException($result)
    {
        throw new InvalidExampleResultException(
            "Unrecognised example result $result"
        );
    }
    /**
     * @type TemplateInterface
     */
    private $template;
}
