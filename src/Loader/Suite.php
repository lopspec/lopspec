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
namespace LopSpec\Loader;

class Suite implements \Countable
{
    /**
     * @param Node\SpecificationNode $spec
     */
    public function addSpecification(Node\SpecificationNode $spec)
    {
        $this->specs[] = $spec;
        $spec->setSuite($this);
    }
    /**
     * @return number
     */
    public function count()
    {
        return array_sum(array_map('count', $this->specs));
    }
    /**
     * @return Node\SpecificationNode[]
     */
    public function getSpecifications()
    {
        return $this->specs;
    }
    /**
     * @type array
     */
    private $specs = [];
}
