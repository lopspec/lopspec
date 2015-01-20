<?php

namespace spec\LopSpec\Event;

use Exception;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Loader\Node\SpecificationNode;
use LopSpec\Loader\Suite;
use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpectationEventSpec extends ObjectBehavior
{
    function it_is_an_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
        $this->shouldBeAnInstanceOf('LopSpec\Event\EventInterface');
    }
    function it_provides_a_link_to_arguments()
    {
        $this->getArguments()
             ->shouldReturn(['methodArguments']);
    }
    function it_provides_a_link_to_example($example)
    {
        $this->getExample()->shouldReturn($example);
    }
    function it_provides_a_link_to_exception($exception)
    {
        $this->getException()
             ->shouldReturn($exception);
    }
    function it_provides_a_link_to_matcher($matcher)
    {
        $this->getMatcher()
             ->shouldReturn($matcher);
    }
    function it_provides_a_link_to_method()
    {
        $this->getMethod()->shouldReturn('calledMethod');
    }
    function it_provides_a_link_to_result()
    {
        $this->getResult()->shouldReturn($this->FAILED);
    }
    function it_provides_a_link_to_specification($specification)
    {
        $this->getSpecification()
             ->shouldReturn($specification);
    }
    function it_provides_a_link_to_subject($subject)
    {
        $this->getSubject()
             ->shouldReturn($subject);
    }
    function it_provides_a_link_to_suite($suite)
    {
        $this->getSuite()
             ->shouldReturn($suite);
    }
    function let(
        Suite $suite,
        SpecificationNode $specification,
        ExampleNode $example,
        MatcherInterface $matcher,
        $subject,
        Exception $exception
    ) {
        $method = 'calledMethod';
        $arguments = ['methodArguments'];
        $this->beConstructedWith($example, $matcher, $subject, $method,
            $arguments, $this->FAILED, $exception);
        $example->getSpecification()
                ->willReturn($specification);
        $specification->getSuite()
                      ->willReturn($suite);
    }
}
