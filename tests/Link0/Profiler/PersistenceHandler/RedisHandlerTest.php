<?php

/**
 * RedisHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;
use Link0\Profiler\Profile;
use Predis\Client;
use Mockery as m;

/**
 * RedisHandler Test
 *
 * @package Link0\Profiler
 */
class RedisTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RedisHandler $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new RedisHandler();
    }

    /**
     * Tests if the Redis PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new RedisHandler();
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\RedisHandler', $persistenceHandler);
        $this->assertInstanceOf('\Predis\Client', $persistenceHandler->getEngine());
    }

    /**
     * Tests whether the Engine can be injected
     */
    public function testSetEngine()
    {
        $predisClient = new Client();
        $this->persistenceHandler->setEngine($predisClient);
        $this->assertSame($predisClient, $this->persistenceHandler->getEngine());
    }

    public function testRetrieveAndPersistByMocking()
    {
        $profile = new Profile();
        $redisHandler = new RedisHandler();

        $clientMock = m::mock('Client');
        $clientMock->shouldReceive('get')->andReturn('N;');
        $clientMock->shouldReceive('set')->andReturnSelf();
        $clientMock->shouldReceive('getList')->andReturn(array(
            $profile->getIdentifier() => $profile,
        ));

        $redisHandler->setEngine($clientMock);
        $this->assertSame($clientMock, $redisHandler->getEngine());
        $this->assertEmpty($redisHandler->getList());

        $this->assertSame($redisHandler, $redisHandler->persist($profile));
        $this->assertNull($redisHandler->retrieve('Foo'));
        $this->assertEquals(1, sizeof($redisHandler->getList()));
        $this->assertEquals($profile->getIdentifier(), $redisHandler->getList()[0]);
    }
}