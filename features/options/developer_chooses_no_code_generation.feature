Feature: Developer chooses no code generation
  As a Developer
  I want to set the no code generation setting option
  In order to specify how lopspec behaves when a class is not found

  @issue352
  Scenario: code-generation defaults to off
    Given the spec file "spec/NoCodeGeneration/SpecExample1/NewClassSpec.php" contains:
      """
      <?php

      namespace spec\NoCodeGeneration\SpecExample1;

      use LopSpec\ObjectBehavior;
      use Prophecy\Argument;

      class NewClassSpec extends ObjectBehavior
      {
          function it_is_initializable()
          {
              $this->shouldBeAnInstanceOf('NoCodeGeneration\NewClass');
          }
      }

      """
    When I run lopspec interactively
    Then I should be prompted for code generation

  @issue352
  Scenario: code-generation is specified in the config
    Given the config file contains:
      """
      code_generation: false
      """
    And the spec file "spec/NoCodeGeneration/SpecExample2/NewClassSpec.php" contains:
      """
      <?php

      namespace spec\NoCodeGeneration\SpecExample2;

      use LopSpec\ObjectBehavior;
      use Prophecy\Argument;

      class NewClassSpec extends ObjectBehavior
      {
          function it_is_initializable()
          {
              $this->shouldBeAnInstanceOf('NoCodeGeneration\NewClass');
          }
      }

      """
    When I run lopspec interactively
    Then I should not be prompted for code generation

  @issue352
  Scenario: code-generation on the command line takes priority
    Given the config file contains:
      """
      code_generation: true
      """
    And the spec file "spec/NoCodeGeneration/SpecExample3/NewClassSpec.php" contains:
      """
      <?php

      namespace spec\NoCodeGeneration\SpecExample3;

      use LopSpec\ObjectBehavior;
      use Prophecy\Argument;

      class NewClassSpec extends ObjectBehavior
      {
          function it_is_initializable()
          {
              $this->shouldBeAnInstanceOf('NoCodeGeneration\NewClass');
          }
      }

      """
    When I run lopspec interactively with the "no-code-generation" option
    Then I should not be prompted for code generation
