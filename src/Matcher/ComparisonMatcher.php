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
use LopSpec\Exception\Example\NotEqualException;
use LopSpec\Formatter\Presenter\PresenterInterface;

class ComparisonMatcher extends BasicMatcher
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
        return 'beLike' === $name
            && 1 == count($arguments)
        ;
    }
    /**
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return NotEqualException
     */
    protected function getFailureException($name, $subject, array $arguments)
    {
        return new NotEqualException(sprintf(
            'Expected %s, but got %s.',
            $this->presenter->presentValue($arguments[0]),
            $this->presenter->presentValue($subject)
        ), $arguments[0], $subject);
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
            'Did not expect %s, but got one.',
            $this->presenter->presentValue($subject)
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
        return $subject == $arguments[0];
    }
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
