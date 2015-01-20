<?php

namespace Matcher;

use LopSpec\Exception\Example\FailureException;
use LopSpec\Matcher\MatcherInterface;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApplicationOutputMatcher implements MatcherInterface
{

    /**
     * Returns matcher priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return 51;
    }
    /**
     * Evaluates negative match.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws FailureException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        throw new FailureException('Negative application output matcher not implemented');
    }
    /**
     * Evaluates positive match.
     *
     * @param string $name
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @throws FailureException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        $expected = $arguments[0];
        if (strpos($subject->getDisplay(), $expected) === false) {
            throw new FailureException(sprintf(
                "Application output did not contain expected '%s'. Actual output:\n'%s'" ,
                $expected,
                $subject->getDisplay()
            ));
        }
    }
    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @return Boolean
     */
    public function supports($name, $subject, array $arguments)
    {
        return ($name == 'haveOutput' && $subject instanceof ApplicationTester);
    }
}
