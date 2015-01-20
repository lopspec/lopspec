<?php

namespace Matcher;

use LopSpec\Exception\Example\FailureException;
use LopSpec\Matcher\MatcherInterface;

class FileExistsMatcher implements MatcherInterface
{
    /**
     * Returns matcher priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return 0;
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
        if (file_exists($subject)) {
            throw new FailureException(sprintf("File unexpectedly exists at path '%s'",
                $subject
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
     * @throws FailureException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        if (!file_exists($subject)) {
            throw new FailureException(sprintf("File did not exist at path '%s'",
                $subject
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
        return ('exist' == $name && is_string($subject));
    }
}
