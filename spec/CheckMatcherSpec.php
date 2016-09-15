<?php

namespace spec\EcomDev\PHPSpec\FileMatcher;

use EcomDev\PHPSpec\FileMatcher\CheckInterface;
use EcomDev\PHPSpec\FileMatcher\MatchLexer;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckMatcherSpec extends ObjectBehavior
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

    function let(MatchLexer $lexer, CheckInterface $positiveCheck, CheckInterface $negativeCheck)
    {
        $this->lexer = $lexer;
        $this->positiveCheck = $positiveCheck;
        $this->negativeCheck = $negativeCheck;
        $this->beConstructedWith(
            $this->lexer,
            $this->positiveCheck,
            $this->negativeCheck,
            30,
            'Positive error message %s, %s',
            'Negative error message %s, %s'
        );
    }

    function it_implements_matcher_interface_for_phpspec()
    {
        $this->shouldImplement('PhpSpec\Matcher\Matcher');
    }

    function it_uses_lexer_for_checking_supports_of_a_matcher()
    {
        $this->lexer->supports('beSomething', [''])->willReturn(true)->shouldBeCalled();
        $this->lexer->supports('beSomething', ['', ''])->willReturn(true)->shouldBeCalled();
        $this->lexer->supports('beSomething', [])->willReturn(false)->shouldBeCalled();

        $this->supports('beSomething', null, [''])->shouldReturn(true);
        $this->supports('beSomething', null, ['', ''])->shouldReturn(true);
        $this->supports('beSomething', null, [])->shouldReturn(false);
    }

    function it_uses_positive_check_model_for_positive_match()
    {
        $this->positiveCheck->__invoke(['argument1', 'argument2'])->willReturn(true)->shouldBeCalled();

        $this->positiveMatch('beSomething', null, ['argument1', 'argument2'])->shouldReturn(true);
    }

    function it_uses_negative_check_model_for_negative_match()
    {
        $this->negativeCheck->__invoke(['argument1', 'argument2'])->willReturn(true)->shouldBeCalled();

        $this->negativeMatch('beSomething', null, ['argument1', 'argument2'])->shouldReturn(true);
    }

    function it_uses_positive_check_model_for_positive_match_that_fails()
    {
        $this->positiveCheck->__invoke(['argument1', 'argument2'])
            ->willReturn(['argument1', 'argument2'])
            ->shouldBeCalled();

        $this->shouldThrow(new FailureException('Positive error message argument1, argument2'))
            ->duringPositiveMatch('beSomething', null, ['argument1', 'argument2']);
    }

    function it_uses_negative_check_model_for_positive_match_that_fails()
    {
        $this->negativeCheck->__invoke(['argument1', 'argument2'])
            ->willReturn(['argument1', 'argument2'])
            ->shouldBeCalled();
        

        $this->shouldThrow(new FailureException('Negative error message argument1, argument2'))
            ->duringNegativeMatch('beSomething', null, ['argument1', 'argument2']);
    }

    function it_uses_positive_check_model_for_positive_match_that_fails_and_returns_no_arguments()
    {
        $this->positiveCheck->__invoke(['argument1', 'argument2'])
            ->willReturn(false)
            ->shouldBeCalled();

        $this->shouldThrow(new FailureException('Positive error message %s, %s'))
            ->duringPositiveMatch('beSomething', null, ['argument1', 'argument2']);
    }


    function it_uses_negative_check_model_for_positive_match_that_fails_and_returns_no_arguments()
    {
        $this->negativeCheck->__invoke(['argument1', 'argument2'])
            ->willReturn(false)
            ->shouldBeCalled();

        $this->shouldThrow(new FailureException('Negative error message %s, %s'))
            ->duringNegativeMatch('beSomething', null, ['argument1', 'argument2']);
    }


    function it_returns_priority_specified_in_constructor()
    {
        $this->getPriority()->shouldReturn(30);
    }
}
