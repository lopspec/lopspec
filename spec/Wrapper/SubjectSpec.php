<?php

namespace spec\LopSpec\Wrapper;

use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Subject\Caller;
use LopSpec\Wrapper\Subject\Expectation\ExpectationInterface;
use LopSpec\Wrapper\Subject\ExpectationFactory;
use LopSpec\Wrapper\Subject\SubjectWithArrayAccess;
use LopSpec\Wrapper\Subject\WrappedObject;
use LopSpec\Wrapper\Wrapper;
use Prophecy\Argument;

class Everything
{
    public function isAlright()
    {
        return true;
    }
}
class SubjectSpec extends
    ObjectBehavior
{
    function it_passes_the_created_subject_to_expectation(WrappedObject $wrappedObject,
        ExpectationFactory $expectationFactory, ExpectationInterface $expectation)
    {
        $expectation->match(Argument::cetera())->willReturn(true);
        $wrappedObject->getClassName()
                      ->willReturn('spec\LopSpec\Wrapper\Everything');
        $expectationFactory->create(Argument::cetera())->willReturn($expectation);

        $this->callOnWrappedObject('shouldBeAlright');
        $expectationFactory->create(Argument::any(),
            Argument::type('spec\LopSpec\Wrapper\Everything'), Argument::any())
                           ->shouldHaveBeenCalled();
    }
    function it_passes_the_existing_subject_to_expectation(Wrapper $wrapper, WrappedObject $wrappedObject, Caller $caller,
        SubjectWithArrayAccess $arrayAccess, ExpectationFactory $expectationFactory, ExpectationInterface $expectation)
    {
        $existingSubject = new \ArrayObject();
        $this->beConstructedWith($existingSubject, $wrapper, $wrappedObject, $caller, $arrayAccess, $expectationFactory);

        $expectation->match(Argument::cetera())->willReturn(true);
        $wrappedObject->getClassName()->willReturn('\ArrayObject');
        $expectationFactory->create(Argument::cetera())->willReturn($expectation);

        $this->callOnWrappedObject('shouldBeAlright');
        $expectationFactory->create(Argument::any(), Argument::exact($existingSubject), Argument::any())->shouldHaveBeenCalled();
    }
    function let(
        Wrapper $wrapper,
        WrappedObject $wrappedObject,
        Caller $caller,
        SubjectWithArrayAccess $arrayAccess,
        ExpectationFactory $expectationFactory
    )
    {
        $this->beConstructedWith(null, $wrapper, $wrappedObject, $caller,
            $arrayAccess, $expectationFactory);
    }
}
