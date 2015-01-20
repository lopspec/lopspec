<?php

/*
 * This file is part of LopSpec, A PHP tool set to drive emergent design by
 * specification.
 *
 * (c) Marcello Duarte <marcello.duarte@gmail.com>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LopSpec\CodeGenerator;

use InvalidArgumentException;
use LopSpec\Locator\ResourceInterface;

/**
 * Uses registered generators to generate code honoring priority order
 */
class GeneratorManager
{
    /**
     * @param ResourceInterface $resource
     * @param string            $name
     * @param array             $data
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function generate(
        ResourceInterface $resource,
        $name,
        array $data = []
    )
    {
        foreach ($this->generators as $generator) {
            if ($generator->supports($resource, $name, $data)) {
                return $generator->generate($resource, $data);
            }
        }

        throw new InvalidArgumentException(sprintf(
            '"%s" code generator is not registered.', $name
        ));
    }
    /**
     * @param Generator\GeneratorInterface $generator
     */
    public function registerGenerator(Generator\GeneratorInterface $generator)
    {
        $this->generators[] = $generator;
        @usort($this->generators, function ($generator1, $generator2) {
            return $generator2->getPriority() - $generator1->getPriority();
        });
    }
    /**
     * @type array
     */
    private $generators = [];
}
