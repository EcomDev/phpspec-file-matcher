<?php

namespace EcomDev\PHPSpec\FileMatcher;

class FileCheck implements CheckInterface
{
    /**
     * @var bool
     */
    private $expectedResult;

    /**
     * @param bool $expectedResult
     */
    public function __construct($expectedResult)
    {
        $this->expectedResult = $expectedResult;
    }

    /**
     * Validates existence of all passed files
     *
     * @param string[] $files
     * @return bool
     */
    public function __invoke(array $files)
    {
        foreach ($files as $file) {
            if (is_file($file) !== $this->expectedResult) {
                return [$file];
            }
        }

        return true;
    }
}
