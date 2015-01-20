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
 * Class ClassNotFoundException holds information about class not found exception
 */
class ClassNotFoundException extends FractureException
{
    /**
     * @param string $message
     * @param string $classname
     */
    public function __construct($message, $classname)
    {
        parent::__construct($message);

        $this->classname = $classname;
    }
    /**
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }
    /**
     * @type string
     */
    private $classname;
}
