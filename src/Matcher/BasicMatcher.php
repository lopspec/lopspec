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
namespace LopSpec\Matcher;

use LopSpec\Exception\Example\FailureException;

abstract class BasicMatcher implements MatcherInterface
{
    /**
     * @return int
     */
    public function getPriority()
    {
        return 100;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return mixed
     *
     * @throws FailureException
     */
    final public function negativeMatch($name, $subject, array $arguments)
    {
        if (true === $this->matches($subject, $arguments)) {
            throw $this->getNegativeFailureException($name, $subject,
                $arguments);
        }

        return $subject;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return mixed
     *
     * @throws FailureException
     */
    final public function positiveMatch($name, $subject, array $arguments)
    {
        if (false === $this->matches($subject, $arguments)) {
            throw $this->getFailureException($name, $subject, $arguments);
        }

        return $subject;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    abstract protected function getFailureException($name, $subject, array $arguments);
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    abstract protected function getNegativeFailureException($name, $subject, array $arguments);
    /**
     * @param mixed $subject
     * @param array $arguments
     *
     * @return boolean
     */
    abstract protected function matches($subject, array $arguments);
}
