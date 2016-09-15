<?php

namespace spec\EcomDev\PHPSpec\FileMatcher;

use EcomDev\PHPSpec\FileMatcher\MatchLexer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MatchLexerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['be', 'have'], ['something']);
    }

    function it_returns_true_if_first_verb_is_specified()
    {
        $this->supports('haveSomething', [])->shouldReturn(true);
    }

    function it_returns_true_if_second_verb_is_specified()
    {
        $this->supports('beSomething', [])->shouldReturn(true);
    }

    function it_returns_false_if_verb_is_unknown()
    {
        $this->supports('isSomething', [])->shouldReturn(false);
    }

    function it_returns_false_if_noun_is_not_registered()
    {
        $this->supports('beAnother', [])->shouldReturn(false);
    }

    function it_returns_false_if_no_verbs_and_nouns_are_sepcified()
    {
        $this->beConstructedWith([], []);
        $this->supports('beSomething', [])->shouldReturn(false);
    }

    function it_returns_true_if_number_of_arguments_should_be_zero_and_zero_arguments_are_passed()
    {
        $this->beConstructedWith(['be'], ['something' => MatchLexer::NO_ARGUMENTS]);

        $this->supports('beSomething', [])->shouldReturn(true);
    }

    function it_returns_false_if_number_of_arguments_should_be_zero_but_at_least_one_is_passed()
    {
        $this->beConstructedWith(['be'], ['something' => MatchLexer::NO_ARGUMENTS]);

        $this->supports('beSomething', [1])->shouldReturn(false);
        $this->supports('beSomething', [1, 2])->shouldReturn(false);
    }

    function it_returns_true_if_number_of_arguments_specified_and_the_same_number_of_arguments_passed()
    {
        $this->beConstructedWith(['be'], ['something' => 2]);
        $this->supports('beSomething', [1, 2])->shouldReturn(true);
    }

    function it_returns_false_if_number_of_arguments_specified_is_not_the_same_number_of_arguments_passed()
    {
        $this->beConstructedWith(['be'], ['something' => 2]);
        $this->supports('beSomething', [1])->shouldReturn(false);
        $this->supports('beSomething', [1, 2, 3])->shouldReturn(false);
        $this->supports('beSomething', [])->shouldReturn(false);
    }

    function it_returns_true_with_noun_containing_underscore()
    {
        $this->beConstructedWith(['be'], ['something_else']);
        $this->supports('beSomethingElse', [1])->shouldReturn(true);
    }

}
