<?php

namespace EcomDev\PHPSpec\FileMatcher;

use EcomDev\PHPSpec\FileMatcher\Matcher\Directory;
use EcomDev\PHPSpec\FileMatcher\Matcher\File;
use EcomDev\PHPSpec\FileMatcher\Matcher\FileContent;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $container->set('matchers.file', function () {
            return new File();
        });

        $container->set('matchers.file_content', function () {
            return new FileContent();
        });

        $container->set('matchers.directory', function () {
            return new Directory();
        });
    }
}
