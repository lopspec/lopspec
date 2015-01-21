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
namespace LopSpec\Console;

use InvalidArgumentException;
use LogicException;
use LopSpec\Extension;
use LopSpec\ServiceContainer;
use RuntimeException;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * The command line application entry point
 */
class Application extends
    BaseApplication
{
    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->container = new ServiceContainer();
        parent::__construct('lopspec', $version);
    }
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->container->set('console.input', $input);
        $this->container->set('console.output', $output);
        $this->container->set('console.helper.dialog', $this->getHelperSet()
                                                            ->get('dialog'));
        $assembler = new ContainerAssembler();
        $assembler->build($this->container);
        $this->loadConfigurationFile($input, $this->container);
        foreach ($this->container->getByPrefix('console.commands') as $command)
        {
            $this->add($command);
        }
        $this->setDispatcher($this->container->get('console_event_dispatcher'));
        $this->container->get('console.io')
                        ->setConsoleWidth($this->getTerminalWidth());
        return parent::doRun($input, $output);
    }
    /**
     * @return ServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }
    /**
     * Fixes an issue with definitions of the no-interaction option not being
     * completely shown in some cases
     */
    protected function getDefaultInputDefinition()
    {
        $description
            = 'Do not ask any interactive question (disables code generation).';
        $definition = parent::getDefaultInputDefinition();
        $options = $definition->getOptions();
        if (array_key_exists('no-interaction', $options)) {
            $option = $options['no-interaction'];
            $options['no-interaction'] = new InputOption($option->getName(),
                $option->getShortcut(), InputOption::VALUE_NONE, $description);
        }
        $options['config'] = new InputOption('config', 'c',
            InputOption::VALUE_REQUIRED,
            'Specify a custom location for the configuration file');
        $definition->setOptions($options);
        return $definition;
    }
    /**
     * @param InputInterface   $input
     * @param ServiceContainer $container
     *
     * @throws \RuntimeException
     */
    protected function loadConfigurationFile(
        InputInterface $input,
        ServiceContainer $container
    ) {
        $config = $this->parseConfigurationFile($input);
        foreach ($config as $key => $val) {
            if ('extensions' === $key && is_array($val)) {
                foreach ($val as $class) {
                    $extension = new $class();
                    if (!$extension instanceof Extension\ExtensionInterface) {
                        $mess
                            = sprintf('Extension class must implement'
                                      . ' ExtensionInterface but "%s" does not.',
                            $class);
                        throw new RuntimeException($mess);
                    }
                    $extension->load($container);
                }
            } else {
                $container->setParam($key, $val);
            }
        }
    }
    /**
     * @param InputInterface $input
     *
     * @return array
     */
    protected function parseConfigurationFile(InputInterface $input)
    {
        $cwd = str_replace('\\', '/', getcwd());
        $paths = [$cwd . '/lopspec.dist.yml', $cwd . '/lopspec.yml'];
        // Windows
        $personal = getenv('USERPROFILE');
        if (false !== $personal) {
            $paths[] = str_replace('\\', '/', $personal) . '/.lopspec.yml';
        }
        // Unix
        $personal = getenv('HOME');
        if (false !== $personal) {
            $paths[] = str_replace('\\', '/', $personal) . '/.lopspec.yml';
        }
        if ($input->hasParameterOption(['-c', '--config'])) {
            $paths[] = (string)$input->getParameterOption(['-c', '--config']);
        }
        $config = [];
        foreach ($paths as $path) {
            if ('' !== $path && is_readable($path) && is_file($path)) {
                try {
                    $result = Yaml::parse(file_get_contents($path));
                } catch (ParseException $exc) {
                    continue;
                }
                $config = array_replace_recursive($config, $result);
            }
        }
        return $config;
    }
    /**
     * @type ServiceContainer
     */
    private $container;
}
