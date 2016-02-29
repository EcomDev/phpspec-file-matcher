<?php

namespace spec\EcomDev\PHPSpec\FileMatcher\Matcher;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileContentSpec extends ObjectBehavior
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
            'file_1' => 'text1',
            'file_2' => 'text2'
        ]);
    }

    function it_extends_abstract_matcher()
    {
        $this->shouldImplement('EcomDev\PHPSpec\FileMatcher\Matcher\AbstractMatcher');
    }

    /**
     * @dataProvider positiveTestCases
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
     * @dataProvider positiveTestCases
     */
    function it_has_positive_match_on_expected_content($matcher, $arguments)
    {
        $this->shouldNotThrow()->duringPositiveMatch($matcher, null, $this->wrapFilePath($arguments));
    }

    /**
     * @dataProvider negativeTestCases
     */
    function it_does_not_have_positive_match_on_not_expected_content($matcher, $arguments, $message)
    {
        $arguments = $this->wrapFilePath($arguments);
        $this->shouldThrow(
            new FailureException($message)
        )->duringPositiveMatch($matcher, null, $arguments);
    }

    /**
     * @dataProvider negativeTestCases
     */
    function it_has_negative_match_on_not_expected_content($matcher, $arguments)
    {
        $this->shouldNotThrow()->duringNegativeMatch($matcher, null, $this->wrapFilePath($arguments));
    }


    /**
     * @dataProvider positiveTestCases
     */
    function it_does_not_have_negative_match_on_expected_content($matcher, $arguments, $message)
    {
        $arguments = $this->wrapFilePath($arguments);
        $this->shouldThrow(
            new FailureException($message)
        )->duringNegativeMatch($matcher, null, $arguments);
    }


    public function positiveTestCases()
    {
        $defaultMatch = [
            null, // No matcher
            ['file_1', 'text1'], // Single file
            'File "vfs://root/file_1" content matches unexpected content "text1"'
        ];

        return [
            ['haveFileContent'] + $defaultMatch, // haveDirectory with multiple arguments
            ['createFileContent'] + $defaultMatch, // createDirectory with one argument
        ];
    }


    public function negativeTestCases()
    {
        $fileMatch = [
            null, // No matcher
            ['file_2', 'text1'], // Single file
            'File "vfs://root/file_2" content does not match expected content "text1"'
        ];


        return [
            ['haveFileContent'] + $fileMatch, // file content does not match
            ['createFileContent'] + $fileMatch, // file content does not match
        ];
    }

    private function wrapFilePath($paths)
    {
        if (isset($paths[0])) {
            $paths[0] = $this->vfs->url() . '/' . $paths[0];
        }

        return $paths;
    }
}
