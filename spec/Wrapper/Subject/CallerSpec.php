<?php

namespace spec\LopSpec\Wrapper\Subject;

use LopSpec\Exception\ExceptionFactory;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\ObjectBehavior;
use LopSpec\Wrapper\Subject;
use LopSpec\Wrapper\Subject\WrappedObject;
use LopSpec\Wrapper\Wrapper;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

class CallerSpec extends ObjectBehavior
{
    function it_delegates_throwing_calling_method_on_non_object_exception(
        ExceptionFactory $exceptions
    )
    {
        $exceptions->callingMethodOnNonObject('foo')
                   ->willReturn(new \LopSpec\Exception\Wrapper\SubjectException('Call to a member function "foo()" on a non-object.'))
                   ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Wrapper\SubjectException')
             ->duringCall('foo');
    }
    function it_delegates_throwing_class_not_found_exception(WrappedObject $wrappedObject, ExceptionFactory $exceptions)
    {
        $wrappedObject->isInstantiated()->willReturn(false);
        $wrappedObject->getClassName()->willReturn('Foo');

        $exceptions->classNotFound('Foo')
            ->willReturn(new \LopSpec\Exception\Fracture\ClassNotFoundException(
                'Class "Foo" does not exist.',
                '"Foo"'
            ))
            ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Fracture\ClassNotFoundException')
            ->duringGetWrappedObject();
    }
    function it_delegates_throwing_getting_property_on_non_object_exception(
        ExceptionFactory $exceptions
    ) {
        $exceptions->gettingPropertyOnNonObject('foo')
                   ->willReturn(new \LopSpec\Exception\Wrapper\SubjectException('Getting property "foo" on a non-object.'))
                   ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Wrapper\SubjectException')
             ->duringGet('foo');
    }
    function it_delegates_throwing_method_not_found_exception(WrappedObject $wrappedObject, ExceptionFactory $exceptions)
    {
        $obj = new \ArrayObject();

        $wrappedObject->isInstantiated()->willReturn(true);
        $wrappedObject->getInstance()->willReturn($obj);
        $wrappedObject->getClassName()->willReturn('ArrayObject');
        $exceptions->methodNotFound('ArrayObject', 'foo', [])
                   ->willReturn(new \LopSpec\Exception\Fracture\MethodNotFoundException(
                'Method "foo" not found.',
                $obj,
                '"ArrayObject::foo"', []
            ))
            ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Fracture\MethodNotFoundException')
            ->duringCall('foo');
    }
    function it_delegates_throwing_method_not_found_exception_for_constructor(WrappedObject $wrappedObject, ExceptionFactory $exceptions, \stdClass $argument)
    {
        $obj = new ExampleClass();

        $wrappedObject->isInstantiated()->willReturn(false);
        $wrappedObject->getInstance()->willReturn(null);
        $wrappedObject->getArguments()
                      ->willReturn([$argument]);
        $wrappedObject->getClassName()
                      ->willReturn('spec\LopSpec\Wrapper\Subject\ExampleClass');
        $wrappedObject->getFactoryMethod()->willReturn(null);
        $exceptions->methodNotFound('spec\LopSpec\Wrapper\Subject\ExampleClass',
            '__construct', [$argument])
                   ->willReturn(new \LopSpec\Exception\Fracture\MethodNotFoundException(
                    'Method "__construct" not found.',
                    $obj,
                    '"ExampleClass::__construct"', []
                ))
            ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Fracture\MethodNotFoundException')
            ->duringCall('__construct');
    }
    function it_delegates_throwing_method_not_visible_exception(
        WrappedObject $wrappedObject,
        ExceptionFactory $exceptions
    ) {
        $obj = new ExampleClass();
        $wrappedObject->isInstantiated()
                      ->willReturn(true);
        $wrappedObject->getInstance()
                      ->willReturn($obj);
        $wrappedObject->getClassName()
                      ->willReturn('spec\LopSpec\Wrapper\Subject\ExampleClass');
        $exceptions->methodNotVisible('spec\LopSpec\Wrapper\Subject\ExampleClass',
            'privateMethod', [])
                   ->willReturn(new \LopSpec\Exception\Fracture\MethodNotVisibleException('Method "privateMethod" not visible.',
                       $obj, '"ExampleClass::privateMethod"', []))
                   ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Fracture\MethodNotVisibleException')
             ->duringCall('privateMethod');
    }
    function it_delegates_throwing_named_constructor_not_found_exception(WrappedObject $wrappedObject, ExceptionFactory $exceptions)
    {
        $obj = new \ArrayObject();
        $arguments = ['firstname', 'lastname'];

        $wrappedObject->isInstantiated()->willReturn(false);
        $wrappedObject->getInstance()->willReturn(null);
        $wrappedObject->getClassName()->willReturn('ArrayObject');
        $wrappedObject->getFactoryMethod()->willReturn('register');
        $wrappedObject->getArguments()->willReturn($arguments);

        $exceptions->namedConstructorNotFound('ArrayObject', 'register', $arguments)
            ->willReturn(new \LopSpec\Exception\Fracture\NamedConstructorNotFoundException(
                'Named constructor "register" not found.',
                $obj,
                '"ArrayObject::register"', []
            ))
            ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Fracture\NamedConstructorNotFoundException')
            ->duringCall('foo');
    }
    function it_delegates_throwing_property_not_found_exception(WrappedObject $wrappedObject, ExceptionFactory $exceptions)
    {
        $obj = new ExampleClass();

        $wrappedObject->isInstantiated()->willReturn(true);
        $wrappedObject->getInstance()->willReturn($obj);

        $exceptions->propertyNotFound($obj, 'nonExistentProperty')
            ->willReturn(new \LopSpec\Exception\Fracture\PropertyNotFoundException(
                'Property "nonExistentProperty" not found.',
                $obj,
                'nonExistentProperty'
            ))
            ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Fracture\PropertyNotFoundException')
            ->duringSet('nonExistentProperty', 'any value');
    }
    function it_delegates_throwing_setting_property_on_non_object_exception(ExceptionFactory $exceptions)
    {
        $exceptions->settingPropertyOnNonObject('foo')
            ->willReturn(new \LopSpec\Exception\Wrapper\SubjectException(
                'Setting property "foo" on a non-object.'
            ))
            ->shouldBeCalled();
        $this->shouldThrow('\LopSpec\Exception\Wrapper\SubjectException')
            ->duringSet('foo');
    }
    function it_dispatches_method_call_events(
        Dispatcher $dispatcher,
        WrappedObject $wrappedObject
    ) {
        $wrappedObject->isInstantiated()
                      ->willReturn(true);
        $wrappedObject->getInstance()
                      ->willReturn(new \ArrayObject());
        $dispatcher->dispatch('beforeMethodCall',
            Argument::type('LopSpec\Event\MethodCallEvent'))
                   ->shouldBeCalled();
        $dispatcher->dispatch('afterMethodCall',
            Argument::type('LopSpec\Event\MethodCallEvent'))
                   ->shouldBeCalled();
        $this->call('count');
    }
    function it_proxies_method_calls_to_wrapped_object(
        \ArrayObject $obj,
        WrappedObject $wrappedObject
    )
    {
        $obj->asort()
            ->shouldBeCalled();
        $wrappedObject->isInstantiated()
                      ->willReturn(true);
        $wrappedObject->getInstance()
                      ->willReturn($obj);
        $this->call('asort');
    }
    function it_sets_a_property_on_the_wrapped_object(
        WrappedObject $wrappedObject
    ) {
        $obj = new \stdClass();
        $obj->id = 1;
        $wrappedObject->isInstantiated()
                      ->willReturn(true);
        $wrappedObject->getInstance()
                      ->willReturn($obj);
        $this->set('id', 2)
             ->shouldReturn(2);
    }
    function let(
        WrappedObject $wrappedObject,
        ExampleNode $example,
        Dispatcher $dispatcher,
        ExceptionFactory $exceptions,
        Wrapper $wrapper
    ) {
        $this->beConstructedWith($wrappedObject, $example, $dispatcher,
            $exceptions, $wrapper);
    }
}

class ExampleClass
{
    private function privateMethod()
    {
    }
}
