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
namespace LopSpec\Exception\Fracture;

/**
 * Class InterfaceNotImplementedException holds information about interface
 * not implemented exception
 */
class InterfaceNotImplementedException extends FractureException
{
    /**
     * @param string $message
     * @param mixed  $subject
     * @param string $interface
     */
    public function __construct($message, $subject, $interface)
    {
        parent::__construct($message);

        $this->subject   = $subject;
        $this->interface = $interface;
    }
    /**
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }
    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * @type string
     */
    private $interface;
    /**
     * @type mixed
     */
    private $subject;
}
