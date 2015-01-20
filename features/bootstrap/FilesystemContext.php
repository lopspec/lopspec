<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use LopSpec\Matcher\MatchersProviderInterface;
use Matcher\FileExistsMatcher;
use Matcher\FileHasContentsMatcher;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Defines application features from the specific context.
 */
class FilesystemContext implements Context, MatchersProviderInterface
{
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }
    /**
     * @return array
     */
    public function getMatchers()
    {
        return [
            new FileExistsMatcher(),
            new FileHasContentsMatcher()
        ];
    }
    /**
     * @beforeScenario
     */
    public function prepWorkingDirectory()
    {
        $this->workingDirectory = tempnam(sys_get_temp_dir(), 'lopspec-behat');
        $this->filesystem->remove($this->workingDirectory);
        $this->filesystem->mkdir($this->workingDirectory);
        chdir($this->workingDirectory);
    }
    /**
     * @afterScenario
     */
    public function removeWorkingDirectory()
    {
        $this->filesystem->remove($this->workingDirectory);
    }
    /**
     * @Given the class file :file contains:
     * @Given the spec file :file contains:
     */
    public function theClassOrSpecFileContains($file, PyStringNode $contents)
    {
        $this->theFileContains($file, $contents);
        require_once($file);
    }
    /**
     * @Given the config file contains:
     */
    public function theConfigFileContains(PyStringNode $contents)
    {
        $this->theFileContains('lopspec.yml', $contents);
    }
    /**
     * @Given the bootstrap file :file contains:
     */
    public function theFileContains($file, PyStringNode $contents)
    {
        $this->filesystem->dumpFile($file, (string)$contents);
    }
    /**
     * @Then the class in :file should contain:
     * @Then a new class/spec should be generated in the :file:
     */
    public function theFileShouldContain($file, PyStringNode $contents)
    {
        expect($file)->toExist();
        expect($file)->toHaveContents($contents);
    }
    /**
     * @Given there is no file :file
     */
    public function thereIsNoFile($file)
    {
        expect($file)->toNotExist();
        expect(file_exists($file))->toBe(false);
    }
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $workingDirectory;
}
