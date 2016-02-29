<?php

namespace EcomDev\PHPSpec\FileMatcher\Matcher;

use PhpSpec\Matcher\MatcherInterface;

/**
 * Abstract matcher
 */
abstract class AbstractMatcher implements MatcherInterface
{
    /**
     * Resolved matcher list for supports() call
     *
     * @var string[]
     */
    private $matchers = [];

    /**
     * Priority for matcher
     *
     * @var int
     */
    private $priority;

    /**
     * Abstract matcher, allows to specify forms of match expressions
     *
     * @param string[] $matchPrefixes
     * @param int[]|bool[] $matchForms
     * @param int $priority
     */
    public function __construct(array $matchPrefixes, array $matchForms, $priority = 100)
    {
        foreach ($matchPrefixes as $prefix) {
            foreach ($matchForms as $form => $arguments) {
                $this->matchers[$prefix . ucfirst($form)] = $arguments;
            }
        }

        $this->priority = $priority;
    }

    /**
     * Preforms check of matcher availability based on specified arguments during object creation
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @return bool
     */
    public function supports($name, $subject, array $arguments)
    {
        if (!isset($this->matchers[$name])) {
            return false;
        }

        if (is_int($this->matchers[$name]) && count($arguments) !== $this->matchers[$name]) {
            return false;
        }

        if ($this->matchers[$name] === false && count($arguments) !== 0) {
            return false;
        }

        if ($this->matchers[$name] == true && count($arguments) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Returns matcher priority specified during construction
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
