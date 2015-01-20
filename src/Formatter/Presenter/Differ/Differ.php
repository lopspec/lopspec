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
namespace LopSpec\Formatter\Presenter\Differ;

class Differ
{
    public function __construct(array $engines = [])
    {
        $this->engines = $engines;
    }
    public function addEngine(EngineInterface $engine)
    {
        $this->engines[] = $engine;
    }
    public function compare($expected, $actual)
    {
        foreach ($this->engines as $engine) {
            if ($engine->supports($expected, $actual)) {
                return rtrim($engine->compare($expected, $actual));
            }
        }
    }
    private $engines = [];
}
