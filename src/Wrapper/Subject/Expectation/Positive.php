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
namespace LopSpec\Wrapper\Subject\Expectation;

use LopSpec\Matcher\MatcherInterface;

class Positive implements ExpectationInterface
{
    /**
     * @param MatcherInterface $matcher
     */
    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }
    /**
     * @param string $alias
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return mixed
     */
    public function match($alias, $subject, array $arguments = [])
    {
        return $this->matcher->positiveMatch($alias, $subject, $arguments);
    }
    /**
     * @type \LopSpec\Matcher\MatcherInterface
     */
    private $matcher;
}
