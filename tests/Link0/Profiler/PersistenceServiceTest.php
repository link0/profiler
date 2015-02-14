<?php

/**
 * PersistenceServiceTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;
use Link0\Profiler\PersistenceHandler\NullHandler;

/**
 * Class PersistenceServiceTest
 *
 * @package Link0\Profiler
 */
class PersistenceServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PersistenceHandlerInterface $persistenceHandler
     */
    protected $persistenceHandler;

    /**
     * @var PersistenceService $persistenceService
     */
    protected $persistenceService;

    /**
     * Sets up objects used in these tests
     */
    public function setUp()
    {
        $this->persistenceHandler = new PersistenceHandler\MemoryHandler();
        $this->persistenceService = new PersistenceService($this->persistenceHandler);
    }

    /**
     * Tests the instantiation and constructor with a primary persistance handler of a nullobject implementation
     */
    public function testCanBeInstantiatedAndConstructor()
    {
        $this->assertInstanceOf('\Link0\Profiler\PersistenceService', $this->persistenceService);
        $this->assertSame($this->persistenceService->getPersistenceHandlers()[0], $this->persistenceHandler);
    }

    /**
     * Test the inner working of the secondary persistence handler set
     */
    public function testAddHandler()
    {
        $this->assertSame(1, sizeof($this->persistenceService->getPersistenceHandlers()));

        $firstPersistenceHandler = new NullHandler();
        $secondPersistenceHandler = new NullHandler();

        $self = $this->persistenceService->addPersistenceHandler($firstPersistenceHandler);
        $this->assertSame($self, $this->persistenceService);
        $this->assertEquals(2, sizeof($this->persistenceService->getPersistenceHandlers()));
        $this->assertSame($firstPersistenceHandler, $this->persistenceService->getPersistenceHandlers()[1]);
        $this->assertNotSame($this->persistenceService->getPersistenceHandlers()[0], $this->persistenceService->getPersistenceHandlers()[1]);

        $this->persistenceService->addPersistenceHandler($secondPersistenceHandler);
        $this->assertEquals(3, sizeof($this->persistenceService->getPersistenceHandlers()));
    }

    public function testListOperationOnHandler()
    {
        $this->assertEmpty($this->persistenceService->getList());
    }


    public function testPersistAndRetrievePrimary()
    {
        $persistenceHandler = new PersistenceHandler\MemoryHandler();
        $persistenceHandler->setProfileFactory(new ProfileFactory());

        $profile = $persistenceHandler->getProfileFactory()->create();
        $this->persistenceService->addPersistenceHandler($persistenceHandler);
        $this->persistenceService->persist($profile);
        $this->assertEquals($profile, $this->persistenceService->retrieve($profile->getIdentifier()));
    }
}