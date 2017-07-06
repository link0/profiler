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
     * @var \Mockery\MockInterface
     */
    private $client;

    public function setUp()
    {
        $client = Mockery::mock('\Link0\Profiler\PersistenceHandler\MongoDbHandler\MongoClientInterface');
        $this->client = $client;

        $this->handler = new MongoDbHandler($client, 'foo', 'bar');
    }

    public function testEmptyList()
    {
        $this->client->shouldReceive('executeQuery')->once()->andReturn(new \ArrayObject());
        $this->assertEmpty($this->handler->getList());
    }

    public function testRetrieveReturnsNullWhenNotFound()
    {
        $this->client->shouldReceive('executeQuery')->andReturn(array());
        $this->assertNull($this->handler->retrieve('FooBarNonExistent'));
    }

    public function testRetrieveReturnsProfile()
    {
        $profile = Profile::create();
        $this->client->shouldReceive('executeQuery')->once()->andReturn(array(
            array(
                'identifier' => $profile->getIdentifier(),
                'profile' => serialize($profile->toArray()),
            )
        ));

        $this->assertInstanceOf('\Link0\Profiler\Profile', $this->handler->retrieve('Foo'));
    }

    public function testPersistReturnsSelf()
    {
        $profile = Profile::create();
        $profile->setServerData(array());

        $this->client->shouldReceive('insert')->once();

        $this->assertSame($this->handler, $this->handler->persist($profile));
    }

    public function testPersistProfile()
    {
        $profile = Profile::create();

        $this->client->shouldReceive('insert')->once();

        $this->handler->persist($profile);
    }

    public function testPersistWithRoundMicrotime()
    {
        $profile = Profile::create();
        $profile->setServerData(array(
            'REQUEST_TIME_FLOAT' => 1234,
        ));

        $this->client->shouldReceive('insert')->once();

        $this->handler->persist($profile);
    }
}
