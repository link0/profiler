<?php

/**
 * NullObjectTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;
use Link0\Profiler\Profile;

/**
 * NullObject Test
 *
 * @package Link0\Profiler
 */
class NullObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullObject $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new NullObject();
    }

    /**
     * Tests if the NullObject PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new NullObject();
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\NullObject', $persistenceHandler);
    }

    /**
     * Tests the NullObject implementation, no matter what argument it given, null is returned
     */
    public function testRetrieveNullObject()
    {
        $this->assertNull($this->persistenceHandler->retrieve('Foo'));
        $this->assertNull($this->persistenceHandler->retrieve(new \stdClass()));
        $this->assertNull($this->persistenceHandler->retrieve(array()));
    }

    /**
     * Tests the NullObject implementation, always returns itself, can't really be tested that it does nothing
     */
    public function testPersistNullObject()
    {
        $profile = new Profile();
        $persistenceHandler = $this->persistenceHandler->persist($profile);
        $this->assertSame($this->persistenceHandler, $persistenceHandler);
    }
}