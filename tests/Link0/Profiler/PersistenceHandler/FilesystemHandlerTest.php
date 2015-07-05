<?php

/**
 * FilesystemHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Exception;
use Link0\Profiler\Profile;
use Link0\Profiler\ProfileFactory;
use Link0\Profiler\Serializer;
use \Mockery as M;
use Mockery;

/**
 * FilesystemHandler Test
 *
 * @package Link0\Profiler
 */
class FilesystemHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\Mock $filesystem
     */
    private $filesystem;

    /**
     * @var FilesystemHandler $persistenceHandler
     */
    private $persistenceHandler;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->filesystem = Mockery::mock('\League\Flysystem\FilesystemInterface');

        $this->serializer = new Serializer(new ProfileFactory());

        $this->filesystem->shouldReceive('listFiles')->andReturn(array())->byDefault();
        $this->filesystem->shouldReceive('put')->andReturn(true)->byDefault();
        $this->filesystem->shouldReceive('read')->andReturn(false)->byDefault();


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

    public function testFileNotFoundWhenNotPersisted()
    {
        $profile = Profile::create('a5949548-2719-44ae-99bb-9428fa91c2b1');
        $this->assertEmpty($this->persistenceHandler->getList());

        // Default identifier is not yet persisted, assert null
        $this->assertNull($this->persistenceHandler->retrieve($profile->getIdentifier()));
    }

    public function testListEmptyByDefault()
    {
        $this->assertEmpty($this->persistenceHandler->getList());
    }

    /**
     * Tests the FilesystemHandler implementation
     */
    public function testPersist()
    {
        // Create an empty profile with self-generated identifier
        $profile = Profile::create();

        // Persist the profile
        $self = $this->persistenceHandler->persist($profile);
        $this->assertSame($self, $this->persistenceHandler);

        $this->filesystem->shouldReceive('read')
            ->withArgs(array(
                '/' . $profile->getIdentifier() . '.profile'
            ))
            ->andReturn($this->serializer->serialize($profile));


        $this->filesystem->shouldReceive('listFiles')->andReturn(array(
            array(
                'filename' => $profile->getIdentifier(),
            ),
        ));

        $list = $this->persistenceHandler->getList();
        $this->assertEquals(1, sizeof($list));
        $this->assertSame($profile->getIdentifier(), $list[0]);

        // Assert retrieval back again
        $this->assertEquals($profile, $this->persistenceHandler->retrieve($profile->getIdentifier()));
    }

    public function testUnableToRetrieve()
    {
        $this->assertNull($this->persistenceHandler->retrieve('foo'));
    }

    public function testRetrieveWithoutFileBeingFound()
    {
        $this->filesystem->shouldReceive('read')->andThrow('\League\Flysystem\FileNotFoundException');
        $this->persistenceHandler->retrieve('foo');
    }

    /**
     * @expectedException \Link0\Profiler\PersistenceHandler\Exception
     * @expectedExceptionMessage Unable to persist Profile[identifier=foo]
     */
    public function testUnableToPersist()
    {
        $this->filesystem->shouldReceive('put')->andReturn(false);

        $profile = Profile::create('foo');
        $this->persistenceHandler->persist($profile);
    }

    public function testEmptyEmptyList()
    {
        $this->persistenceHandler->emptyList();
    }

    /**
     * @expectedException \Link0\Profiler\PersistenceHandler\Exception
     * @expectedExceptionMessage Unable to delete Profile[identifier=foo]
     */
    public function testEmptyListUnableToDelete()
    {
        $this->filesystem->shouldReceive('listFiles')->andReturn(array(
            array(
                'filename' => 'foo',
            ),
        ));
        $this->filesystem->shouldReceive('delete')->andReturn(false);
        $this->persistenceHandler->emptyList();
    }

    public function tearDown()
    {
        m::close();
    }
}
