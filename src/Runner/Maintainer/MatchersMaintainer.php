<?php

/*
 * This file is part of LopSpec, A php toolset to drive emergent
 * design by specification.
 *
 * (c) Marcello Duarte <marcello.duarte@gmail.com>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LopSpec\Runner\Maintainer;

use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Matcher;
use LopSpec\Matcher\MatcherInterface;
use LopSpec\Runner\CollaboratorManager;
use LopSpec\Runner\MatcherManager;
use LopSpec\SpecificationInterface;

class MatchersMaintainer implements MaintainerInterface
{
    /**
     * @param PresenterInterface $presenter
     * @param MatcherInterface[] $matchers
     */
    public function __construct(PresenterInterface $presenter, array $matchers)
    {
        $this->presenter = $presenter;
        $this->defaultMatchers = $matchers;
        @usort($this->defaultMatchers, function ($matcher1, $matcher2) {
            return $matcher2->getPriority() - $matcher1->getPriority();
        });
    }
    /**
     * @return int
     */
    public function getPriority()
    {
        return 50;
    }
    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {

        $matchers->replace($this->defaultMatchers);

        if (!$context instanceof Matcher\MatchersProviderInterface) {
            return;
        }

        foreach ($context->getMatchers() as $name => $matcher) {
            if ($matcher instanceof Matcher\MatcherInterface) {
                $matchers->add($matcher);
            } else {
                $matchers->add(new Matcher\CallbackMatcher(
                    $name, $matcher, $this->presenter
                ));
            }
        }
    }
    /**
     * @param ExampleNode $example
     *
     * @return bool
     */
    public function supports(ExampleNode $example)
    {
        return true;
    }
    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }
    /**
     * @type MatcherInterface[]
     */
    private $defaultMatchers = [];
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
    /**
     * @type \LopSpec\Wrapper\Unwrapper
     */
    private $unwrapper;
}
