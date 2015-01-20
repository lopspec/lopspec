<?php

namespace Matcher;

use LopSpec\Exception\Example\FailureException;
use LopSpec\Matcher\MatcherInterface;
use Symfony\Component\Console\Tester\ApplicationTester;
const JUNIT_XSD_PATH = '/src/LopSpec/Resources/schema/junit.xsd';

class ValidJUnitXmlMatcher implements MatcherInterface
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
        throw new FailureException('Negative JUnit matcher not implemented');
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
        $dom = new \DOMDocument();
        $dom->loadXML($subject->getDisplay());
        if (!$dom->schemaValidate(__DIR__ . '/../../..' . JUNIT_XSD_PATH)) {
            throw new FailureException(sprintf(
               "Output was not valid JUnit XML"
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
        return ($name == 'haveOutputValidJunitXml'
                && $subject instanceof ApplicationTester);
    }
}
