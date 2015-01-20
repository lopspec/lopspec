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

use LopSpec\Exception\Wrapper\CollaboratorException;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Wrapper\Collaborator;
use ReflectionFunctionAbstract;

class CollaboratorManager
{
    /**
     * @param PresenterInterface $presenter
     */
    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }
    /**
     * @param string $name
     *
     * @return Collaborator
     *
     * @throws \LopSpec\Exception\Wrapper\CollaboratorException
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new CollaboratorException(
                sprintf('Collaborator %s not found.', $this->presenter->presentString($name))
            );
        }

        return $this->collaborators[$name];
    }
    /**
     * @param ReflectionFunctionAbstract $function
     *
     * @return array
     */
    public function getArgumentsFor(ReflectionFunctionAbstract $function)
    {
        $parameters = [];
        foreach ($function->getParameters() as $parameter) {
            if ($this->has($parameter->getName())) {
                $parameters[] = $this->get($parameter->getName());
            } else {
                $parameters[] = null;
            }
        }

        return $parameters;
    }
    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->collaborators[$name]);
    }
    /**
     * @param string       $name
     * @param Collaborator $collaborator
     */
    public function set($name, $collaborator)
    {
        $this->collaborators[$name] = $collaborator;
    }
    /**
     * @type Collaborator[]
     */
    private $collaborators = [];
    /**
     * @type \LopSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
}
