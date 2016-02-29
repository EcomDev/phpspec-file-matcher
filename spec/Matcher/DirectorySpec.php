<?php

namespace spec\EcomDev\PHPSpec\FileMatcher\Matcher;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DirectorySpec extends ObjectBehavior
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
     * @dataProvider positiveDirectoryTestCases
     */
    function it_responds_to_directory_match_requests($matcher, $arguments)
    {
        $this->supports($matcher, null, $arguments)->shouldReturn(true);
    }

    function it_does_not_responds_to_not_directory_match_requests()
    {
        $this->supports('beFile', null, [''])->shouldReturn(false);
        $this->supports('contain', null, [''])->shouldReturn(false);
        $this->supports('beDirectory', null, [])->shouldReturn(false); // Must not take empty arguments
        $this->supports('beDirectory', null, ['', ''])->shouldReturn(false); // Must not take multiple arguments
                                                                             // for single matcher
    }

    /**
     * @dataProvider positiveDirectoryTestCases
     */
    function it_has_positive_match_on_existing_directories($matcher, $arguments)
    {
        $this->shouldNotThrow()->duringPositiveMatch($matcher, null, $this->wrapFilePath($arguments));
    }

    /**
     * @dataProvider negativeDirectoryTestCases
     */
    function it_does_not_have_positive_match_on_non_existing_directory($matcher, $arguments, $message)
    {
        $arguments = $this->wrapFilePath($arguments);
        $this->shouldThrow(
            new FailureException($message)
        )->duringPositiveMatch($matcher, null, $arguments);
    }

    /**
     * @dataProvider negativeDirectoryTestCases
     */
    function it_has_negative_match_on_not_existing_directories($matcher, $arguments)
    {
        $this->shouldNotThrow()->duringNegativeMatch($matcher, null, $this->wrapFilePath($arguments));
    }


    /**
     * @dataProvider positiveDirectoryTestCases
     */
    function it_does_not_have_negative_match_on_existing_directory($matcher, $arguments, $message)
    {
        $arguments = $this->wrapFilePath($arguments);
        $this->shouldThrow(
            new FailureException($message)
        )->duringNegativeMatch($matcher, null, $arguments);
    }


    public function positiveDirectoryTestCases()
    {
        return array_merge($this->positiveSingularTestCases(), $this->positivePluralTestCases());
    }

    public function negativeDirectoryTestCases()
    {
        return array_merge($this->negativeSingularTestCases(), $this->negativePluralTestCases());
    }


    public function positiveSingularTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['directory_1'], // Single directory
            'Directory "vfs://root/directory_1" exists' // Expected failure text for negative match
        ];

        return [
            ['beDirectory'] + $defaultMatch, // beDirectory with one argument
            ['haveDirectory'] + $defaultMatch, // haveDirectory with multiple arguments
            ['createDirectory'] + $defaultMatch, // createDirectory with one argument
        ];
    }


    public function positivePluralTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['directory_1', 'directory_2'], // Single directory
            'Directory "vfs://root/directory_1" exists' // Expected failure text for negative match
        ];

        return [
            ['beDirectories'] + $defaultMatch, // beDirectory with one argument
            ['haveDirectories'] + $defaultMatch, // haveDirectory with multiple arguments
            ['createDirectories'] + $defaultMatch, // createDirectory with one argument
        ];
    }

    public function negativeSingularTestCases()
    {
        $fileMatch = [
            null, // No matcher
            ['file_1'], // Single file
            'Directory "vfs://root/file_1" does not exist' // Expected failure text for positive match
        ];

        $directoryMatch = [
            null, // No matcher
            ['directory_4', 'directory_3'], // Single directory
            'Directory "vfs://root/directory_4" does not exist' // Expected failure text for positive match
        ];

        return [
            ['beDirectory'] + $fileMatch, // file is not a directory
            ['beDirectory'] + $directoryMatch, // directory_4 does not exists
            ['haveDirectory'] + $fileMatch, // file is not a directory
            ['haveDirectory'] + $directoryMatch, // directory_4 does not exists
            ['createDirectory'] + $fileMatch, // createDirectory with one argument
            ['createDirectory'] + $directoryMatch, // createDirectory with one argument
            ['haveDirectory'] + $fileMatch, // file is not a directory
            ['haveDirectory'] + $directoryMatch, // directory_4 does not exists
        ];
    }

    public function negativePluralTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['directory_4', 'directory_3', 'directory_5'], // Multiple directory
            'Directory "vfs://root/directory_4" does not exist' // Expected failure text for positive match
        ];

        return [
            ['beDirectories'] + $defaultMatch, // one of the argument does not exists
            ['haveDirectories'] + $defaultMatch, // one of the argument does not exists
            ['createDirectories'] + $defaultMatch, // one of the argument does not exists
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
