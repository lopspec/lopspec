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
namespace LopSpec\Exception\Example;

/**
 * Class ErrorException holds information about generic php errors
 */
class ErrorException extends ExampleException
{
    /**
     * Initializes error handler exception.
     *
     * @param string $level   error level
     * @param string $message error message
     * @param string $file    error file
     * @param string $line    error line
     */
    public function __construct($level, $message, $file, $line)
    {
        parent::__construct(sprintf('%s: %s in %s line %d',
            isset($this->levels[$level]) ? $this->levels[$level] : $level,
            $message,
            $file,
            $line
        ));
    }
    /**
     * @type array
     */
    private $levels
        = [
            E_WARNING           => 'warning',
            E_NOTICE            => 'notice',
            E_USER_ERROR        => 'error',
            E_USER_WARNING      => 'warning',
            E_USER_NOTICE       => 'notice',
            E_STRICT            => 'notice',
            E_RECOVERABLE_ERROR => 'error',
        ];
}
