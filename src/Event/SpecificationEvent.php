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

use LopSpec\Loader\Node\SpecificationNode;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SpecificationEvent holds information about the specification event
 */
class SpecificationEvent extends Event implements EventInterface
{
    /**
     * @param SpecificationNode $specification
     * @param float             $time
     * @param integer           $result
     */
    public function __construct(SpecificationNode $specification, $time = null, $result = null)
    {
        $this->specification = $specification;
        $this->time          = $time;
        $this->result        = $result;
    }
    /**
     * @return integer
     */
    public function getResult()
    {
        return $this->result;
    }
    /**
     * @return SpecificationNode
     */
    public function getSpecification()
    {
        return $this->specification;
    }
    /**
     * @return \LopSpec\Loader\Suite
     */
    public function getSuite()
    {
        return $this->specification->getSuite();
    }
    /**
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->specification->getTitle();
    }
    /**
     * @type integer
     */
    private $result;
    /**
     * @type \LopSpec\Loader\Node\SpecificationNode
     */
    private $specification;
    /**
     * @type float
     */
    private $time;
}
