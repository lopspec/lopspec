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

use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Runner\CollaboratorManager;
use LopSpec\Runner\MatcherManager;
use LopSpec\SpecificationInterface;

class LetAndLetgoMaintainer implements MaintainerInterface
{
    /**
     * @return int
     */
    public function getPriority()
    {
        return 10;
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
        if (!$example->getSpecification()->getClassReflection()->hasMethod('let')) {
            return;
        }

        $reflection = $example->getSpecification()->getClassReflection()->getMethod('let');
        $reflection->invokeArgs($context, $collaborators->getArgumentsFor($reflection));
    }
    /**
     * @param ExampleNode $example
     *
     * @return bool
     */
    public function supports(ExampleNode $example)
    {
        return $example->getSpecification()
                       ->getClassReflection()
                       ->hasMethod('let')
               || $example->getSpecification()
                          ->getClassReflection()
                          ->hasMethod('letgo');
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
        if (!$example->getSpecification()->getClassReflection()->hasMethod('letgo')) {
            return;
        }

        $reflection = $example->getSpecification()->getClassReflection()->getMethod('letgo');
        $reflection->invokeArgs($context, $collaborators->getArgumentsFor($reflection));
    }
}
