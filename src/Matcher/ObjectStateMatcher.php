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
use LopSpec\Exception\Fracture\MethodNotFoundException;
use LopSpec\Formatter\Presenter\PresenterInterface;

class ObjectStateMatcher implements MatcherInterface
{
    /**
     * @param PresenterInterface $presenter
     */
    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }
    /**
     * @return int
     */
    public function getPriority()
    {
        return 50;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws \LopSpec\Exception\Example\FailureException
     * @throws \LopSpec\Exception\Fracture\MethodNotFoundException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        preg_match(self::$regex, $name, $matches);
        $method   = ('be' === $matches[1] ? 'is' : 'has').ucfirst($matches[2]);
        $callable = [$subject, $method];

        if (!method_exists($subject, $method)) {
            throw new MethodNotFoundException(sprintf(
                'Method %s not found.',
                $this->presenter->presentValue($callable)
            ), $subject, $method, $arguments);
        }
        if (false !== $result = call_user_func_array($callable, $arguments)) {
            throw $this->getFailureExceptionFor($callable, false, $result);
        }
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws \LopSpec\Exception\Example\FailureException
     * @throws \LopSpec\Exception\Fracture\MethodNotFoundException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        preg_match(self::$regex, $name, $matches);
        $method   = ('be' === $matches[1] ? 'is' : 'has').ucfirst($matches[2]);
        $callable = [$subject, $method];

        if (!method_exists($subject, $method)) {
            throw new MethodNotFoundException(sprintf(
                'Method %s not found.',
                $this->presenter->presentValue($callable)
            ), $subject, $method, $arguments);
        }
        if (true !== $result = call_user_func_array($callable, $arguments)) {
            throw $this->getFailureExceptionFor($callable, true, $result);
        }
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return bool
     */
    public function supports($name, $subject, array $arguments)
    {
        return is_object($subject) && !is_callable($subject)
               && (0 === strpos($name, 'be') || 0 === strpos($name, 'have'));
    }
    /**
     * @param callable $callable
     * @param Boolean  $expectedBool
     * @param Boolean  $result
     *
     * @return FailureException
     */
    private function getFailureExceptionFor($callable, $expectedBool, $result)
    {
        return new FailureException(sprintf(
            "Expected %s to return %s, but got %s.",
            $this->presenter->presentValue($callable),
            $this->presenter->presentValue($expectedBool),
            $this->presenter->presentValue($result)
        ));
    }
    /**
     * @type string
     */
    private static $regex = '/(be|have)(.+)/';
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
