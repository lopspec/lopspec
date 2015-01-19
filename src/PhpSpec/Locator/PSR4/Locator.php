<?php
/**
 * Contains Locator class.
 *
 * PHP version 5.4
 *
 * @copyright 2015 Michael Cummings
 * @author    Michael Cummings <mgcummings@yahoo.com>
 */
namespace PhpSpec\Locator\PSR4;

use FilePathNormalizer\FilePathNormalizer;
use FilePathNormalizer\FilePathNormalizerInterface;
use InvalidArgumentException;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\Locator\ResourceLocatorInterface;
use PhpSpec\Util\Filesystem;
use RuntimeException;

/**
 * Class Locator
 */
class Locator implements
    ResourceLocatorInterface
{
    /**
     * @param string                      $srcNamespace
     * @param string                      $specNamespacePrefix
     * @param string                      $srcPath
     * @param string                      $specPath
     * @param string                      $psr4Prefix
     * @param FilePathNormalizerInterface $fpn
     * @param Filesystem                  $filesystem
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        $srcNamespace,
        $specNamespacePrefix,
        $srcPath,
        $specPath,
        $psr4Prefix = null,
        FilePathNormalizerInterface $fpn = null,
        Filesystem $filesystem = null
    ) {
        $this->fpn = $fpn ?: new FilePathNormalizer();
        $this->filesystem = $filesystem ?: new Filesystem();
        $psr4Prefix = ltrim($this->fpn->normalizeFile((string)$psr4Prefix, false), '/');
        $specNamespacePrefix = $this->fpn->normalizePath($specNamespacePrefix, false);
        $this->srcPath = $this->fpn->normalizePath($srcPath, false);
        $this->specPath = $this->fpn->normalizePath($specPath, false);
        $srcNamespace = ltrim($this->fpn->normalizeFile($srcNamespace, false), '/');
        $srcNamespacePath = $srcNamespace;
        $len = strlen($psr4Prefix);
        if (0 !== $len) {
            if (0 !== strpos($srcNamespace, $psr4Prefix)) {
                $mess = 'PSR4 prefix does NOT match given class namespace.';
                throw new InvalidArgumentException($mess);
            }
            $srcNamespacePath = substr($srcNamespace, $len);
        }
        $this->specNamespace = (string)str_replace('/', '\\', $specNamespacePrefix . $srcNamespace);
        $this->srcNamespace = (string)str_replace('/', '\\', $srcNamespace);
        $this->fullSrcPath = $this->fpn->normalizePath($this->srcPath . $srcNamespacePath);
        $this->fullSpecPath = $this->fpn->normalizePath($this->specPath . $specNamespacePrefix . $srcNamespacePath);
        if ($srcPath > $this->fullSrcPath || !is_dir($this->fullSrcPath)) {
            throw new InvalidArgumentException(sprintf('Source code path should be existing filesystem path, but "%s" given.',
                $srcPath));
        }
        if ($specPath > $this->fullSpecPath || !is_dir($this->fullSpecPath)) {
            throw new InvalidArgumentException(sprintf('Specs code path should be existing filesystem path, but "%s" given.',
                $specPath));
        }
    }
    /**
     * @param string $className
     *
     * @return ResourceInterface|null
     */
    public function createResource($className)
    {
        $this->validatePsr0ClassName($className);
        $className = str_replace('/', '\\', $className);
        if (0 === strpos($className, $this->specNamespace)) {
            $relative = substr($className, strlen($this->specNamespace));
            return new PSR4Resource(explode('\\', $relative), $this);
        }
        if ('' === $this->srcNamespace || 0 === strpos($className, $this->srcNamespace)) {
            $relative = substr($className, strlen($this->srcNamespace));
            return new PSR4Resource(explode('\\', $relative), $this);
        }
        return null;
    }
    /**
     * @param string $query
     *
     * @return ResourceInterface[]
     */
    public function findResources($query)
    {
        $sep = DIRECTORY_SEPARATOR;
        $path = rtrim(realpath(str_replace(array('\\', '/'), $sep, $query)), $sep);
        if ('.php' !== substr($path, -4)) {
            $path .= $sep;
        }
        if ($path && 0 === strpos($path, $this->fullSrcPath)) {
            $path = $this->fullSpecPath . substr($path, strlen($this->fullSrcPath));
            $path = preg_replace('/\.php/', 'Spec.php', $path);
            return $this->findSpecResources($path);
        }
        if ($path && 0 === strpos($path, $this->srcPath)) {
            $path = $this->fullSpecPath . substr($path, strlen($this->srcPath));
            $path = preg_replace('/\.php/', 'Spec.php', $path);
            return $this->findSpecResources($path);
        }
        if ($path && 0 === strpos($path, $this->specPath)) {
            return $this->findSpecResources($path);
        }
        return array();
    }
    /**
     * @return ResourceInterface[]
     */
    public function getAllResources()
    {
        return $this->findSpecResources($this->fullSpecPath);
    }
    /**
     * @return integer
     */
    public function getPriority()
    {
        return 0;
    }
    /**
     * @return string
     */
    public function getSpecNamespace()
    {
        return $this->specNamespace;
    }
    /**
     * @param string $className
     *
     * @return boolean
     */
    public function supportsClass($className)
    {
        $className = str_replace('/', '\\', $className);
        return '' === $this->srcNamespace
               || 0 === strpos($className, $this->srcNamespace)
               || 0 === strpos($className, $this->specNamespace);
    }
    /**
     * @param string $query
     *
     * @return boolean
     */
    public function supportsQuery($query)
    {
        $query = $this->fpn->normalizeFile($query, false);
        if ('' === $query) {
            return false;
        }
        return 0 === strpos($query, $this->srcPath)
               || 0 === strpos($query, $this->specPath);
    }
    /**
     * @param string $path
     *
     * @return array
     */
    protected function findSpecResources($path)
    {
        if (!$this->filesystem->pathExists($path)) {
            return array();
        }
        if ('.php' === substr($path, -4)) {
            return array($this->createResourceFromSpecFile(realpath($path)));
        }
        $resources = array();
        foreach ($this->filesystem->findSpecFilesIn($path) as $file) {
            $resources[] = $this->createResourceFromSpecFile($file->getRealPath());
        }
        return $resources;
    }
    /**
     * @type Filesystem $filesystem
     */
    protected $filesystem;
    /**
     * @type FilePathNormalizerInterface $fpn
     */
    protected $fpn;
    /**
     * @type string $fullSpecPath
     */
    protected $fullSpecPath;
    /**
     * @type string $fullSrcPath
     */
    protected $fullSrcPath;
    /**
     * @type string $specNamespace
     */
    protected $specNamespace;
    /**
     * @type string $specPath
     */
    protected $specPath;
    /**
     * @type string $srcNamespace
     */
    protected $srcNamespace;
    /**
     * @type string $srcPath
     */
    protected $srcPath;
    /**
     * @param string $path
     *
     * @return PSR4Resource
     */
    private function createResourceFromSpecFile($path)
    {
        $className = $this->findSpecClassName($path);
        if (null === $className) {
            throw new RuntimeException('Spec file does not contains any class definition.');
        }
        // Remove spec namespace from the beginning of the className.
        $specNamespace = $this->getSpecNamespace() . '\\';
        if (0 !== strpos($className, $specNamespace)) {
            $mess = sprintf('Spec class `%s` must be in the base spec namespace `%s`.', $className,
                $this->getSpecNamespace());
            throw new RuntimeException($mess);
        }
        $className = substr($className, strlen($specNamespace));
        // cut "Spec" from the end
        $className = substr($className, 0, -4);
        // Create the resource
        return new PSR4Resource(explode('\\', $className), $this);
    }
    /**
     * @param string $path
     *
     * @return null|string
     */
    private function findSpecClassName($path)
    {
        // Find namespace and class name
        $namespace = '';
        $content = $this->filesystem->getFileContents($path);
        $tokens = token_get_all($content);
        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < $count; $j++) {
                    if ($tokens[$j][0] === T_STRING) {
                        $namespace .= $tokens[$j][1] . '\\';
                    } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                        break;
                    }
                }
            }
            if ($tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < $count; $j++) {
                    if ($tokens[$j] === '{') {
                        return $namespace . $tokens[$i + 2][1];
                    }
                }
            }
        }
        // No class found
        return null;
    }
    /**
     * @param string $className
     */
    private function validatePsr0ClassName($className)
    {
        $classNamePattern
            = '/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*[\/\\\\]?)*[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
        if (!preg_match($classNamePattern, $className)) {
            throw new InvalidArgumentException(sprintf('String "%s" is not a valid class name.', $className) . PHP_EOL
                                               . 'Please see reference document: '
                                               . 'https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md');
        }
    }
}
