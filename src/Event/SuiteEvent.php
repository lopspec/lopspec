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
namespace LopSpec\Event;

use LopSpec\Loader\Suite;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SuiteEvent holds information about the suite event
 */
class SuiteEvent extends Event implements EventInterface
{
    /**
     * @param Suite   $suite
     * @param float   $time
     * @param integer $result
     */
    public function __construct(Suite $suite, $time = null, $result = null)
    {
        $this->suite  = $suite;
        $this->time   = $time;
        $this->result = $result;
    }
    /**
     * @return integer
     */
    public function getResult()
    {
        return $this->result;
    }
    /**
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }
    /**
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @return bool
     */
    public function isWorthRerunning()
    {
        return $this->worthRerunning;
    }
    public function markAsWorthRerunning()
    {
        $this->worthRerunning = true;
    }
    /**
     * @type integer
     */
    private $result;
    /**
     * @type Suite
     */
    private $suite;
    /**
     * @type float
     */
    private $time;
    /**
     * @type boolean
     */
    private $worthRerunning = false;
}
