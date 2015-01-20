<?php

namespace Fake;

use LopSpec\Process\ReRunner as BaseReRunner;

class ReRunner implements BaseReRunner
{
    public function hasBeenReRun()
    {
        return $this->beenReRun;
    }
    /**
     * @return boolean
     */
    public function isSupported()
    {
        return true;
    }

    public function reRunSuite()
    {
        $this->beenReRun = true;
    }
    private $beenReRun = false;
}
