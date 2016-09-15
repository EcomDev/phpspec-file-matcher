<?php

namespace EcomDev\PHPSpec\FileMatcher;

/**
 * Match lexer for matcher adapter for PHPSpec
 *
 */
class MatchLexer
{
    /**
     * Any argument matcher condition
     *
     * @var bool
     */
    const ANY_ARGUMENTS = true;

    /**
     * No arguments matcher condition
     *
     * @var bool
     */
    const NO_ARGUMENTS = false;

    /**
     * Associative array of lexer schemas for matcher
     *
     * @var bool[]|int[]
     */
    private $forms = [];

    /**
     * @param string[] $verbs
     * @param string[]|int[]|bool[] $nouns
     */
    public function __construct(array $verbs, array $nouns)
    {
        foreach ($verbs as $verb) {
            $this->buildMatcherListFromVerbAndListOfNouns($verb, $nouns);
        }
    }

    /**
     * Validates matcher condition based on specified rules
     *
     * @param string $phrase
     * @param mixed[] $arguments
     *
     * @return bool
     */
    public function supports($phrase, array $arguments)
    {
        if (!isset($this->forms[$phrase])) {
            return false;
        }

        $argumentMatch = $this->forms[$phrase];

        if ($this->validateNoArgumentsCondition($arguments, $argumentMatch)
            || $this->validateStrictArgumentCountCondition($arguments, $argumentMatch)) {
            return false;
        }

        return true;
    }

    private function buildMatcherListFromVerbAndListOfNouns($verb, $nouns)
    {
        foreach ($nouns as $noun => $argumentsCount) {
            $this->generateMatcher($verb, $noun, $argumentsCount);
        }
    }

    private function validateNoArgumentsCondition(array $arguments, $argumentMatch)
    {
        return $argumentMatch === self::NO_ARGUMENTS && $arguments;
    }

    private function validateStrictArgumentCountCondition(array $arguments, $argumentMatch)
    {
        return is_int($argumentMatch) && count($arguments) !== $argumentMatch;
    }

    private function generateMatcher($verb, $noun, $argumentsCount)
    {
        if (is_int($noun)) {
            $noun = $argumentsCount;
            $argumentsCount = self::ANY_ARGUMENTS;
        }

        $noun = str_replace(' ', '', ucwords(strtr($noun, '_', ' ')));
        $this->forms[$verb . $noun] = $argumentsCount;
    }
}
