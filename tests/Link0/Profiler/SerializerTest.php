<?php

/**
 * SerializerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Class SerializerTest
 *
 * @package Link0\Profiler
 */
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Setup method is ran for every test
     */
    public function setUp()
    {
        $this->serializer = new Serializer();
    }

    public function testSerialization()
    {
        $foo = array(
            'foo' => 'bar',
        );

        $serializedData = $this->serializer->serialize($foo);
        $unserializedData = $this->serializer->unserialize($serializedData);
        $this->assertEquals($foo, $unserializedData);
    }

    /**
     * @expectedException \Link0\Profiler\SerializerException
     * @expectedExceptionMessage Unable to unserialize data: FDSAfoobar
     */
    public function testUnexpectedDataToThrowException()
    {
        $this->serializer->unserialize('FDSAfoobar');
    }
}