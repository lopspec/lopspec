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

use LopSpec\Loader\Suite;
use LopSpec\Locator\ResourceInterface;
use ReflectionClass;

class SpecificationNode implements \Countable
{
    /**
     * @param string            $title
     * @param ReflectionClass   $class
     * @param ResourceInterface $resource
     */
    public function __construct($title, ReflectionClass $class, ResourceInterface $resource)
    {
        $this->title    = $title;
        $this->class    = $class;
        $this->resource = $resource;
    }
    /**
     * @param ExampleNode $example
     */
    public function addExample(ExampleNode $example)
    {
        $this->examples[] = $example;
        $example->setSpecification($this);
    }
    /**
     * @return int
     */
    public function count()
    {
        return count($this->examples);
    }
    /**
     * @return ReflectionClass
     */
    public function getClassReflection()
    {
        return $this->class;
    }
    /**
     * @return ExampleNode[]
     */
    public function getExamples()
    {
        return $this->examples;
    }
    /**
     * @return ResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }
    /**
     * @return Suite|null
     */
    public function getSuite()
    {
        return $this->suite;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @param Suite $suite
     */
    public function setSuite(Suite $suite)
    {
        $this->suite = $suite;
    }
    /**
     * @type \ReflectionClass
     */
    private $class;
    /**
     * @type ExampleNode[]
     */
    private $examples = [];
    /**
     * @type \LopSpec\Locator\ResourceInterface
     */
    private $resource;
    /**
     * @type Suite
     */
    private $suite;
    /**
     * @type string
     */
    private $title;
}
