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

use Prophecy\Prophecy\ObjectProphecy;

class Collaborator implements WrapperInterface
{
    /**
     * @param ObjectProphecy $prophecy
     */
    public function __construct(ObjectProphecy $prophecy)
    {
        $this->prophecy  = $prophecy;
    }
    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array([$this->prophecy, '__call'],
            [$method, $arguments]);
    }
    /**
     * @param string $parameter
     *
     * @return mixed
     */
    public function __get($parameter)
    {
        return $this->prophecy->$parameter;
    }
    /**
     * @param string $parameter
     * @param mixed  $value
     */
    public function __set($parameter, $value)
    {
        $this->prophecy->$parameter = $value;
    }
    /**
     * @param string $classOrInterface
     */
    public function beADoubleOf($classOrInterface)
    {
        if (interface_exists($classOrInterface)) {
            $this->prophecy->willImplement($classOrInterface);
        } else {
            $this->prophecy->willExtend($classOrInterface);
        }
    }
    /**
     * @param array $arguments
     */
    public function beConstructedWith(array $arguments = null)
    {
        $this->prophecy->willBeConstructedWith($arguments);
    }
    /**
     * @return object
     */
    public function getWrappedObject()
    {
        return $this->prophecy->reveal();
    }
    /**
     * @param string $interface
     */
    public function implement($interface)
    {
        $this->prophecy->willImplement($interface);
    }
    /**
     * @type \Prophecy\Prophecy\ObjectProphecy
     */
    private $prophecy;
}
