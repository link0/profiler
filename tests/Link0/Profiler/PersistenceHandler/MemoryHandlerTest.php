<?php

/**
 * MemoryHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;
use Link0\Profiler\Profile;

/**
 * Memory Test
 *
 * @package Link0\Profiler
 */
class MemoryHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MemoryHandler $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new MemoryHandler();
    }

    /**
     * Tests if the Memory PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new MemoryHandler();
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\MemoryHandler', $persistenceHandler);
    }

    /**
     * Tests the MemoryHandler implementation
     */
    public function testStorageAndRetrieval()
    {
        // Create an empty profile with self-generated identifier
        $profile = new Profile();
        $this->assertEmpty($this->persistenceHandler->getList());

        // Default identifier is not yet persisted, assert null
        $this->assertNull($this->persistenceHandler->retrieve($profile->getIdentifier()));

        // Persist the profile
        $self = $this->persistenceHandler->persist($profile);
        $this->assertSame($self, $this->persistenceHandler);
        $this->assertEquals(1, sizeof($this->persistenceHandler->getList()));
        $this->assertSame($profile->getIdentifier(), $this->persistenceHandler->getList()[0]);

        // Assert retrieval back again
        $this->assertEquals($profile, $this->persistenceHandler->retrieve($profile->getIdentifier()));
    }
}