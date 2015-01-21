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

use FilePathNormalizer\FilePathNormalizer;
use InvalidArgumentException;
use LopSpec\CodeGenerator;
use LopSpec\Config\OptionsConfig;
use LopSpec\Formatter as SpecFormatter;
use LopSpec\Formatter\Presenter\PresenterInterface;
use LopSpec\Listener;
use LopSpec\Listener\StatisticsCollector;
use LopSpec\Loader;
use LopSpec\Locator;
use LopSpec\Matcher;
use LopSpec\Process\ReRunner;
use LopSpec\Runner;
use LopSpec\ServiceContainer;
use LopSpec\Util\Filesystem;
use LopSpec\Util\MethodAnalyser;
use LopSpec\Wrapper;
use RuntimeException;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Class ContainerAssembler
 */
class ContainerAssembler
{
    /**
     * @param ServiceContainer $container
     */
    public function build(ServiceContainer $container)
    {
        $this->setupIO($container)
             ->setupEventDispatcher($container)
             ->setupConsoleEventDispatcher($container)
             ->setupGenerators($container)
             ->setupPresenter($container)
             ->setupNormalizer($container)
             ->setupFilesystem($container)
             ->setupLocator($container)
             ->setupLoader($container)
             ->setupFormatter($container)
             ->setupRunner($container)
             ->setupCommands($container)
             ->setupResultConverter($container)
             ->setupRerunner($container)
             ->setupMatchers($container);
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupCommands(ServiceContainer $container)
    {
        $container->setShared('console.commands.run', function () {
            return new Command\RunCommand();
        });
        $container->setShared('console.commands.describe', function () {
            return new Command\DescribeCommand();
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupConsoleEventDispatcher(ServiceContainer $container)
    {
        $container->setShared('console_event_dispatcher',
            function (ServiceContainer $c) {
                $dispatcher = new EventDispatcher();
                array_map([$dispatcher, 'addSubscriber'],
                    $c->getByPrefix('console_event_dispatcher.listeners'));
                return $dispatcher;
            });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupEventDispatcher(ServiceContainer $container)
    {
        $container->setShared('event_dispatcher',
            function (ServiceContainer $c) {
                $dispatcher = new EventDispatcher();
                array_map([$dispatcher, 'addSubscriber'],
                    $c->getByPrefix('event_dispatcher.listeners'));
                return $dispatcher;
            });
        $container->setShared('event_dispatcher.listeners.stats', function () {
            return new Listener\StatisticsCollector();
        });
        $container->setShared('event_dispatcher.listeners.class_not_found',
            function (ServiceContainer $c) {
                return new Listener\ClassNotFoundListener($c->get('console.io'),
                    $c->get('locator.resource_manager'),
                    $c->get('code_generator'));
            });
        $container->setShared('event_dispatcher.listeners.named_constructor_not_found',
            function (ServiceContainer $c) {
                return new Listener\NamedConstructorNotFoundListener($c->get('console.io'),
                    $c->get('locator.resource_manager'),
                    $c->get('code_generator'));
            });
        $container->setShared('event_dispatcher.listeners.method_not_found',
            function (ServiceContainer $c) {
                return new Listener\MethodNotFoundListener($c->get('console.io'),
                    $c->get('locator.resource_manager'),
                    $c->get('code_generator'));
            });
        $container->setShared('event_dispatcher.listeners.stop_on_failure',
            function (ServiceContainer $c) {
                return new Listener\StopOnFailureListener($c->get('console.io'));
            });
        $container->setShared('event_dispatcher.listeners.rerun',
            function (ServiceContainer $c) {
                return new Listener\RerunListener($c->get('process.rerunner'));
            });
        $container->setShared('event_dispatcher.listeners.method_returned_null',
            function (ServiceContainer $c) {
                return new Listener\MethodReturnedNullListener($c->get('console.io'),
                    $c->get('locator.resource_manager'),
                    $c->get('code_generator'), $c->get('util.method_analyser'));
            });
        $container->setShared('util.method_analyser', function () {
            return new MethodAnalyser();
        });
        $container->setShared('event_dispatcher.listeners.bootstrap',
            function (ServiceContainer $c) {
                return new Listener\BootstrapListener($c->get('console.io'));
            });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupFilesystem(ServiceContainer $container)
    {
        $container->setShared('filesystem.filesystem',
            function (ServiceContainer $c) {
                return new Filesystem();
            });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupFormatter(ServiceContainer $container)
    {
        $container->set('formatter.formatters.progress',
            function (ServiceContainer $c) {
                /**
                 * @type PresenterInterface $presenter
                 */
                $presenter = $c->get('formatter.presenter');
                /**
                 * @type IO $consoleIo
                 */
                $consoleIo = $c->get('console.io');
                /**
                 * @type StatisticsCollector $stats
                 */
                $stats = $c->get('event_dispatcher.listeners.stats');
                return new SpecFormatter\ProgressFormatter($presenter,
                    $consoleIo, $stats);
            });
        $container->set('formatter.formatters.pretty',
            function (ServiceContainer $c) {
                /**
                 * @type PresenterInterface $presenter
                 */
                $presenter = $c->get('formatter.presenter');
                /**
                 * @type IO $consoleIo
                 */
                $consoleIo = $c->get('console.io');
                /**
                 * @type StatisticsCollector $stats
                 */
                $stats = $c->get('event_dispatcher.listeners.stats');
                return new SpecFormatter\PrettyFormatter($presenter, $consoleIo,
                    $stats);
            });
        $container->set('formatter.formatters.junit',
            function (ServiceContainer $c) {
                /**
                 * @type PresenterInterface $presenter
                 */
                $presenter = $c->get('formatter.presenter');
                /**
                 * @type IO $consoleIo
                 */
                $consoleIo = $c->get('console.io');
                /**
                 * @type StatisticsCollector $stats
                 */
                $stats = $c->get('event_dispatcher.listeners.stats');
                return new SpecFormatter\JUnitFormatter($presenter, $consoleIo,
                    $stats);
            });
        $container->set('formatter.formatters.dot',
            function (ServiceContainer $c) {
                /**
                 * @type PresenterInterface $presenter
                 */
                $presenter = $c->get('formatter.presenter');
                /**
                 * @type IO $consoleIo
                 */
                $consoleIo = $c->get('console.io');
                /**
                 * @type StatisticsCollector $stats
                 */
                $stats = $c->get('event_dispatcher.listeners.stats');
                return new SpecFormatter\DotFormatter($presenter, $consoleIo,
                    $stats);
            });
        $container->set('formatter.formatters.html',
            function (ServiceContainer $c) {
                $io = new SpecFormatter\Html\IO();
                $template = new SpecFormatter\Html\Template($io);
                $factory = new SpecFormatter\Html\ReportItemFactory($template);
                $presenter
                    = new SpecFormatter\Html\HtmlPresenter($c->get('formatter.presenter.differ'));
                return new SpecFormatter\HtmlFormatter($factory, $presenter,
                    $io, $c->get('event_dispatcher.listeners.stats'));
            });
        $container->set('formatter.formatters.h',
            function (ServiceContainer $c) {
                return $c->get('formatter.formatters.html');
            });
        $container->addConfigurator(function (ServiceContainer $c) {
            $formatterName = $c->getParam('formatter.name', 'progress');
            $c->get('console.output')
              ->setFormatter(new Formatter($c->get('console.output')
                                             ->isDecorated()));
            try {
                $formatter = $c->get('formatter.formatters.' . $formatterName);
            } catch (InvalidArgumentException $e) {
                throw new RuntimeException(sprintf('Formatter not recognised: "%s"',
                    $formatterName));
            }
            $c->set('event_dispatcher.listeners.formatter', $formatter);
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupGenerators(ServiceContainer $container)
    {
        $container->setShared('code_generator', function (ServiceContainer $c) {
            $generator = new CodeGenerator\GeneratorManager();
            array_map([$generator, 'registerGenerator'],
                $c->getByPrefix('code_generator.generators'));
            return $generator;
        });
        $container->set('code_generator.generators.specification',
            function (ServiceContainer $c) {
                return new CodeGenerator\Generator\SpecificationGenerator($c->get('console.io'),
                    $c->get('code_generator.templates'));
            });
        $container->set('code_generator.generators.class',
            function (ServiceContainer $c) {
                return new CodeGenerator\Generator\ClassGenerator($c->get('console.io'),
                    $c->get('code_generator.templates'));
            });
        $container->set('code_generator.generators.method',
            function (ServiceContainer $c) {
                return new CodeGenerator\Generator\MethodGenerator($c->get('console.io'),
                    $c->get('code_generator.templates'));
            });
        $container->set('code_generator.generators.returnConstant',
            function (ServiceContainer $c) {
                return new CodeGenerator\Generator\ReturnConstantGenerator($c->get('console.io'),
                    $c->get('code_generator.templates'));
            });
        $container->set('code_generator.generators.named_constructor',
            function (ServiceContainer $c) {
                return new CodeGenerator\Generator\NamedConstructorGenerator($c->get('console.io'),
                    $c->get('code_generator.templates'));
            });
        $container->setShared('code_generator.templates',
            function (ServiceContainer $c) {
                $renderer = new CodeGenerator\TemplateRenderer();
                $renderer->setLocations($c->getParam('code_generator.templates.paths',
                    []));
                return $renderer;
            });
        if (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
            $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
        } else {
            $home = $_SERVER['HOME'];
        }
        $container->setParam('code_generator.templates.paths', [
            rtrim(getcwd(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . '.phpspec',
            rtrim($home, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . '.phpspec',
        ]);
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupIO(ServiceContainer $container)
    {
        $container->setShared('console.io', function (ServiceContainer $c) {
            return new IO($c->get('console.input'), $c->get('console.output'),
                $c->get('console.helper.dialog'),
                new OptionsConfig($c->getParam('stop_on_failure', false),
                    $c->getParam('code_generation', true),
                    $c->getParam('rerun', true), $c->getParam('fake', false),
                    $c->getParam('bootstrap', false)));
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupLoader(ServiceContainer $container)
    {
        $container->setShared('loader.resource_loader',
            function (ServiceContainer $c) {
                return new Loader\ResourceLoader($c->get('locator.resource_manager'));
            });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupLocator(ServiceContainer $container)
    {
        $container->setShared('locator.resource_manager',
            function (ServiceContainer $c) {
                $manager = new Locator\ResourceManager();
                array_map([$manager, 'registerLocator'],
                    $c->getByPrefix('locator.locators'));
                return $manager;
            });
        $container->addConfigurator(function (ServiceContainer $c) {
            /**
             * @type FilePathNormalizer $fpn
             */
            $fpn = $c->get('path_normalizer');
            /**
             * @type Filesystem $filesystem
             */
            $filesystem = $c->get('filesystem.filesystem');
            $cwd = $fpn->normalizePath(getcwd());
            /**
             * @type array $suites
             */
            $suites = $c->getParam('suites', ['main' => '']);
            foreach ($suites as $name => $suite) {
                $suite = is_array($suite) ? $suite
                    : ['src_namespace' => $suite];
                $srcNS = !empty($suite['src_namespace'])
                    ? $suite['src_namespace'] : '';
                $specNS = !empty($suite['spec_namespace'])
                    ? $suite['spec_namespace'] : 'Spec';
                $srcPath = !empty($suite['src_path']) ? $suite['src_path']
                    : 'src';
                $srcPath = $fpn->normalizePath($cwd . $srcPath);
                $specPath = !empty($suite['spec_path']) ? $suite['spec_path']
                    : 'spec';
                $specPath = $fpn->normalizePath($cwd . $specPath);
                $filesystem->checkExistsOrCreateDir($srcPath);
                $filesystem->checkExistsOrCreateDir($specPath);
                $c->set(sprintf('locator.locators.%s_suite', $name),
                    function () use (
                        $srcNS,
                        $specNS,
                        $srcPath,
                        $specPath,
                        $fpn,
                        $filesystem
                    ) {
                        return new Locator\PSR4\Locator($srcNS, $specNS,
                            $srcPath, $specPath, $fpn, $filesystem);
                    });
            }
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupMatchers(ServiceContainer $container)
    {
        $container->set('matchers.identity', function (ServiceContainer $c) {
            return new Matcher\IdentityMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.comparison', function (ServiceContainer $c) {
            return new Matcher\ComparisonMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.throwm', function (ServiceContainer $c) {
            return new Matcher\ThrowMatcher($c->get('unwrapper'),
                $c->get('formatter.presenter'));
        });
        $container->set('matchers.type', function (ServiceContainer $c) {
            return new Matcher\TypeMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.object_state',
            function (ServiceContainer $c) {
                return new Matcher\ObjectStateMatcher($c->get('formatter.presenter'));
            });
        $container->set('matchers.scalar', function (ServiceContainer $c) {
            return new Matcher\ScalarMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.array_count', function (ServiceContainer $c) {
            return new Matcher\ArrayCountMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.array_key', function (ServiceContainer $c) {
            return new Matcher\ArrayKeyMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.array_contain',
            function (ServiceContainer $c) {
                return new Matcher\ArrayContainMatcher($c->get('formatter.presenter'));
            });
        $container->set('matchers.string_start',
            function (ServiceContainer $c) {
                return new Matcher\StringStartMatcher($c->get('formatter.presenter'));
            });
        $container->set('matchers.string_end', function (ServiceContainer $c) {
            return new Matcher\StringEndMatcher($c->get('formatter.presenter'));
        });
        $container->set('matchers.string_regex',
            function (ServiceContainer $c) {
                return new Matcher\StringRegexMatcher($c->get('formatter.presenter'));
            });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupNormalizer(ServiceContainer $container)
    {
        $container->setShared('path_normalizer', function () {
            return new FilePathNormalizer();
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupPresenter(ServiceContainer $container)
    {
        $container->setShared('formatter.presenter',
            function (ServiceContainer $c) {
                return new SpecFormatter\Presenter\TaggedPresenter($c->get('formatter.presenter.differ'));
            });
        $container->setShared('formatter.presenter.differ',
            function (ServiceContainer $c) {
                $differ = new SpecFormatter\Presenter\Differ\Differ();
                array_map([$differ, 'addEngine'],
                    $c->getByPrefix('formatter.presenter.differ.engines'));
                return $differ;
            });
        $container->set('formatter.presenter.differ.engines.string',
            function () {
                return new SpecFormatter\Presenter\Differ\StringEngine();
            });
        $container->set('formatter.presenter.differ.engines.array',
            function () {
                return new SpecFormatter\Presenter\Differ\ArrayEngine();
            });
        $container->set('formatter.presenter.differ.engines.object',
            function (ServiceContainer $c) {
                return new SpecFormatter\Presenter\Differ\ObjectEngine(new Exporter(),
                    $c->get('formatter.presenter.differ.engines.string'));
            });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupRerunner(ServiceContainer $container)
    {
        $container->setShared('process.rerunner',
            function (ServiceContainer $c) {
                return new ReRunner\OptionalReRunner($c->get('process.rerunner.platformspecific'),
                    $c->get('console.io'));
            });
        if ($container->isDefined('process.rerunner.platformspecific')) {
            return $this;
        }
        $container->setShared('process.rerunner.platformspecific',
            function (ServiceContainer $c) {
                return new ReRunner\CompositeReRunner($c->getByPrefix('process.rerunner.platformspecific'));
            });
        $container->setShared('process.rerunner.platformspecific.pcntl',
            function (ServiceContainer $c) {
                return new ReRunner\PcntlReRunner($c->get('process.phpexecutablefinder'));
            });
        $container->setShared('process.rerunner.platformspecific.passthru',
            function (ServiceContainer $c) {
                return new ReRunner\PassthruReRunner($c->get('process.phpexecutablefinder'));
            });
        $container->setShared('process.phpexecutablefinder', function () {
            return new PhpExecutableFinder();
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupResultConverter(ServiceContainer $container)
    {
        $container->setShared('console.result_converter', function () {
            return new ResultConverter();
        });
        return $this;
    }
    /**
     * @param ServiceContainer $container
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setupRunner(ServiceContainer $container)
    {
        $container->setShared('runner.suite', function (ServiceContainer $c) {
            return new Runner\SuiteRunner($c->get('event_dispatcher'),
                $c->get('runner.specification'));
        });
        $container->setShared('runner.specification',
            function (ServiceContainer $c) {
                return new Runner\SpecificationRunner($c->get('event_dispatcher'),
                    $c->get('runner.example'));
            });
        $container->setShared('runner.example', function (ServiceContainer $c) {
            $runner = new Runner\ExampleRunner($c->get('event_dispatcher'),
                $c->get('formatter.presenter'));
            array_map([$runner, 'registerMaintainer'],
                $c->getByPrefix('runner.maintainers'));
            return $runner;
        });
        $container->set('runner.maintainers.errors',
            function (ServiceContainer $c) {
                return new Runner\Maintainer\ErrorMaintainer($c->getParam('runner.maintainers.errors.level',
                    E_ALL ^ E_STRICT));
            });
        $container->set('runner.maintainers.collaborators',
            function (ServiceContainer $c) {
                return new Runner\Maintainer\CollaboratorsMaintainer($c->get('unwrapper'));
            });
        $container->set('runner.maintainers.let_letgo', function () {
            return new Runner\Maintainer\LetAndLetgoMaintainer();
        });
        $container->set('runner.maintainers.matchers',
            function (ServiceContainer $c) {
                $matchers = $c->getByPrefix('matchers');
                return new Runner\Maintainer\MatchersMaintainer($c->get('formatter.presenter'),
                    $matchers);
            });
        $container->set('runner.maintainers.subject',
            function (ServiceContainer $c) {
                return new Runner\Maintainer\SubjectMaintainer($c->get('formatter.presenter'),
                    $c->get('unwrapper'), $c->get('event_dispatcher'));
            });
        $container->setShared('unwrapper', function () {
            return new Wrapper\Unwrapper();
        });
        return $this;
    }
}
