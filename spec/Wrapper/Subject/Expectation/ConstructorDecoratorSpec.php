<?php

namespace spec\LopSpec\Wrapper\Subject\Expectation;

use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Subject;
use LopSpec\Wrapper\Subject\Expectation\ExpectationInterface;
use LopSpec\Wrapper\Subject\WrappedObject;
use Prophecy\Argument;

class ConstructorDecoratorSpec extends ObjectBehavior
{
    function it_ignores_any_other_exception(
        Subject $subject,
        WrappedObject $wrapped
    )
    {
        $subject->callOnWrappedObject('getWrappedObject', [])
                ->willThrow('\Exception');
        $wrapped->getClassName()
                ->willReturn('\stdClass');
        $this->shouldNotThrow('\Exception')
             ->duringMatch('be', $subject, [], $wrapped);
    }
    function it_rethrows_fracture_errors_as_phpspec_error_exceptions(
        Subject $subject,
        WrappedObject $wrapped
    )
    {
        $subject->__call('getWrappedObject', [])
                ->willThrow('LopSpec\Exception\Fracture\FractureException');
        $this->shouldThrow('LopSpec\Exception\Fracture\FractureException')
             ->duringMatch('be', $subject, [], $wrapped);
    }
    function it_rethrows_php_errors_as_phpspec_error_exceptions(
        Subject $subject,
        WrappedObject $wrapped
    )
    {
        $subject->callOnWrappedObject('getWrappedObject', [])
                ->willThrow('LopSpec\Exception\Example\ErrorException');
        $this->shouldThrow('LopSpec\Exception\Example\ErrorException')
             ->duringMatch('be', $subject, [], $wrapped);
    }
    function let(ExpectationInterface $expectation)
    {
        $this->beConstructedWith($expectation);
    }
}
