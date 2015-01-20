<?php
use Behat\Behat\Context\Context;
use Fake\DialogHelper;
use Fake\ReRunner;
use LopSpec\Console\Application;
use LopSpec\Matcher\MatchersProviderInterface;
use Matcher\ApplicationOutputMatcher;
use Matcher\ExitStatusMatcher;
use Matcher\ValidJUnitXmlMatcher;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Defines application features from the specific context.
 */
class ApplicationContext implements Context, MatchersProviderInterface
{
    /**
     * @Then :number example(s) should have been skipped
     */
    public function exampleShouldHaveBeenSkipped($number)
    {
        expect($this->tester)->toHaveOutput("($number skipped)");
    }
    /**
     * @Then :number example(s) should have been run
     */
    public function examplesShouldHaveBeenRun($number)
    {
        expect($this->tester)->toHaveOutput("$number examples");
    }
    /**
     * Custom matchers
     *
     * @return array
     */
    public function getMatchers()
    {
        return [
            new ApplicationOutputMatcher(),
            new ValidJUnitXmlMatcher()
        ];
    }
    /**
     * @Given I have started describing the :class class
     * @Given I start describing the :class class
     */
    public function iDescribeTheClass($class)
    {
        $arguments = [
            'command' => 'describe',
            'class' => $class
        ];
        expect($this->tester->run($arguments,
            ['interactive' => false]))->toBe(0);
    }
    /**
     * @When I run lopspec (non interactively)
     * @When I run lopspec using the :formatter format
     * @When I run lopspec with the :option option
     * @When /I run lopspec with option (?P<option>.*)/
     * @When /I run lopspec (?P<interactive>interactively)$/
     * @When /I run lopspec (?P<interactive>interactively) with the (?P<option>.*) option/
     */
    public function iRunLopSpec(
        $formatter = null,
        $option = null,
        $interactive = null
    )
    {
        $arguments = [
            'command' => 'run'
        ];

        if ($formatter) {
            $arguments['--format'] = $formatter;
        }

        $this->addOptionToArguments($option, $arguments);
        $this->lastExitCode = $this->tester->run($arguments,
            ['interactive' => (bool)$interactive]);
    }
    /**
     * @When I run lopspec and answer :answer when asked if I want to generate the code
     * @When I run lopspec with the option :option and (I) answer :answer when asked if I want to generate the code
     */
    public function iRunLopSpecAndAnswerWhenAskedIfIWantToGenerateTheCode(
        $answer,
        $option = null
    )
    {
        $arguments = [
            'command' => 'run'
        ];

        $this->addOptionToArguments($option, $arguments);

        $this->dialogHelper->setAnswer($answer=='y');
        $this->lastExitCode = $this->tester->run($arguments,
            ['interactive' => true]);
    }
    /**
     * @Then I should be prompted for code generation
     */
    public function iShouldBePromptedForCodeGeneration()
    {
        expect($this->dialogHelper)->toHaveBeenAsked();
    }
    /**
     * @Then I should not be prompted for code generation
     */
    public function iShouldNotBePromptedForCodeGeneration()
    {
        expect($this->dialogHelper)->toNotHaveBeenAsked();
    }
    /**
     * @Then I should see :output
     * @Then I should see:
     */
    public function iShouldSee($output)
    {
        expect($this->tester)->toHaveOutput((string)$output);
    }
    /**
     * @Then I should see valid junit output
     */
    public function iShouldSeeValidJunitOutput()
    {
        expect($this->tester)->toHaveOutputValidJunitXml();
    }
    /**
     * @beforeScenario
     */
    public function setupApplication()
    {
        $this->application = new Application('2.1-dev');
        $this->application->setAutoExit(false);
        $this->tester = new ApplicationTester($this->application);
        $this->setupDialogHelper();
        $this->setupReRunner();
    }
    /**
     * @Then the exit code should be :code
     */
    public function theExitCodeShouldBe($code)
    {
        expect($this->lastExitCode)->toBeLike($code);
    }
    /**
     * @Then the suite should pass
     */
    public function theSuiteShouldPass()
    {
        expect($this->lastExitCode)->toBeLike(0);
    }
    /**
     * @Then the tests should be rerun
     */
    public function theTestsShouldBeRerun()
    {
        expect($this->reRunner)->toHaveBeenRerun();
    }
    /**
     * @Then the tests should not be rerun
     */
    public function theTestsShouldNotBeRerun()
    {
        expect($this->reRunner)->toNotHaveBeenRerun();
    }
    /**
     * @param string $option
     * @param array  $arguments
     */
    private function addOptionToArguments($option, array &$arguments)
    {
        if ($option) {
            if (preg_match('/(?P<option>[a-z-]+)=(?P<value>[a-z.]+)/', $option,
                $matches)) {
                $arguments[$matches['option']] = $matches['value'];
            } else {
                $arguments['--' . trim($option, '"')] = true;
            }
        }
    }
    private function setupDialogHelper()
    {
        $this->dialogHelper = new DialogHelper();
        $helperSet = $this->application->getHelperSet();
        $helperSet->set($this->dialogHelper);
    }
    private function setupReRunner()
    {
        $this->reRunner = new ReRunner;
        $this->application->getContainer()
                          ->set('process.rerunner.platformspecific',
                              $this->reRunner);
    }
    /**
     * @var Application
     */
    private $application;
    /**
     * @var DialogHelper
     */
    private $dialogHelper;
    /**
     * @var integer
     */
    private $lastExitCode;
    /**
     * @var ReRunner
     */
    private $reRunner;
    /**
     * @var ApplicationTester
     */
    private $tester;
}
