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

class ReportPassedItem
{
    /**
     * @param TemplateInterface $template
     * @param ExampleEvent      $event
     */
    public function __construct(TemplateInterface $template, ExampleEvent $event)
    {
        $this->template = $template;
        $this->event = $event;
    }
    /**
     *
     */
    public function write()
    {
        $this->template->render(Template::DIR . '/Template/ReportPass.html', [
            'title' => $this->event->getTitle()
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
