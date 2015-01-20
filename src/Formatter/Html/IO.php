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

use LopSpec\IO\IOInterface;

class IO implements IOInterface
{
    /**
     * @return bool
     */
    public function isVerbose()
    {
        return true;
    }
    /**
     * @param $message
     */
    public function write($message)
    {
        echo $message;
    }
}
