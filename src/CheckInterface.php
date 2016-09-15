<?php

namespace EcomDev\PHPSpec\FileMatcher;

/**
 * Checker interface
 */
interface CheckInterface
{
    /**
     * Validates passed arguments
     *
     * @param string[] $arguments
     *
     * @return bool
     */
    public function __invoke(array $arguments);
}
