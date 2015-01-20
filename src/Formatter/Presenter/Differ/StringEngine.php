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

class StringEngine implements EngineInterface
{
    public function compare($expected, $actual)
    {
        $expected = explode(PHP_EOL, (string) $expected);
        $actual   = explode(PHP_EOL, (string) $actual);

        $diff = new \Diff($expected, $actual, []);

        $renderer = new \Diff_Renderer_Text_Unified();
        $text = $diff->render($renderer);

        $lines = [];
        foreach (explode("\n", $text) as $line) {
            if (0 === strpos($line, '-')) {
                $lines[] = sprintf('<diff-del>%s</diff-del>', $line);
            } elseif (0 === strpos($line, '+')) {
                $lines[] = sprintf('<diff-add>%s</diff-add>', $line);
            } else {
                $lines[] = $line;
            }
        }

        return sprintf("<code>%s%s</code>", PHP_EOL, implode(PHP_EOL, $lines));
    }
    public function supports($expected, $actual)
    {
        return is_string($expected) && is_string($actual);
    }
}
