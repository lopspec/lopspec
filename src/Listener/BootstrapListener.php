<?php

namespace LopSpec\Listener;

use LopSpec\Console\IO;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BootstrapListener implements EventSubscriberInterface
{
    public function __construct(IO $io)
    {
        $this->io = $io;
    }
    public static function getSubscribedEvents()
    {
        return ['beforeSuite' => 'beforeSuite'];
    }
    public function beforeSuite()
    {
        if ($bootstrap = $this->io->getBootstrapPath()) {
            if (!is_file($bootstrap)) {
                throw new \RuntimeException(sprintf("Bootstrap file '%s' does not exist", $bootstrap));
            }

            require $bootstrap;
        }
    }
    /**
     * @type IO
     */
    private $io;
}
