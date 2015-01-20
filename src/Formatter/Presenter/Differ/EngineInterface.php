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

interface EngineInterface
{
    /**
     * @param mixed $expected
     * @param mixed $actual
     *
     * @return string
     */
    public function compare($expected, $actual);

    /**
     * @param mixed $expected
     * @param mixed $actual
     *
     * @return bool
     */
    public function supports($expected, $actual);
}
