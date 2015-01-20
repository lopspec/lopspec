<?php

namespace spec\LopSpec\Exception;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ExceptionFactorySpec extends ObjectBehavior
{
    function it_creates_a_calling_method_on_non_object_exception(
        PresenterInterface $presenter
    )
    {
        $presenter->presentString("{$this->fixture->method}()")
                  ->shouldBeCalled()
                  ->willReturn("\"{$this->fixture->method}()\"");
        $fixtureMessage
            = "Call to a member function \"{$this->fixture->method}()\" on a non-object.";
        $exception = $this->callingMethodOnNonObject($this->fixture->method);
        $exception->shouldHaveType('LopSpec\Exception\Wrapper\SubjectException');
        $exception->getMessage()
                  ->shouldBe($fixtureMessage);
    }
    function it_creates_a_class_not_found_exception(
        PresenterInterface $presenter
    )
    {
        $presenter->presentString("{$this->fixture->classname}")
            ->shouldBeCalled()
                  ->willReturn("\"{$this->fixture->classname}\"");
        $this->fixture->message = 'Class "\stdClass" does not exist.';
        $this->createdException = $this->classNotFound($this->fixture->classname
        );
        $this->shouldCreateClassNotFoundException();
    }
    function it_creates_a_method_not_found_exception(PresenterInterface $presenter)
    {
        $presenter->presentString("{$this->fixture->classname}::{$this->fixture->method}")
            ->shouldBeCalled()
            ->willReturn("\"{$this->fixture->classname}::{$this->fixture->method}\"");
        $this->fixture->message = 'Method "\stdClass::foo" not found.';
        $this->createdException = $this->methodNotFound(
            $this->fixture->classname,
            $this->fixture->method,
            $this->fixture->arguments
        );

        $this->shouldCreateMethodNotFoundException();
    }
    function it_creates_a_method_not_visible_exception(PresenterInterface $presenter)
    {
        $presenter->presentString("{$this->fixture->classname}::{$this->fixture->method}")
            ->shouldBeCalled()
            ->willReturn("\"{$this->fixture->classname}::{$this->fixture->method}\"");
        $this->fixture->message = 'Method "\stdClass::foo" not visible.';

        $this->createdException = $this->methodNotVisible(
            $this->fixture->classname,
            $this->fixture->method,
            $this->fixture->arguments
        );

        $this->shouldCreateMethodNotVisibleException();
    }
    function it_creates_a_named_constructor_not_found_exception(
        PresenterInterface $presenter
    )
    {
        $presenter->presentString("{$this->fixture->classname}::{$this->fixture->method}")
            ->shouldBeCalled()
                  ->willReturn("\"{$this->fixture->classname}::{$this->fixture->method}\"");
        $this->fixture->message
            = 'Named constructor "\stdClass::foo" not found.';
        $this->createdException
            = $this->namedConstructorNotFound($this->fixture->classname,
            $this->fixture->method, $this->fixture->arguments
        );
        $this->shouldCreateNamedConstructorNotFoundException();
    }
    function it_creates_a_property_not_found_exception(PresenterInterface $presenter)
    {
        $presenter->presentString("{$this->fixture->property}")
            ->shouldBeCalled()
            ->willReturn("\"{$this->fixture->property}\"");
        $this->fixture->message = 'Property "zoo" not found.';
        $this->createdException = $this->propertyNotFound(
            $this->fixture->subject,
            $this->fixture->property
        );

        $this->shouldCreatePropertyNotFoundException();
    }
    function it_creates_a_setting_property_on_non_object_exception(PresenterInterface $presenter)
    {
        $presenter->presentString("{$this->fixture->property}")
            ->shouldBeCalled()
            ->willReturn("\"{$this->fixture->property}\"");
        $fixtureMessage = "Setting property \"{$this->fixture->property}\" on a non-object.";
        $exception = $this->settingPropertyOnNonObject($this->fixture->property);
        $exception->shouldHaveType('LopSpec\Exception\Wrapper\SubjectException');
        $exception->getMessage()->shouldBe($fixtureMessage);
    }
    function it_creates_an_accessing_property_on_non_object_exception(PresenterInterface $presenter)
    {
        $presenter->presentString("{$this->fixture->property}")
            ->shouldBeCalled()
            ->willReturn("\"{$this->fixture->property}\"");
        $fixtureMessage = "Getting property \"{$this->fixture->property}\" on a non-object.";
        $exception = $this->gettingPropertyOnNonObject($this->fixture->property);
        $exception->shouldHaveType('LopSpec\Exception\Wrapper\SubjectException');
        $exception->getMessage()->shouldBe($fixtureMessage);
    }
    function let(PresenterInterface $presenter)
    {
        $this->beConstructedWith($presenter);
        $this->fixture = new \stdClass();
        $this->fixture->subject = new \stdClass();
        $this->fixture->method = 'foo';
        $this->fixture->arguments = ['bar'];
        $this->fixture->classname = '\stdClass';
        $this->fixture->property = 'zoo';
    }
    function shouldCreateClassNotFoundException()
    {
        $this->createdException->shouldHaveType('LopSpec\Exception\Fracture\ClassNotFoundException');
        $this->createdException->getMessage()->shouldReturn($this->fixture->message);
        $this->createdException->getClassname()
                               ->shouldReturn($this->fixture->classname);
    }
    function shouldCreateMethodNotFoundException()
    {
        $this->createdException->shouldHaveType('LopSpec\Exception\Fracture\MethodNotFoundException');
        $this->createdException->getMessage()->shouldReturn($this->fixture->message);
        $this->createdException->getSubject()->shouldBeLike($this->fixture->subject);
        $this->createdException->getMethodName()->shouldReturn($this->fixture->method);
        $this->createdException->getArguments()->shouldReturn($this->fixture->arguments);
    }
    function shouldCreateMethodNotVisibleException()
    {
        $this->createdException->shouldHaveType('LopSpec\Exception\Fracture\MethodNotVisibleException');
        $this->createdException->getMessage()->shouldReturn($this->fixture->message);
        $this->createdException->getSubject()->shouldBeLike($this->fixture->subject);
        $this->createdException->getMethodName()->shouldReturn($this->fixture->method);
        $this->createdException->getArguments()->shouldReturn($this->fixture->arguments);
    }
    function shouldCreateNamedConstructorNotFoundException()
    {
        $this->createdException->shouldHaveType('LopSpec\Exception\Fracture\NamedConstructorNotFoundException');
        $this->createdException->getMessage()->shouldReturn($this->fixture->message);
        $this->createdException->getSubject()
                               ->shouldBeLike($this->fixture->subject);
        $this->createdException->getMethodName()
                               ->shouldReturn($this->fixture->method);
        $this->createdException->getArguments()
                               ->shouldReturn($this->fixture->arguments);
    }
    function shouldCreatePropertyNotFoundException()
    {
        $this->createdException->shouldHaveType('LopSpec\Exception\Fracture\PropertyNotFoundException');
        $this->createdException->getMessage()->shouldReturn($this->fixture->message);
        $this->createdException->getSubject()->shouldReturn($this->fixture->subject);
        $this->createdException->getProperty()->shouldReturn($this->fixture->property);
    }
    private $createdException;
    private $fixture;
}
