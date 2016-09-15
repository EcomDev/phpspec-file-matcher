<?php

namespace EcomDev\PHPSpec\FileMatcher;

use PhpSpec\ServiceContainer;

class Extension implements \PhpSpec\Extension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->define('matchers.file', function () {
            return new CheckMatcher(
                new MatchLexer(['be', 'have', 'create'], ['file' => 1, 'files']),
                new FileCheck(true),
                new FileCheck(false),
                10,
                'File "%s" does not exist',
                'File "%s" exists'
            );
        });

        $container->define('matchers.file_content', function () {
            return new CheckMatcher(
                new MatchLexer(['be', 'have'], ['file_content' => 2]),
                new FileContentCheck(true),
                new FileContentCheck(false),
                20,
                'File "%s" content does not match expected content "%s"',
                'File "%s" content matches un-expected content "%s"'
            );
        });

        $container->define('matchers.directory', function () {
            return new CheckMatcher(
                new MatchLexer(['be', 'have', 'create'], ['directory' => 1, 'directories']),
                new DirectoryCheck(true),
                new DirectoryCheck(false),
                30,
                'Directory "%s" does not exist',
                'Directory "%s" exists'
            );
        });
    }

}
