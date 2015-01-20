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

use SebastianBergmann\Exporter\Exporter;

class ObjectEngine implements EngineInterface
{
    /**
     * @param Exporter     $exporter
     * @param StringEngine $stringDiffer
     */
    public function __construct(Exporter $exporter, StringEngine $stringDiffer)
    {
        $this->exporter = $exporter;
        $this->stringDiffer = $stringDiffer;
    }
    /**
     * @param object $expected
     * @param object $actual
     *
     * @return string
     */
    public function compare($expected, $actual)
    {
        return $this->stringDiffer->compare(
            $this->exporter->export($expected),
            $this->exporter->export($actual)
        );
    }
    /**
     * @param mixed $expected
     * @param mixed $actual
     *
     * @return bool
     */
    public function supports($expected, $actual)
    {
        return is_object($expected) && is_object($actual);
    }
    /**
     * @type \SebastianBergmann\Exporter\Exporter
     */
    private $exporter;
    /**
     * @type StringEngine
     */
    private $stringDiffer;
}
