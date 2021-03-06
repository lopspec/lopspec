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
namespace LopSpec\CodeGenerator\Generator;

use LopSpec\CodeGenerator\TemplateRenderer;
use LopSpec\Console\IO;
use LopSpec\Locator\ResourceInterface;
use LopSpec\Util\Filesystem;

class NamedConstructorGenerator implements GeneratorInterface
{
    /**
     * @param IO               $io
     * @param TemplateRenderer $templates
     * @param Filesystem       $filesystem
     */
    public function __construct(IO $io, TemplateRenderer $templates, Filesystem $filesystem = null)
    {
        $this->io         = $io;
        $this->templates  = $templates;
        $this->filesystem = $filesystem ?: new Filesystem();
    }
    /**
     * @param ResourceInterface $resource
     * @param array             $data
     */
    public function generate(ResourceInterface $resource, array $data = [])
    {
        $filepath   = $resource->getSrcFilename();
        $methodName = $data['name'];
        $arguments  = $data['arguments'];

        $content = $this->getContent($resource, $methodName, $arguments);

        $code = $this->filesystem->getFileContents($filepath);
        $code = preg_replace('/}[ \n]*$/', rtrim($content)."\n}\n", trim($code));
        $this->filesystem->putFileContents($filepath, $code);

        $this->io->writeln(sprintf(
            "<info>Method <value>%s::%s()</value> has been created.</info>\n",
            $resource->getSrcClassname(), $methodName
        ), 2);
    }
    /**
     * @return int
     */
    public function getPriority()
    {
        return 0;
    }
    /**
     * @param ResourceInterface $resource
     * @param string            $generation
     * @param array             $data
     *
     * @return bool
     */
    public function supports(
        ResourceInterface $resource,
        $generation,
        array $data
    ) {
        return 'named_constructor' === $generation;
    }
    /**
     * @param  ResourceInterface $resource
     * @param  string            $methodName
     * @param  array             $arguments
     * @return string
     */
    private function getContent(ResourceInterface $resource, $methodName, $arguments)
    {
        $className = $resource->getName();
        $class = $resource->getSrcClassname();

        $template = new CreateObjectTemplate($this->templates, $methodName, $arguments, $className);

        if (method_exists($class, '__construct')) {
            $template = new ExistingConstructorTemplate(
                $this->templates,
                $methodName,
                $arguments,
                $className,
                $class
            );
        }

        return $template->getContent();
    }
    /**
     * @type \LopSpec\Util\Filesystem
     */
    private $filesystem;
    /**
     * @type \LopSpec\Console\IO
     */
    private $io;
    /**
     * @type \LopSpec\CodeGenerator\TemplateRenderer
     */
    private $templates;
}
