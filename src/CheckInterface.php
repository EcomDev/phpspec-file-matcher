<?php
/**
 * phpspec-file-matcher
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2016 EcomDev BV (http://www.ecomdev.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ivan Chepurnyi <ivan@ecomdev.org>
 */
namespace EcomDev\PHPSpec\FileMatcher;

interface CheckInterface
{
    /**
     * Validates passed arguments
     *
     * @param string[] $arguments
     * @return bool
     */
    public function __invoke(array $arguments);
}
