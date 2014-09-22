<?php

/**
 * Memory.php
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
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Memory $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new Memory();
    }

    /**
     * Tests if the Memory PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new Memory();
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\Memory', $persistenceHandler);
    }

    /**
     * Tests the Memory implementation
     */
    public function testStorageAndRetrieval()
    {
        // Create an empty profile with self-generated identifier
        $profile = new Profile();

        // Default identifier is not yet persisted, assert null
        $this->assertNull($this->persistenceHandler->retrieve($profile->getIdentifier()));

        // Persist the profile
        $self = $this->persistenceHandler->persist($profile);
        $this->assertSame($self, $this->persistenceHandler);

        // Assert retrieval back again
        $this->assertSame($profile, $this->persistenceHandler->retrieve($profile->getIdentifier()));
    }
}