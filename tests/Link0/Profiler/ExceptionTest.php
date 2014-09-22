<?php

/**
 * ExceptionTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Profiler Exception Test
 *
 * @package Link0\Profiler
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if the Exception can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $e = new Exception();
        $this->assertInstanceOf('\Link0\Profiler\Exception', $e);
    }
}