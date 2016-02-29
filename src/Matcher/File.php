<?php

namespace EcomDev\PHPSpec\FileMatcher\Matcher;

use PhpSpec\Exception\Example\FailureException;

/**
 * File existance check matcher
 */
class File extends AbstractMatcher
{
    /**
     * Initializes available matchers
     */
    public function __construct()
    {
        parent::__construct(['be', 'have', 'create'], ['file' => 1, 'files' => true]);
    }

    /**
     * Checks existence of passed file arguments
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @throws FailureException on failure
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        foreach ($arguments as $file) {
            if (!is_file($file)) {
                throw new FailureException(sprintf('File "%s" does not exist', $file));
            }
        }
    }

    /**
     * Checks not existence of passed file arguments
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @throws FailureException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        foreach ($arguments as $file) {
            if (is_file($file)) {
                throw new FailureException(sprintf('File "%s" exists', $file));
            }
        }
    }
}
