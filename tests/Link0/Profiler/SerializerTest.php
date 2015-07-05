<?php

/**
 * SerializerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

use Link0\Profiler\Exception;

/**
 * Class SerializerTest
 *
 * @package Link0\Profiler
 */
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProfileFactory
     */
    private $profileFactory;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Setup method is ran for every test
     */
    public function setUp()
    {
        $this->profileFactory = new ProfileFactory();
        $this->serializer = new Serializer($this->profileFactory);
    }

    public function testSerialization()
    {
        $profile = $this->profileFactory->create([

        ]);

        $serializedData = $this->serializer->serialize($profile);
        $unserializedData = $this->serializer->unserialize($serializedData);
        $this->assertEquals($profile, $unserializedData);
    }

    /**
     * @expectedException \Link0\Profiler\SerializerException
     * @expectedExceptionMessage The format "foobar" is not supported for serialization.
     */
    public function testUnsupportedFormatForSerializeThrowsException()
    {
        $serializer = new Serializer(new ProfileFactory(), 'foobar');
        $serializer->serialize(Profile::create());
    }

    /**
     * @expectedException \Link0\Profiler\SerializerException
     * @expectedExceptionMessage The format "foobar" is not supported for deserialization.
     */
    public function testUnsupportedFormatForUnserializeThrowsException()
    {
        $serializer = new Serializer(new ProfileFactory(), 'foobar');
        $serializer->unserialize('{}');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Class name 'Link0\Profiler\FakeClass' does not match ProfileFactory for 'Link0\Profiler\Profile'
     */
    public function testUnserializeClassMismatchWithFactory()
    {
        $serializer = new Serializer(new ProfileFactory());
        $serializer->serialize(new FakeClass());
    }

    /**
     * @expectedException \JMS\Serializer\Exception\RuntimeException
     * @expectedExceptionMessage Could not decode JSON, syntax error - malformed JSON.
     */
    public function testUnexpectedDataToThrowException()
    {
        $this->serializer->unserialize('FDSAfoobar');
    }
}

class FakeClass implements ProfileInterface {
    /**
     * @param  string $identifier
     *
     * @return ProfileInterface $this
     */
    public function setIdentifier($identifier)
    {
        // TODO: Implement setIdentifier() method.
    }

    /**
     * @return string $identifier
     */
    public function getIdentifier()
    {
        // TODO: Implement getIdentifier() method.
    }

    /**
     * @param  array $serverData
     *
     * @return ProfileInterface $this
     */
    public function setServerData($serverData)
    {
        // TODO: Implement setServerData() method.
    }

    /**
     * @return array $serverData
     */
    public function getServerData()
    {
        // TODO: Implement getServerData() method.
    }

    /**
     * @param array $applicationData
     *
     * @return ProfileInterface $this
     */
    public function setApplicationData($applicationData)
    {
        // TODO: Implement setApplicationData() method.
    }

    /**
     * @return array $applicationData
     */
    public function getApplicationData()
    {
        // TODO: Implement getApplicationData() method.
    }

    /**
     * @param array $profileData
     *
     * @return ProfileInterface $this
     */
    public function setProfileData($profileData)
    {
        // TODO: Implement setProfileData() method.
    }

    /**
     * @return array $profileData
     */
    public function getProfileData()
    {
        // TODO: Implement getProfileData() method.
    }

    /**
     * Returns an array representation of this object.
     * This array representation is also used for persistence.
     *
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @param array $arrayData
     *
     * @return ProfileInterface $profile
     */
    public static function fromArray($arrayData)
    {
        // TODO: Implement fromArray() method.
    }

    /**
     * @param string|null $identifier
     *
     * @return ProfileInterface
     */
    public static function create($identifier = null)
    {
        // TODO: Implement create() method.
    }}