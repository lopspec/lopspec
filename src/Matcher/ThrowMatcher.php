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
use LopSpec\Exception\Example\MatcherException;
use LopSpec\Exception\Example\NotEqualException;
use LopSpec\Exception\Fracture\MethodNotFoundException;
use LopSpec\Factory\ReflectionFactory;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Wrapper\DelayedCall;
use LopSpec\Wrapper\Unwrapper;

class ThrowMatcher implements MatcherInterface
{
    /**
     * @param Unwrapper          $unwrapper
     * @param PresenterInterface $presenter
     * @param ReflectionFactory  $factory
     */
    public function __construct(Unwrapper $unwrapper, PresenterInterface $presenter, ReflectionFactory $factory = null)
    {
        $this->unwrapper = $unwrapper;
        $this->presenter = $presenter;
        $this->factory   = $factory ?: new ReflectionFactory();
    }
    /**
     * @return int
     */
    public function getPriority()
    {
        return 1;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return DelayedCall
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        return $this->getDelayedCall([$this, 'verifyNegative'], $subject,
            $arguments);
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return DelayedCall
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        return $this->getDelayedCall([$this, 'verifyPositive'], $subject,
            $arguments);
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
        return 'throw' === $name;
    }
    /**
     * @param callable    $callable
     * @param array       $arguments
     * @param string|null $exception
     *
     * @throws \LopSpec\Exception\Example\FailureException
     */
    public function verifyNegative($callable, array $arguments, $exception = null)
    {
        try {
            call_user_func_array($callable, $arguments);
        } catch (\Exception $e) {
            if (null === $exception) {
                throw new FailureException(sprintf(
                    'Expected to not throw any exceptions, but got %s.',
                    $this->presenter->presentValue($e)
                ));
            }

            if ($e instanceof $exception) {
                $invalidProperties = [];
                if (is_object($exception)) {
                    $exceptionRefl = $this->factory->create($exception);
                    foreach ($exceptionRefl->getProperties() as $property) {
                        if (in_array($property->getName(), self::$ignoredProperties)) {
                            continue;
                        }

                        $property->setAccessible(true);
                        $expected = $property->getValue($exception);
                        $actual   = $property->getValue($e);

                        if (null !== $expected && $actual === $expected) {
                            $invalidProperties[] = sprintf('  `%s`=%s',
                                $property->getName(),
                                $this->presenter->presentValue($expected)
                            );
                        }
                    }
                }

                $withProperties = '';
                if (count($invalidProperties) > 0) {
                    $withProperties = sprintf(' with'.PHP_EOL.'%s,'.PHP_EOL,
                        implode(",\n", $invalidProperties)
                    );
                }

                throw new FailureException(sprintf(
                    'Expected to not throw %s exception%s but got it.',
                    $this->presenter->presentValue($exception),
                    $withProperties
                ));
            }
        }
    }
    /**
     * @param callable $callable
     * @param array    $arguments
     * @param null     $exception
     *
     * @throws \LopSpec\Exception\Example\FailureException
     * @throws \LopSpec\Exception\Example\NotEqualException
     */
    public function verifyPositive(
        $callable,
        array $arguments,
        $exception = null
    )
    {
        try {
            call_user_func_array($callable, $arguments);
        } catch (\Exception $e) {
            if (null === $exception) {
                return;
            }
            if (!$e instanceof $exception) {
                throw new FailureException(sprintf('Expected exception of class %s, but got %s.',
                    $this->presenter->presentValue($exception),
                    $this->presenter->presentValue($e)));
            }
            if (is_object($exception)) {
                $exceptionRefl = $this->factory->create($exception);
                foreach ($exceptionRefl->getProperties() as $property) {
                    if (in_array($property->getName(),
                        self::$ignoredProperties)) {
                        continue;
                    }
                    $property->setAccessible(true);
                    $expected = $property->getValue($exception);
                    $actual = $property->getValue($e);
                    if (null !== $expected && $actual !== $expected) {
                        throw new NotEqualException(sprintf('Expected exception `%s` to be %s, but it is %s.',
                            $property->getName(),
                            $this->presenter->presentValue($expected),
                            $this->presenter->presentValue($actual)), $expected,
                            $actual);
                    }
                }
            }

            return;
        }
        throw new FailureException('Expected to get exception, none got.');
    }
    /**
     * @param callable $check
     * @param mixed    $subject
     * @param array    $arguments
     *
     * @return DelayedCall
     */
    private function getDelayedCall($check, $subject, array $arguments)
    {
        $exception = $this->getException($arguments);
        $unwrapper = $this->unwrapper;

        return new DelayedCall(
            function ($method, $arguments) use ($check, $subject, $exception, $unwrapper) {
                $arguments = $unwrapper->unwrapAll($arguments);

                $methodName  = $arguments[0];
                $arguments = isset($arguments[1]) ? $arguments[1] : [];
                $callable = [$subject, $methodName];
                list($class, $methodName) = [$subject, $methodName];
                if (!method_exists($class, $methodName) && !method_exists($class, '__call')) {
                    throw new MethodNotFoundException(
                        sprintf('Method %s::%s not found.', get_class($class), $methodName),
                        $class, $methodName, $arguments
                    );
                }

                return call_user_func($check, $callable, $arguments, $exception);
            }
        );
    }
    /**
     * @param array $arguments
     *
     * @return null|string
     * @throws \LopSpec\Exception\Example\MatcherException
     */
    private function getException(array $arguments)
    {
        if (0 == count($arguments)) {
            return null;
        }

        if (is_string($arguments[0])) {
            return $arguments[0];
        }

        if (is_object($arguments[0]) && $arguments[0] instanceof \Exception) {
            return $arguments[0];
        }

        throw new MatcherException(sprintf(
            "Wrong argument provided in throw matcher.\n".
            "Fully qualified classname or exception instance expected,\n".
            "Got %s.",
            $this->presenter->presentValue($arguments[0])
        ));
    }
    /**
     * @type array
     */
    private static $ignoredProperties
        = [
            'file',
            'line',
            'string',
            'trace',
            'previous'
        ];
    /**
     * @type ReflectionFactory
     */
    private $factory;
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
    /**
     * @type \LopSpec\Wrapper\Unwrapper
     */
    private $unwrapper;
}