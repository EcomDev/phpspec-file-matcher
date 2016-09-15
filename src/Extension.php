<?php

namespace EcomDev\PHPSpec\FileMatcher;

use PhpSpec\ServiceContainer;

/**
 * Directory existence check class
 */
class Extension implements \PhpSpec\Extension
{
    /**
     * Loads matchers into PHPSpec service container
     *
     * @param ServiceContainer $container
     * @param array $params
     */
    public function load(ServiceContainer $container, array $params)
    {
        $container->define(
            'ecomdev.matcher.file',
            function () {
                return $this->createFileMatcher();
            },
            ['matchers']
        );

        $container->define(
            'ecomdev.matcher.file_content',
            function () {
                return $this->createFileContentMatcher();
            },
            ['matchers']
        );

        $container->define(
            'ecomdev.matcher.directory',
            function () {
                return $this->createDirectoryMatcher();
            },
            ['matchers']
        );
    }

    /**
     * Return directory content matcher instance
     *
     * @return CheckMatcher
     */
    private function createDirectoryMatcher()
    {
        return $this->createCheckMatcher(
            ['be', 'have', 'create'],
            ['directory' => 1, 'directories'],
            new DirectoryCheck(true),
            new DirectoryCheck(false),
            30,
            'Directory "%s" does not exist',
            'Directory "%s" exists'
        );
    }

    /**
     * Returns file matcher instance
     *
     * @return CheckMatcher
     */
    private function createFileMatcher()
    {
        return $this->createCheckMatcher(
            ['be', 'have', 'create'],
            ['file' => 1, 'files'],
            new FileCheck(true),
            new FileCheck(false),
            10,
            'File "%s" does not exist',
            'File "%s" exists'
        );
    }

    /**
     * Return file content matcher instance
     *
     * @return CheckMatcher
     */
    private function createFileContentMatcher()
    {
        return $this->createCheckMatcher(
            ['be', 'have'],
            ['file_content' => 2],
            new FileContentCheck(true),
            new FileContentCheck(false),
            20,
            'File "%s" content does not match expected content "%s"',
            'File "%s" content matches un-expected content "%s"'
        );
    }

    /**
     * Create lexer instance
     *
     * @param $verbs
     * @param $nouns
     *
     * @return MatchLexer
     */
    private function createLexer($verbs, $nouns)
    {
        return new MatchLexer($verbs, $nouns);
    }

    /**
     * Create check matcher instance
     *
     * @param string[] $verbs
     * @param string[] $nouns
     * @param array $matcherArguments
     *
     * @return CheckMatcher
     */
    private function createCheckMatcher($verbs, $nouns, ...$matcherArguments)
    {
        return new CheckMatcher(
            $this->createLexer($verbs, $nouns),
            ...$matcherArguments
        );
    }
}
