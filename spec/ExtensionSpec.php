<?php

namespace spec\EcomDev\PHPSpec\FileMatcher;

use EcomDev\PHPSpec\FileMatcher\CheckMatcher;
use EcomDev\PHPSpec\FileMatcher\DirectoryCheck;
use EcomDev\PHPSpec\FileMatcher\FileCheck;
use EcomDev\PHPSpec\FileMatcher\Matcher\FileContent;
use EcomDev\PHPSpec\FileMatcher\MatchLexer;
use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_implements_extension_interface()
    {
        $this->shouldImplement('PhpSpec\Extension');
    }

    function it_adds_existing_matchers(ServiceContainer $container)
    {
        $container->define(
            'ecomdev.matcher.file',
            Argument::that(function ($value) {
                $check = $value();
                return ($check instanceof CheckMatcher)
                    && $check->supports('haveFile', null, ['file'])
                    && $check->supports('beFile', null, ['file'])
                    && $check->supports('haveFiles', null, ['file', 'file2'])
                    && $check->supports('createFiles', null, ['file', 'file2'])
                    ;
            }),
            ['matchers']
        )->shouldBeCalled();

        $container->define(
            'ecomdev.matcher.file_content',
            Argument::that(function ($value) {
                $check = $value();
                return ($check instanceof CheckMatcher)
                    && $check->supports('haveFileContent', null, ['file', 'content']);
            }),
            ['matchers']
        )->shouldBeCalled();

        $container->define(
            'ecomdev.matcher.directory', 
            Argument::that(function ($value) {
                $check = $value();
                return ($check instanceof CheckMatcher)
                    && $check->supports('haveDirectory', null, ['directory'])
                    && $check->supports('beDirectory', null, ['directory'])
                    && $check->supports('haveDirectories', null, ['directory', 'directory2'])
                    && $check->supports('createDirectories', null, ['directory', 'directory2']);
                }
            ),
            ['matchers']
        )->shouldBeCalled();

        $this->load($container, []);
    }
}
