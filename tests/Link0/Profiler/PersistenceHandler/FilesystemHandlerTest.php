<?php

/**
 * FilesystemHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;
use Link0\Profiler\Profile;

/**
 * FilesystemHandler Test
 *
 * @package Link0\Profiler
 */
class FilesystemHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilesystemHandler $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new FilesystemHandler('/tmp/link0-profiler-unittest');
    }

    /**
     * Tests if the FilesystemHandler PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new FilesystemHandler();
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\FilesystemHandler', $persistenceHandler);
    }

    /**
     * Tests the FilesystemHandler implementation
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

        $this->persistenceHandler->emptyList();
    }
}