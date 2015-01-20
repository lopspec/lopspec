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
namespace LopSpec\Exception\Wrapper;

use LopSpec\Exception\Exception;

/**
 * Class MatcherNotFoundException holds information about matcher not found
 * exception
 */
class MatcherNotFoundException extends Exception
{
    /**
     * @param string $message
     * @param string $keyword
     * @param mixed  $subject
     * @param array  $arguments
     */
    public function __construct($message, $keyword, $subject, array $arguments)
    {
        parent::__construct($message);

        $this->keyword   = $keyword;
        $this->subject   = $subject;
        $this->arguments = $arguments;
    }
    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * @type array
     */
    private $arguments;
    /**
     * @type string
     */
    private $keyword;
    /**
     * @type mixed
     */
    private $subject;
}
