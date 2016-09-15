<?php

namespace EcomDev\PHPSpec\FileMatcher;

/**
 * File existence check class
 */
class FileCheck implements CheckInterface
{
    /**
     * Expected result for check
     *
     * @var bool
     */
    private $expectedResult;

    public function __construct($expectedResult)
    {
        $this->expectedResult = (bool)$expectedResult;
    }

    /**
     * Validates existence of all passed files
     *
     * @param string[] $files
     *
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
