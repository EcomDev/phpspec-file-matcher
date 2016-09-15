<?php

namespace EcomDev\PHPSpec\FileMatcher;

class DirectoryCheck implements CheckInterface
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
     * Validates existence of all passed directories
     *
     * @param string[] $directories
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
