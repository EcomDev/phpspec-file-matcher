<?php

namespace EcomDev\PHPSpec\FileMatcher;

/**
 * File content check class
 */
class FileContentCheck implements CheckInterface
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
     * @param string[] $filePair
     *
     * @return bool
     */
    public function __invoke(array $filePair)
    {
        list($filePath, $content) = $filePair;

        $fileContentMatch = false;

        if (is_file($filePath)) {
            $fileContentMatch = file_get_contents($filePath) === $content;
        }
        
        if ($fileContentMatch !== $this->expectedResult) {
            return [$filePath, $content];
        }

        return true;
    }
}
