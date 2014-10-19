<?php

/**
 * FilesystemHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Cache\Noop;
use Link0\Profiler\Filesystem;
use Link0\Profiler\FilesystemInterface;
use Link0\Profiler\Profile;
use \Mockery as M;

/**
 * FilesystemHandler Test
 *
 * @package Link0\Profiler
 */
class FilesystemHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilesystemInterface $filesystem
     */
    private $filesystem;

    /**
     * @var FilesystemHandler $persistenceHandler
     */
    private $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->filesystem = new Filesystem(new Local('/tmp/link0-profiler-unittest'));
        $this->persistenceHandler = new FilesystemHandler($this->filesystem);
    }

    /**
     * Tests if the FilesystemHandler PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new FilesystemHandler($this->filesystem);
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\FilesystemHandler', $persistenceHandler);
    }

    /**
     * @expectedException \League\Flysystem\FileNotFoundException
     * @expectedExceptionMessage File not found at path: a5949548-2719-44ae-99bb-9428fa91c2b1.profile
     */
    public function testFileNotFoundWhenNotPersisted()
    {
        $profile = new Profile('a5949548-2719-44ae-99bb-9428fa91c2b1');
        $this->assertEmpty($this->persistenceHandler->getList());

        // Default identifier is not yet persisted, assert null
        $this->assertNull($this->persistenceHandler->retrieve($profile->getIdentifier()));
    }

    /**
     * Tests the FilesystemHandler implementation
     */
    public function testStorageAndRetrieval()
    {
        // Create an empty profile with self-generated identifier
        $profile = new Profile();
        $this->assertEmpty($this->persistenceHandler->getList());

        // Persist the profile
        $self = $this->persistenceHandler->persist($profile);
        $this->assertSame($self, $this->persistenceHandler);
        $this->assertEquals(1, sizeof($this->persistenceHandler->getList()));
        $this->assertSame($profile->getIdentifier(), $this->persistenceHandler->getList()[0]);

        // Assert retrieval back again
        $this->assertEquals($profile, $this->persistenceHandler->retrieve($profile->getIdentifier()));

        $this->persistenceHandler->emptyList();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unable to persist Profile[identifier=foo]
     */
    public function testUnableToPersist()
    {
        $mock = M::mock('League\Flysystem\AdapterInterface');
        $mock->shouldReceive('has')->withAnyArgs()->andReturn(false);
        $mock->shouldReceive('write')->withAnyArgs()->andReturn(false);

        $profile = new Profile('foo');
        $filesystem = new Filesystem($mock);
        $filesystemHandler = new FilesystemHandler($filesystem);
        $filesystemHandler->persist($profile);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unable to delete Profile[identifier=foo]
     */
    public function testUnableToDelete()
    {
        $mock = M::mock('League\Flysystem\AdapterInterface');
        $mock->shouldReceive('has')->withAnyArgs()->andReturn(true);
        $mock->shouldReceive('listContents')->withAnyArgs()->andReturn(array(array(
            'type' => 'file',
            'path' => 'foo',
        )));
        $mock->shouldReceive('delete')->withAnyArgs()->andReturn(false);

        $filesystem = new Filesystem($mock, new Noop());
        $filesystemHandler = new FilesystemHandler($filesystem);
        $filesystemHandler->emptyList();
    }

    public function tearDown()
    {
        m::close();
    }
}