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

use LopSpec\Config\OptionsConfig;
use LopSpec\IO\IOInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class IO deals with input and output from command line interaction
 */
class IO implements IOInterface
{
    const COL_DEFAULT_WIDTH = 60;
    const COL_MAX_WIDTH = 80;
    const COL_MIN_WIDTH = 40;
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param DialogHelper    $dialogHelper
     * @param OptionsConfig   $config
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        DialogHelper $dialogHelper,
        OptionsConfig $config
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->dialogHelper = $dialogHelper;
        $this->config = $config;
    }
    /**
     * @param string      $question
     * @param string|null $default
     *
     * @return string
     */
    public function ask($question, $default = null)
    {
        return $this->dialogHelper->ask($this->output, $question, $default);
    }
    /**
     * @param string      $question
     * @param callable    $validator
     * @param int|false   $attempts
     * @param string|null $default
     *
     * @return string
     */
    public function askAndValidate(
        $question,
        $validator,
        $attempts = false,
        $default = null
    ) {
        return $this->dialogHelper->askAndValidate($this->output, $question,
            $validator, $attempts, $default);
    }
    /**
     * @param string $question
     * @param bool   $default
     *
     * @return Boolean
     */
    public function askConfirmation($question, $default = true)
    {
        $lines = [];
        $lines[] = '<question>' . str_repeat(' ', $this->getBlockWidth())
                   . "</question>";
        foreach (explode("\n",
            wordwrap($question, $this->getBlockWidth() - 4, "\n", true)) as
                 $line) {
            $lines[]
                = '<question>  ' . str_pad($line, $this->getBlockWidth() - 2)
                  . '</question>';
        }
        $lines[] = '<question>' . str_repeat(' ', $this->getBlockWidth() - 8)
                   . '</question> <value>' . ($default ? '[Y/n]' : '[y/N]')
                   . '</value> ';

        return $this->dialogHelper->askConfirmation($this->output,
            implode("\n", $lines) . "\n", $default);
    }
    /**
     * @return null|string
     */
    public function cutTemp()
    {
        if (false === $this->hasTempString) {
            return;
        }
        $message = $this->lastMessage;
        $this->write('');

        return $message;
    }
    /**
     *
     */
    public function freezeTemp()
    {
        $this->write($this->lastMessage);
    }
    /**
     * @return integer
     */
    public function getBlockWidth()
    {
        $width = self::COL_DEFAULT_WIDTH;
        if ($this->consoleWidth
            && ($this->consoleWidth - 10) > self::COL_MIN_WIDTH
        ) {
            $width = $this->consoleWidth - 10;
        }
        if ($width > self::COL_MAX_WIDTH) {
            $width = self::COL_MAX_WIDTH;
        }

        return $width;
    }
    public function getBootstrapPath()
    {
        if ($path = $this->input->getOption('bootstrap')) {
            return $path;
        }
        if ($path = $this->config->getBootstrapPath()) {
            return $path;
        }

        return false;
    }
    /**
     * @return string
     */
    public function getLastWrittenMessage()
    {
        return $this->lastMessage;
    }
    /**
     * @return bool
     */
    public function isCodeGenerationEnabled()
    {
        if (!$this->isInteractive()) {
            return false;
        }

        return $this->config->isCodeGenerationEnabled()
            && !$this->input->getOption('no-code-generation');
    }
    /**
     * @return bool
     */
    public function isDecorated()
    {
        return $this->output->isDecorated();
    }
    public function isFakingEnabled()
    {
        return $this->input->getOption('fake')
               || $this->config->isFakingEnabled();
    }
    /**
     * @return bool
     */
    public function isInteractive()
    {
        return $this->input->isInteractive();
    }
    public function isRerunEnabled()
    {
        return !$this->input->getOption('no-rerun')
               && $this->config->isReRunEnabled();
    }
    /**
     * @return bool
     */
    public function isStopOnFailureEnabled()
    {
        return $this->config->isStopOnFailureEnabled()
               || $this->input->getOption('stop-on-failure');
    }
    /**
     * @return bool
     */
    public function isVerbose()
    {
        return OutputInterface::VERBOSITY_VERBOSE
               <= $this->output->getVerbosity();
    }
    /**
     * @param string       $message
     * @param integer|null $indent
     * @param bool         $newline
     */
    public function overwrite($message, $indent = null, $newline = false)
    {
        if (null !== $indent) {
            $message = $this->indentText($message, $indent);
        }
        if ($message === $this->lastMessage) {
            return;
        }
        $commonPrefix = $this->getCommonPrefix($message, $this->lastMessage);
        $newSuffix = substr($message, strlen($commonPrefix));
        $oldSuffix = substr($this->lastMessage, strlen($commonPrefix));
        $overwriteLength = strlen(strip_tags($oldSuffix));
        $this->write(str_repeat("\x08", $overwriteLength));
        $this->write($newSuffix);
        $fill = $overwriteLength - strlen(strip_tags($newSuffix));
        if ($fill > 0) {
            $this->write(str_repeat(' ', $fill));
            $this->write(str_repeat("\x08", $fill));
        }
        if ($newline) {
            $this->writeln();
        }
        $this->lastMessage = $message . ($newline ? "\n" : '');
    }
    /**
     * @param string       $message
     * @param integer|null $indent
     */
    public function overwriteln($message = '', $indent = null)
    {
        $this->overwrite($message, $indent, true);
    }
    /**
     * @param integer $width
     */
    public function setConsoleWidth($width)
    {
        $this->consoleWidth = $width;
    }
    /**
     * @param string       $message
     * @param integer|null $indent
     * @param bool         $newline
     */
    public function write($message, $indent = null, $newline = false)
    {
        if ($this->hasTempString) {
            $this->hasTempString = false;
            $this->overwrite($message, $indent, $newline);

            return;
        }

        if (null !== $indent) {
            $message = $this->indentText($message, $indent);
        }

        $this->output->write($message, $newline);
        $this->lastMessage = $message.($newline ? "\n" : '');
    }
    /**
     * @param string       $message
     * @param integer|null $indent
     */
    public function writeTemp($message, $indent = null)
    {
        $this->write($message, $indent);
        $this->hasTempString = true;
    }
    /**
     * @param string       $message
     * @param integer|null $indent
     */
    public function writeln($message = '', $indent = null)
    {
        $this->write($message, $indent, true);
    }
    private function getCommonPrefix($stringA, $stringB)
    {
        for ($i = 0; $i<min(strlen($stringA), strlen($stringB)); $i++) {
            if ($stringA[$i] != $stringB[$i]) {
                break;
            }
        }

        $common = substr($stringA, 0, $i);

        if (preg_match('/(^.*)<[a-z-]*>?[^<]*$/', $common, $matches)) {
            $common = $matches[1];
        }

        return $common;
    }
    /**
     * @param string  $text
     * @param integer $indent
     *
     * @return string
     */
    private function indentText($text, $indent)
    {
        return implode("\n", array_map(
            function ($line) use ($indent) {
                return str_repeat(' ', $indent).$line;
            },
            explode("\n", $text)
        ));
    }
    /**
     * @type OptionsConfig
     */
    private $config;
    /**
     * @type integer
     */
    private $consoleWidth;
    /**
     * @type \Symfony\Component\Console\Helper\DialogHelper
     */
    private $dialogHelper;
    /**
     * @type bool
     */
    private $hasTempString = false;
    /**
     * @type \Symfony\Component\Console\Input\InputInterface
     */
    private $input;
    /**
     * @type string
     */
    private $lastMessage;
    /**
     * @type \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;
}
