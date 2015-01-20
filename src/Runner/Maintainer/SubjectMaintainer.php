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
use LopSpec\Runner\CollaboratorManager;
use LopSpec\Runner\MatcherManager;
use LopSpec\SpecificationInterface;
use LopSpec\Wrapper\Unwrapper;
use LopSpec\Wrapper\Wrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SubjectMaintainer implements MaintainerInterface
{
    /**
     * @param PresenterInterface       $presenter
     * @param Unwrapper                $unwrapper
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(PresenterInterface $presenter, Unwrapper $unwrapper, EventDispatcherInterface $dispatcher)
    {
        $this->presenter = $presenter;
        $this->unwrapper = $unwrapper;
        $this->dispatcher = $dispatcher;
    }
    /**
     * @return int
     */
    public function getPriority()
    {
        return 100;
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
        $subjectFactory = new Wrapper($matchers, $this->presenter, $this->dispatcher, $example);
        $subject = $subjectFactory->wrap(null);
        $subject->beAnInstanceOf(
            $example->getSpecification()->getResource()->getSrcClassname()
        );

        $context->setSpecificationSubject($subject);
    }
    /**
     * @param ExampleNode $example
     *
     * @return boolean
     */
    public function supports(ExampleNode $example)
    {
        return $example->getSpecification()
                       ->getClassReflection()
                       ->implementsInterface('LopSpec\Wrapper\SubjectContainerInterface');
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
     * @type \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
    /**
     * @type \LopSpec\Wrapper\Unwrapper
     */
    private $unwrapper;
}
