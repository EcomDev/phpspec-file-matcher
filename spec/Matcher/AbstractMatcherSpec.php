<?php

namespace spec\EcomDev\PHPSpec\FileMatcher\Matcher;

use EcomDev\PHPSpec\FileMatcher\Matcher\AbstractMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractMatcherSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\EcomDev\PHPSpec\FileMatcher\Matcher\AbstractMatcherDummy');
        $this->beConstructedWith([], []);
    }

    function it_implements_matcher_interface()
    {
        $this->shouldImplement('PhpSpec\Matcher\MatcherInterface');
    }

    function it_supports_only_specified_prefixes_with_any_arguments_but_not_empty_list()
    {
        $this->beConstructedWith(['be', 'have'], ['something' => true]);
        $this->supports('beSomething', null, [''])->shouldReturn(true); // single argument
        $this->supports('beSomething', null, ['', ''])->shouldReturn(true); // multiple argument list
        $this->supports('haveSomething', null, [''])->shouldReturn(true); // supported another prefix
        $this->supports('beSomething', null, [])->shouldReturn(false); // empty argument list
        $this->supports('beAnything', null, [''])->shouldReturn(false); // Unknown form
        $this->supports('isSomething', null, [''])->shouldReturn(false); // Unknown prefix
    }

    function it_supports_empty_argument_list_for_false_form_matcher()
    {
        $this->beConstructedWith(['is'], ['something' => false]);
        $this->supports('isSomething', null, [])->shouldReturn(true); // no arguments
        $this->supports('isSomething', null, [''])->shouldReturn(false); // no arguments are allowed
    }

    function it_supports_strict_form_matcher()
    {
        $this->beConstructedWith(['is'], ['something' => 2]);
        $this->supports('isSomething', null, ['', ''])->shouldReturn(true); // supported 2 arguments
        $this->supports('isSomething', null, [''])->shouldReturn(false); // single argument not supported
        $this->supports('isSomething', null, [])->shouldReturn(false); // no arguments not supported
    }

    function it_supports_multiform_matcher()
    {
        $this->beConstructedWith(['is'], ['aThing' => 2, 'things' => true, 'stuff' => false]);
        $this->supports('isAThing', null, ['', ''])->shouldReturn(true); // supported count match
        $this->supports('isAThing', null, [''])->shouldReturn(false); // less arguments than expected
        $this->supports('isThings', null, ['', '', ''])->shouldReturn(true); // supported multi arguments
        $this->supports('isThings', null, [])->shouldReturn(false); // not arguments not supported
        $this->supports('isStuff', null, [])->shouldReturn(true); // supported call without arguments
    }

    function it_returns_default_priority()
    {
        $this->getPriority()->shouldReturn(100);
    }

    function it_returns_overriden_priority()
    {
        $this->beConstructedWith([], [], 20);
        $this->getPriority()->shouldReturn(20);
    }
}

class AbstractMatcherDummy extends AbstractMatcher
{
    public function positiveMatch($name, $subject, array $arguments)
    {
        // Dummy method, does nothing
    }


    public function negativeMatch($name, $subject, array $arguments)
    {
        // Dummy method, does nothing
    }
}
