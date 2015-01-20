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
namespace LopSpec\Loader\Node;

use ReflectionFunctionAbstract;

class ExampleNode
{
    /**
     * @param string                     $title
     * @param ReflectionFunctionAbstract $function
     */
    public function __construct($title, ReflectionFunctionAbstract $function)
    {
        $this->title    = $title;
        $this->function = $function;
    }
    /**
     * @return ReflectionFunctionAbstract
     */
    public function getFunctionReflection()
    {
        return $this->function;
    }
    /**
     * @return SpecificationNode|null
     */
    public function getSpecification()
    {
        return $this->specification;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->isPending;
    }
    /**
     * @param bool $isPending
     */
    public function markAsPending($isPending = true)
    {
        $this->isPending = $isPending;
    }
    /**
     * @param SpecificationNode $specification
     */
    public function setSpecification(SpecificationNode $specification)
    {
        $this->specification = $specification;
    }
    /**
     * @type \ReflectionFunctionAbstract
     */
    private $function;
    /**
     * @type bool
     */
    private $isPending = false;
    /**
     * @type SpecificationNode|null
     */
    private $specification;
    /**
     * @type string
     */
    private $title;
}
