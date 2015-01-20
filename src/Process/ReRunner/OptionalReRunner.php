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
namespace LopSpec\Process\ReRunner;

use LopSpec\Console\IO;
use LopSpec\Process\ReRunner;

class OptionalReRunner implements ReRunner
{
    /**
     * @param IO $io
     */
    public function __construct(ReRunner $decoratedRerunner, IO $io)
    {
        $this->io = $io;
        $this->decoratedRerunner = $decoratedRerunner;
    }
    public function reRunSuite()
    {
        if ($this->io->isRerunEnabled()) {
            $this->decoratedRerunner->reRunSuite();
        }
    }
    /**
     * @type \LopSpec\Process\ReRunner
     */
    private $decoratedRerunner;
    /**
     * @type \LopSpec\Console\IO
     */
    private $io;
}
