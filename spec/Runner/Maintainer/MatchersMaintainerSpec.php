<?php

namespace spec\LopSpec\Runner\Maintainer;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Matcher\MatcherInterface;
use LopSpec\ObjectBehavior;
use LopSpec\Runner\CollaboratorManager;
use LopSpec\Runner\MatcherManager;
use LopSpec\SpecificationInterface;
use Prophecy\Argument;

class MatchersMaintainerSpec extends ObjectBehavior
{
    function it_should_add_default_matchers_to_the_matcher_manager(
        PresenterInterface $presenter, ExampleNode $example, SpecificationInterface $context,
        MatcherManager $matchers, CollaboratorManager $collaborators, MatcherInterface $matcher)
    {
        $this->beConstructedWith($presenter, [$matcher]);
        $this->prepare($example, $context, $matchers, $collaborators);
        $matchers->replace([$matcher])
                 ->shouldHaveBeenCalled();
    }
}
