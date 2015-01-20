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
use LopSpec\Formatter\Presenter\PresenterInterface;

class ScalarMatcher implements MatcherInterface
{
    /**
     * @param \LopSpec\Formatter\Presenter\PresenterInterface $presenter
     */
    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }
    /**
     * Returns matcher priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return 50;
    }
    /**
     * Evaluates negative match.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws \LopSpec\Exception\Example\FailureException
     * @return boolean
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        $checker = $this->getCheckerName($name);
        if (call_user_func($checker, $subject)) {
            throw new FailureException(sprintf('%s not expected to return %s, but it did.',
                $this->presenter->presentString(sprintf('%s(%s)',
                    $checker, $this->presenter->presentValue($subject)
                )),
                $this->presenter->presentValue(true)
            ));
        }
    }
    /**
     * Evaluates positive match.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws \LopSpec\Exception\Example\FailureException
     * @return boolean
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        $checker = $this->getCheckerName($name);
        if (!call_user_func($checker, $subject)) {
            throw new FailureException(sprintf('%s expected to return %s, but it did not.',
                $this->presenter->presentString(sprintf('%s(%s)',
                    $checker, $this->presenter->presentValue($subject)
                )),
                $this->presenter->presentValue(true)
            ));
        }
    }
    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return Boolean
     */
    public function supports($name, $subject, array $arguments)
    {
        $checkerName = $this->getCheckerName($name);

        return $checkerName && function_exists($checkerName);
    }
    /**
     * @param string $name
     *
     * @return string|boolean
     */
    private function getCheckerName($name)
    {
        if (0 !== strpos($name, 'be')) {
            return false;
        }

        $expected = lcfirst(substr($name, 2));
        if ($expected == 'boolean') {
            return 'is_bool';
        }

        return 'is_'.$expected;
    }
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
