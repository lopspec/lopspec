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
namespace LopSpec\Config;

class OptionsConfig
{
    /**
     * @param bool $stopOnFailureEnabled
     * @param bool $codeGenerationEnabled
     * @param bool $reRunEnabled
     * @param bool $fakingEnabled
     * @param string|bool $bootstrapPath
     */
    public function __construct($stopOnFailureEnabled, $codeGenerationEnabled, $reRunEnabled, $fakingEnabled, $bootstrapPath)
    {
        $this->stopOnFailureEnabled  = $stopOnFailureEnabled;
        $this->codeGenerationEnabled = $codeGenerationEnabled;
        $this->reRunEnabled = $reRunEnabled;
        $this->fakingEnabled = $fakingEnabled;
        $this->bootstrapPath = $bootstrapPath;
    }
    public function getBootstrapPath()
    {
        return $this->bootstrapPath;
    }
    /**
     * @return bool
     */
    public function isCodeGenerationEnabled()
    {
        return $this->codeGenerationEnabled;
    }
    public function isFakingEnabled()
    {
        return $this->fakingEnabled;
    }
    public function isReRunEnabled()
    {
        return $this->reRunEnabled;
    }
    /**
     * @return bool
     */
    public function isStopOnFailureEnabled()
    {
        return $this->stopOnFailureEnabled;
    }
    /**
     * @type string|bool
     */
    private $bootstrapPath;
    /**
     * @type bool
     */
    private $codeGenerationEnabled;
    /**
     * @type bool
     */
    private $fakingEnabled;
    /**
     * @type bool
     */
    private $reRunEnabled;
    /**
     * @type bool
     */
    private $stopOnFailureEnabled;
}
