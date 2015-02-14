<?php

/**
 * MongoDbHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\Profile;
use Mockery;

/**
 * MongoDbHandlerTest
 *
 * @package Link0\Profiler\PersistenceHandler
 */
class MongoDbHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MongoDbHandler $handler
     */
    private $handler;

    /**
     * @var MongoCollection $collection
     */
    private $mongoCollection;

    public function setUp()
    {
        $traversable = new \ArrayObject();

        $mongoCollection = Mockery::mock('\Link0\Profiler\PersistenceHandler\MongoDbHandler\MongoCollectionInterface');
        $mongoCollection->shouldReceive('find')->once()->andReturn($traversable);
        $mongoCollection->shouldReceive('insert')->once()->andReturn(true);
        $this->mongoCollection = $mongoCollection;

        $mongoDb = Mockery::mock('\Link0\Profiler\PersistenceHandler\MongoDbHandler\MongoDbInterface');
        $mongoDb->bar = $mongoCollection;

        $client = Mockery::mock('\Link0\Profiler\PersistenceHandler\MongoDbHandler\MongoClientInterface');
        $client->foo = $mongoDb;

        $this->handler = new MongoDbHandler($client, 'foo', 'bar');
    }

    public function testEmptyList()
    {
        $this->assertEmpty($this->handler->getList());
    }

    public function testRetrieveReturnsNullWhenNotFound()
    {
        $this->mongoCollection->shouldReceive('findOne');
        $this->assertNull($this->handler->retrieve('FooBarNonExistent'));
    }

    public function testRetrieveReturnsProfile()
    {
        $profile = new Profile();
        $this->mongoCollection->shouldReceive('findOne')->once()->andReturn(array(
            'identifier' => $profile->getIdentifier(),
            'profile' => serialize($profile->toArray()),
        ));
        $this->assertInstanceOf('\Link0\Profiler\Profile', $this->handler->retrieve('Foo'));
    }

    public function testPersistReturnsSelf()
    {
        $this->assertSame($this->handler, $this->handler->persist(new Profile()));
    }

    public function testPersistProfile()
    {
        $profile = new Profile();
        $this->handler->persist($profile);
    }
}