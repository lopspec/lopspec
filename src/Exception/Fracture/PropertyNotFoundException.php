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
 * Class PropertyNotFoundException holds information about property not found
 * exceptions
 */
class PropertyNotFoundException extends FractureException
{
    /**
     * @param string $message
     * @param mixed  $subject
     * @param string $property
     */
    public function __construct($message, $subject, $property)
    {
        parent::__construct($message);

        $this->subject = $subject;
        $this->property  = $property;
    }
    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
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
    private $property;
    /**
     * @type mixed
     */
    private $subject;
}
