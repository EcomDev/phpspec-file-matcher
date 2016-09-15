<?php

namespace spec\EcomDev\PHPSpec\FileMatcher;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DirectoryCheckSpec extends ObjectBehavior
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

    function it_returns_true_when_existing_file_passed_as_argument()
    {
        $this([$this->vfs->url() . '/directory_1'])->shouldReturn(true);
        $this([$this->vfs->url() . '/directory_2'])->shouldReturn(true);
    }

    function it_returns_directory_name_when_existing_file_passed_as_argument()
    {
        $this([
            $this->vfs->url() . '/directory_1',
            $this->vfs->url() . '/file_1'
        ])->shouldReturn([$this->vfs->url() . '/file_1']);
    }


    function it_returns_directory_name_when_non_existing_passed_as_argument()
    {
        $this([$this->vfs->url() . '/directory_3'])->shouldReturn([$this->vfs->url() . '/directory_3']);
    }

    function it_returns_true_when_not_existing_directory_passed_as_argument_for_negative_match()
    {
        $this->beConstructedWith(false);
        $this([$this->vfs->url() . '/directory_3'])->shouldReturn(true);
    }
}
