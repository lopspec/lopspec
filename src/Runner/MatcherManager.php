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
namespace LopSpec\Runner;

use LopSpec\Exception\Wrapper\MatcherNotFoundException;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Matcher\MatcherInterface;

class MatcherManager
{
    /**
     * @param PresenterInterface $presenter
     */
    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }
    /**
     * @param MatcherInterface $matcher
     */
    public function add(MatcherInterface $matcher)
    {
        $this->matchers[] = $matcher;
        @usort($this->matchers, function ($matcher1, $matcher2) {
            return $matcher2->getPriority() - $matcher1->getPriority();
        });
    }
    /**
     * @param string $keyword
     * @param mixed  $subject
     * @param array  $arguments
     *
     * @return MatcherInterface
     *
     * @throws \LopSpec\Exception\Wrapper\MatcherNotFoundException
     */
    public function find($keyword, $subject, array $arguments)
    {
        foreach ($this->matchers as $matcher) {
            if (true === $matcher->supports($keyword, $subject, $arguments)) {
                return $matcher;
            }
        }

        throw new MatcherNotFoundException(
            sprintf('No %s(%s) matcher found for %s.',
                $this->presenter->presentString($keyword),
                $this->presenter->presentValue($arguments),
                $this->presenter->presentValue($subject)
            ),
            $keyword, $subject, $arguments
        );
    }
    /**
     * Replaces matchers with an already-sorted list
     *
     * @param MatcherInterface[] $matchers
     */
    public function replace(array $matchers)
    {
        $this->matchers = $matchers;
    }
    /**
     * @type MatcherInterface[]
     */
    private $matchers = [];
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
