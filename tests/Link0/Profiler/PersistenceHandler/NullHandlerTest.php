<?php

/**
 * NullHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;
use Link0\Profiler\Profile;

/**
 * NullHandler Test
 *
 * @package Link0\Profiler
 */
class NullHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullHandler $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * Setup objects for all tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new NullHandler();
    }

    /**
     * Tests if the NullHandler PersistenceHandler can be instantiated
     */
    public function testCanBeInstantiated()
    {
        $persistenceHandler = new NullHandler();
        $this->assertInstanceOf('\Link0\Profiler\PersistenceHandler\NullHandler', $persistenceHandler);
    }

    public function testListIsEmptyOnNullHandler()
    {
        $this->assertEmpty($this->persistenceHandler->getList());
    }

    /**
     * Tests the NullHandler implementation, no matter what argument it given, null is returned
     */
    public function testRetrieveNullHandler()
    {
        $this->assertNull($this->persistenceHandler->retrieve('Foo'));
    }

    /**
     * Tests the NullHandler implementation, always returns itself, can't really be tested that it does nothing
     */
    public function testPersistNullHandler()
    {
        $profile = Profile::create();
        $persistenceHandler = $this->persistenceHandler->persist($profile);
        $this->assertSame($this->persistenceHandler, $persistenceHandler);
    }
}