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

use LopSpec\Locator\ResourceInterface;

/**
 * The Class Generator is responsible for generating the classes from a resource
 * in the appropriate folder using the template provided
 */
class ClassGenerator extends PromptingGenerator
{
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
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'class' === $generation;
    }
    /**
     * @param ResourceInterface $resource
     *
     * @return string
     */
    protected function getFilePath(ResourceInterface $resource)
    {
        return $resource->getSrcFilename();
    }
    /**
     * @param ResourceInterface $resource
     * @param string            $filepath
     *
     * @return string
     */
    protected function getGeneratedMessage(
        ResourceInterface $resource,
        $filepath
    )
    {
        return sprintf("<info>Class <value>%s</value> created in <value>%s</value>.</info>\n",
            $resource->getSrcClassname(), $filepath
        );
    }
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return file_get_contents(__DIR__.'/templates/class.template');
    }
    /**
     * @param ResourceInterface $resource
     * @param string            $filepath
     *
     * @return string
     */
    protected function renderTemplate(ResourceInterface $resource, $filepath)
    {
        $values = [
            '%filepath%'        => $filepath,
            '%name%'            => $resource->getName(),
            '%namespace%'       => $resource->getSrcNamespace(),
            '%namespace_block%' => '' !== $resource->getSrcNamespace()
                ? sprintf("\n\nnamespace %s;", $resource->getSrcNamespace())
                : '',
        ];
        if (!$content = $this->getTemplateRenderer()
                             ->render('class', $values)
        ) {
            $content = $this->getTemplateRenderer()
                            ->renderString($this->getTemplate(), $values);
        }

        return $content;
    }
}