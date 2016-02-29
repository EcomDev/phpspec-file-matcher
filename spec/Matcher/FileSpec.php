<?php

namespace spec\EcomDev\PHPSpec\FileMatcher\Matcher;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    /**
     * Root VFS directory
     *
     * @var vfsStreamDirectory
     */
    private $vfs;

    function let()
    {
        $this->vfs = vfsStream::setup('root', null, [
            'directory_1' => [],
            'directory_2' => [],
            'file_1' => 'text',
            'file_2' => 'text'
        ]);
    }

    function it_extends_abstract_matcher()
    {
        $this->shouldImplement('EcomDev\PHPSpec\FileMatcher\Matcher\AbstractMatcher');
    }

    /**
     * @dataProvider positiveFileTestCases
     */
    function it_responds_to_file_match_requests($matcher, $arguments)
    {
        $this->supports($matcher, null, $arguments)->shouldReturn(true);
    }

    function it_does_not_responds_to_not_file_match_requests()
    {
        $this->supports('beDirectory', null, [''])->shouldReturn(false);
        $this->supports('contain', null, [''])->shouldReturn(false);
        $this->supports('beFile', null, [])->shouldReturn(false); // Must not take empty arguments
        $this->supports('beFile', null, ['', ''])->shouldReturn(false); // Must not take multiple arguments
                                                                        // for single matcher
    }

    /**
     * @dataProvider positiveFileTestCases
     */
    function it_has_positive_match_on_existing_files($matcher, $arguments)
    {
        $this->shouldNotThrow()->duringPositiveMatch($matcher, null, $this->wrapFilePath($arguments));
    }

    /**
     * @dataProvider negativeFileTestCases
     */
    function it_does_not_have_positive_match_on_non_existing_files($matcher, $arguments, $message)
    {
        $arguments = $this->wrapFilePath($arguments);
        $this->shouldThrow(
            new FailureException($message)
        )->duringPositiveMatch($matcher, null, $arguments);
    }

    /**
     * @dataProvider negativeFileTestCases
     */
    function it_has_negative_match_on_not_existing_files($matcher, $arguments)
    {
        $this->shouldNotThrow()->duringNegativeMatch($matcher, null, $this->wrapFilePath($arguments));
    }


    /**
     * @dataProvider positiveFileTestCases
     */
    function it_does_not_have_negative_match_on_existing_files($matcher, $arguments, $message)
    {
        $arguments = $this->wrapFilePath($arguments);
        $this->shouldThrow(
            new FailureException($message)
        )->duringNegativeMatch($matcher, null, $arguments);
    }


    public function positiveFileTestCases()
    {
        return array_merge($this->positiveSingularTestCases(), $this->positivePluralTestCases());
    }

    public function negativeFileTestCases()
    {
        return array_merge($this->negativeSingularTestCases(), $this->negativePluralTestCases());
    }


    public function positiveSingularTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['file_1'], // Single file
            'File "vfs://root/file_1" exists' // Expected failure text for negative match
        ];

        return [
            ['beFile'] + $defaultMatch, // beDirectory with one argument
            ['haveFile'] + $defaultMatch, // haveDirectory with multiple arguments
            ['createFile'] + $defaultMatch, // createDirectory with one argument
        ];
    }


    public function positivePluralTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['file_1', 'file_2'], // Single directory
            'File "vfs://root/file_1" exists' // Expected failure text for negative match
        ];

        return [
            ['beFiles'] + $defaultMatch, // beDirectory with one argument
            ['haveFiles'] + $defaultMatch, // haveDirectory with multiple arguments
            ['createFiles'] + $defaultMatch, // createDirectory with one argument
        ];
    }

    public function negativeSingularTestCases()
    {
        $fileMatch = [
            null, // No matcher
            ['file_3'], // Single file
            'File "vfs://root/file_3" does not exist' // Expected failure text for positive match
        ];

        $directoryMatch = [
            null, // No matcher
            ['directory_1'], // Single directory
            'File "vfs://root/directory_1" does not exist' // Expected failure text for positive match
        ];

        return [
            ['beFile'] + $fileMatch, // file_3 does not exists
            ['beFile'] + $directoryMatch, // directory is not a file
            ['haveFile'] + $fileMatch, // file_3 does not exists
            ['haveFile'] + $directoryMatch, // directory is not a file
            ['createFile'] + $fileMatch, // file_3 does not exists
            ['createFile'] + $directoryMatch, // directory is not a file
        ];
    }

    public function negativePluralTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['file_4', 'file_3', 'file_5'], // Multiple files
            'File "vfs://root/file_4" does not exist' // Expected failure text for positive match
        ];

        return [
            ['beFiles'] + $defaultMatch, // one of the argument does not exists
            ['haveFiles'] + $defaultMatch, // one of the argument does not exists
            ['createFiles'] + $defaultMatch, // one of the argument does not exists
        ];
    }

    private function wrapFilePath($paths)
    {
        return array_map(
            function ($file) {
                return $this->vfs->url() . '/' . $file;
            },
            $paths
        );
    }
}
