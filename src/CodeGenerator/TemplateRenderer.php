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
namespace LopSpec\CodeGenerator;

use LopSpec\Util\Filesystem;

/**
 * Template renderer class can render templates in registered locations. It comes
 * with a simple placeholder string replacement for specified fields
 */
class TemplateRenderer
{
    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?: new Filesystem();
    }
    /**
     * @param string $location
     */
    public function appendLocation($location)
    {
        array_push($this->locations, $this->normalizeLocation($location));
    }
    /**
     * @return array
     */
    public function getLocations()
    {
        return $this->locations;
    }
    /**
     * @param string $location
     */
    public function prependLocation($location)
    {
        array_unshift($this->locations, $this->normalizeLocation($location));
    }
    /**
     * @param string $name
     * @param array  $values
     *
     * @return string
     */
    public function render($name, array $values = [])
    {
        foreach ($this->locations as $location) {
            $path = $location.DIRECTORY_SEPARATOR.$this->normalizeLocation($name, true).'.tpl';

            if ($this->filesystem->pathExists($path)) {
                return $this->renderString($this->filesystem->getFileContents($path), $values);
            }
        }
    }
    /**
     * @param string $template
     * @param array  $values
     *
     * @return string
     */
    public function renderString($template, array $values = [])
    {
        return strtr($template, $values);
    }
    /**
     * @param array $locations
     */
    public function setLocations(array $locations)
    {
        $this->locations = array_map([$this, 'normalizeLocation'], $locations);
    }
    /**
     * @param string $location
     * @param bool   $trimLeft
     *
     * @return string
     */
    private function normalizeLocation($location, $trimLeft = false)
    {
        $location = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $location);
        $location = rtrim($location, DIRECTORY_SEPARATOR);

        if ($trimLeft) {
            $location = ltrim($location, DIRECTORY_SEPARATOR);
        }

        return $location;
    }
    /**
     * @type \LopSpec\Util\Filesystem
     */
    private $filesystem;
    /**
     * @type array
     */
    private $locations = [];
}
