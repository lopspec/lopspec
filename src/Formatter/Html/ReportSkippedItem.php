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
use LopSpec\Formatter\Template as TemplateInterface;

class ReportSkippedItem
{
    /**
     * @param TemplateInterface $template
     * @param ExampleEvent      $event
     */
    public function __construct(TemplateInterface $template, ExampleEvent $event)
    {
        $this->template = $template;
        $this->event    = $event;
    }
    /**
     *
     */
    public function write()
    {
        $this->template->render(Template::DIR . '/Template/ReportSkipped.html',
            [
            'title' => htmlentities(strip_tags($this->event->getTitle())),
            'message' => htmlentities(strip_tags($this->event->getMessage())),
            ]);
    }
    /**
     * @type \LopSpec\Event\ExampleEvent
     */
    private $event;
    /**
     * @type \LopSpec\Formatter\Template
     */
    private $template;
}
