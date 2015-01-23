<?php

/**
 * ProfileTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Class ProfileTest
 *
 * @package Link0\Profiler
 */
class ProfileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing the constructor argument works with default arguments
     */
    public function testConstructorArguments()
    {
        $profile = new Profile();
        $this->assertTrue((is_string($profile->getIdentifier()) && strlen($profile->getIdentifier()) > 0));
    }

    /**
     * Tests the setIdentifier works with getter
     */
    public function testSetIdentifier()
    {
        $profile = new Profile();
        $profile->setIdentifier('foo');
        $this->assertEquals('foo', $profile->getIdentifier());
    }

}