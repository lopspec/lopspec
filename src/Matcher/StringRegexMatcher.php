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

class StringRegexMatcher extends BasicMatcher
{
    /**
     * @param PresenterInterface $presenter
     */
    public function __construct(PresenterInterface $presenter)
    {
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
        return 'match' === $name
            && is_string($subject)
            && 1 == count($arguments)
        ;
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
            'Expected %s to match %s regex, but it does not.',
            $this->presenter->presentString($subject),
            $this->presenter->presentString($arguments[0])
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
            'Expected %s not to match %s regex, but it does.',
            $this->presenter->presentString($subject),
            $this->presenter->presentString($arguments[0])
        ));
    }
    /**
     * @param mixed $subject
     * @param array $arguments
     *
     * @return bool
     */
    protected function matches($subject, array $arguments)
    {
        return (Boolean)preg_match($arguments[0], $subject);
    }
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
