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

class CallbackMatcher extends BasicMatcher
{
    /**
     * @param string             $name
     * @param callable           $callback
     * @param PresenterInterface $presenter
     */
    public function __construct($name, $callback, PresenterInterface $presenter)
    {
        $this->name      = $name;
        $this->callback  = $callback;
        $this->presenter = $presenter;
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
        return $name === $this->name;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    protected function getFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            '%s expected to %s(%s), but it is not.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentString($name), implode(', ',
                array_map([$this->presenter, 'presentValue'], $arguments))
        ));
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            '%s not expected to %s(%s), but it did.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentString($name), implode(', ',
                array_map([$this->presenter, 'presentValue'], $arguments))
        ));
    }
    /**
     * @param string $subject
     * @param array  $arguments
     *
     * @return bool
     */
    protected function matches($subject, array $arguments)
    {
        array_unshift($arguments, $subject);

        return (Boolean)call_user_func_array($this->callback, $arguments);
    }
    /**
     * @type callable
     */
    private $callback;
    /**
     * @type string
     */
    private $name;
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
