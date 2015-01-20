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
namespace LopSpec\Wrapper;

use LopSpec\Exception\ExceptionFactory;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Loader\Node\ExampleNode;
use LopSpec\Runner\MatcherManager;
use LopSpec\Wrapper\Subject\Caller;
use LopSpec\Wrapper\Subject\ExpectationFactory;
use LopSpec\Wrapper\Subject\SubjectWithArrayAccess;
use LopSpec\Wrapper\Subject\WrappedObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Wrapper
{
    /**
     * @param MatcherManager           $matchers
     * @param PresenterInterface       $presenter
     * @param EventDispatcherInterface $dispatcher
     * @param ExampleNode              $example
     */
    public function __construct(MatcherManager $matchers, PresenterInterface $presenter,
        EventDispatcherInterface $dispatcher, ExampleNode $example)
    {
        $this->matchers = $matchers;
        $this->presenter = $presenter;
        $this->dispatcher = $dispatcher;
        $this->example = $example;
    }
    /**
     * @param object $value
     *
     * @return Subject
     */
    public function wrap($value = null)
    {
        $exceptionFactory   = new ExceptionFactory($this->presenter);
        $wrappedObject      = new WrappedObject($value, $this->presenter);
        $caller             = new Caller($wrappedObject, $this->example, $this->dispatcher, $exceptionFactory, $this);
        $arrayAccess        = new SubjectWithArrayAccess($caller, $this->presenter, $this->dispatcher);
        $expectationFactory = new ExpectationFactory($this->example, $this->dispatcher, $this->matchers);

        return new Subject(
            $value, $this, $wrappedObject, $caller, $arrayAccess, $expectationFactory
        );
    }
    /**
     * @type \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @type \LopSpec\Loader\Node\ExampleNode
     */
    private $example;
    /**
     * @type \LopSpec\Runner\MatcherManager
     */
    private $matchers;
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
