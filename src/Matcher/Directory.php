<?php

namespace EcomDev\PHPSpec\FileMatcher\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\MatcherInterface;

/**
 * Directory existence matcher
 */
class Directory extends AbstractMatcher implements MatcherInterface
{
    /**
     * Constructor for matcher
     */
    public function __construct()
    {
        parent::__construct(['be', 'have', 'create'], ['directory' => 1, 'directories' => true]);
    }

    /**
     * Checks existence of passed directory arguments
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @throws FailureException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        foreach ($arguments as $directory) {
            if (!is_dir($directory)) {
                throw new FailureException(sprintf('Directory "%s" does not exist', $directory));
            }
        }
    }

    /**
     * Checks not existence of passed directory arguments
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @throws FailureException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        foreach ($arguments as $directory) {
            if (is_dir($directory)) {
                throw new FailureException(sprintf('Directory "%s" exists', $directory));
            }
        }
    }
}
