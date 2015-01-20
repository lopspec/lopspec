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
namespace LopSpec\Locator;

interface ResourceLocatorInterface
{
    /**
     * @param string $classname
     *
     * @return ResourceInterface|null
     */
    public function createResource($classname);

    /**
     * @param string $query
     *
     * @return ResourceInterface[]
     */
    public function findResources($query);

    /**
     * @return ResourceInterface[]
     */
    public function getAllResources();

    /**
     * @return integer
     */
    public function getPriority();

    /**
     * @param string $className
     *
     * @return boolean
     */
    public function supportsClass($className);

    /**
     * @param string $query
     *
     * @return boolean
     */
    public function supportsQuery($query);
}
