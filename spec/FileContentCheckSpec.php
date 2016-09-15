<?php

namespace spec\EcomDev\PHPSpec\FileMatcher;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileContentCheckSpec extends ObjectBehavior
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
        $this->beConstructedWith(true);
    }

    function it_returns_true_when_proper_file_content_passed_as_argument()
    {
        $this([$this->vfs->url() . '/file_1', 'text'])->shouldReturn(true);
    }


    function it_returns_passed_arguments_when_wrong_file_content_passed_as_argument()
    {
        $this([$this->vfs->url() . '/file_1', 'text2'])->shouldReturn([$this->vfs->url() . '/file_1', 'text2']);
    }

    function it_returns_passed_arguments_when_non_existing_file_passed_as_argument()
    {
        $this([$this->vfs->url() . '/file_3', 'text'])->shouldReturn([$this->vfs->url() . '/file_3', 'text']);
    }

    function it_returns_true_when_not_existing_file_passed_as_argument_for_negative_match()
    {
        $this->beConstructedWith(false);
        $this([$this->vfs->url() . '/file_3', 'text'])->shouldReturn(true);
    }

    function it_returns_true_when_non_matching_content_passed_as_argument_for_negative_match()
    {
        $this->beConstructedWith(false);
        $this([$this->vfs->url() . '/file_2', 'text2'])->shouldReturn(true);
    }

    function it_returns_passed_arguments_when_content_equals_to_passed_as_argument_for_negative_match()
    {
        $this->beConstructedWith(false);
        $this([$this->vfs->url() . '/file_2', 'text'])->shouldReturn([$this->vfs->url() . '/file_2', 'text']);
    }
}
