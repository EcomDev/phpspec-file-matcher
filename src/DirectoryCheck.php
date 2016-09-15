<?php

namespace EcomDev\PHPSpec\FileMatcher;

/**
 * Directory existence check class
 */
class DirectoryCheck implements CheckInterface
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
     * Validates existence of all passed directories
     *
     * @param string[] $directories
     *
     * @return bool
     */
    public function __invoke(array $directories)
    {
        foreach ($directories as $directory) {
            if (is_dir($directory) !== $this->expectedResult) {
                return [$directory];
            }
        }

        return true;
    }
}
