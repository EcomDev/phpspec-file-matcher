<?php

namespace EcomDev\PHPSpec\FileMatcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;

/**
 * Check matcher abstraction
 */
class CheckMatcher implements Matcher
{
    /**
     * @var MatchLexer
     */
    private $lexer;

    /**
     * @var CheckInterface
     */
    private $positiveCheck;

    /**
     * @var CheckInterface
     */
    private $negativeCheck;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $negativeError;

    /**
     * @var string
     */
    private $positiveError;

    /**
     * @param MatchLexer $lexer
     * @param CheckInterface $positiveCheck
     * @param CheckInterface $negativeCheck
     * @param int $priority
     * @param string $negativeError
     * @param string $positiveError
     */
    public function __construct(
        MatchLexer $lexer,
        CheckInterface $positiveCheck,
        CheckInterface $negativeCheck,
        $priority,
        $positiveError,
        $negativeError
    ) {
        $this->lexer = $lexer;
        $this->positiveCheck = $positiveCheck;
        $this->negativeCheck = $negativeCheck;
        $this->priority = $priority;
        $this->negativeError = $negativeError;
        $this->positiveError = $positiveError;
    }


    /**
     * Uses lexer to check if name of match is supported
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @return bool
     */
    public function supports($name, $subject, array $arguments)
    {
        return $this->lexer->supports($name, $arguments);
    }


    /**
     * Uses positive check instance for validating match
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     * @return bool
     * @throws FailureException
     */
    public function positiveMatch($name, $subject, array $arguments)
    {
        return $this->validateCheckerConditionsAndThrowFailureExceptionOnFailedCheck(
            $this->positiveCheck,
            $arguments,
            $this->positiveError
        );
    }

    /**
     * Uses negative check instance for validating match
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     * @return bool
     * @throws FailureException
     */
    public function negativeMatch($name, $subject, array $arguments)
    {
        return $this->validateCheckerConditionsAndThrowFailureExceptionOnFailedCheck(
            $this->negativeCheck,
            $arguments,
            $this->negativeError
        );
    }

    /**
     * @param CheckInterface $checker
     * @param array $arguments
     * @param string $errorText
     * @return bool
     * @throws FailureException
     */
    private function validateCheckerConditionsAndThrowFailureExceptionOnFailedCheck(
        CheckInterface $checker,
        array $arguments,
        $errorText
    ) {
        $validatorData = $checker($arguments);

        if ($validatorData !== true) {
            if (is_array($validatorData)) {
                throw new FailureException(sprintf($errorText, ...$validatorData));
            }

            throw new FailureException($errorText);
        }

        return true;
    }

    /**
     * Returns matcher priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
