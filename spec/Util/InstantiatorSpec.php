<?php

namespace spec\LopSpec\Util;

use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class InstantiatorSpec extends ObjectBehavior
{
    function it_complains_if_class_does_not_exist()
    {
        $this->shouldThrow('LopSpec\Exception\Fracture\ClassNotFoundException')
             ->duringInstantiate('NonExistingClass');
    }
    function it_creates_an_instance()
    {
        $this->instantiate('spec\LopSpec\Util\NoConstructor')
             ->shouldBeAnInstanceOf('spec\LopSpec\Util\NoConstructor');
    }
    function it_creates_an_instance_ignoring_constructor()
    {
        $this->instantiate('spec\LopSpec\Util\WithConstructor')
             ->shouldBeAnInstanceOf('spec\LopSpec\Util\WithConstructor');
    }
    function it_creates_an_instance_with_properties()
    {
        $this->instantiate('spec\LopSpec\Util\WithProperties')
             ->shouldBeAnInstanceOf('spec\LopSpec\Util\WithProperties');
    }
}

class NoConstructor
{
}

class WithConstructor
{
    public function __construct($requiredArgument)
    {
    }
}

class WithProperties
{
    public $baz;
    protected $bar;
    private $foo;
}
