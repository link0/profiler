<?php

/**
 * PersistenceServiceTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;
use Link0\Profiler\PersistenceHandler\NullObject;

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
        $this->persistenceHandler = new PersistenceHandler\NullObject();
        $this->persistenceService = new PersistenceService($this->persistenceHandler);
    }

    /**
     * Tests the instantiation and constructor with a primary persistance handler of a nullobject implementation
     */
    public function testCanBeInstantiatedAndConstructor()
    {
        $this->assertInstanceOf('\Link0\Profiler\PersistenceService', $this->persistenceService);
        $this->assertSame($this->persistenceService->getPrimaryPersistenceHandler(), $this->persistenceHandler);
    }

    /**
     * Test if the primary handler can be set
     */
    public function testCanPrimaryHandlerBeSet()
    {
        $persistenceHandlerNullObject = new PersistenceHandler\NullObject();
        $this->persistenceService->setPrimaryPersistenceHandler($persistenceHandlerNullObject);

        $this->assertSame($persistenceHandlerNullObject, $this->persistenceService->getPrimaryPersistenceHandler());
        $this->assertNotSame($this->persistenceHandler, $this->persistenceService->getPrimaryPersistenceHandler());
    }

    /**
     * Test the inner working of the secondary persistence handler set
     */
    public function testSecondaryHandlerSet()
    {
        $this->assertEmpty($this->persistenceService->getSecondaryPersistenceHandlers());

        $firstPersistenceHandler = new NullObject();
        $secondPersistenceHandler = new NullObject();

        $self = $this->persistenceService->addSecondaryPersistenceHandler($firstPersistenceHandler);
        $this->assertSame($self, $this->persistenceService);
        $this->assertEquals(1, sizeof($this->persistenceService->getSecondaryPersistenceHandlers()));
        $this->assertSame($firstPersistenceHandler, $this->persistenceService->getSecondaryPersistenceHandlers()[0]);
        $this->assertNotSame($this->persistenceService->getPrimaryPersistenceHandler(), $this->persistenceService->getSecondaryPersistenceHandlers()[0]);

        $this->persistenceService->addSecondaryPersistenceHandler($secondPersistenceHandler);
        $this->assertEquals(2, sizeof($this->persistenceService->getSecondaryPersistenceHandlers()));
        $this->assertSame($secondPersistenceHandler, $this->persistenceService->getSecondaryPersistenceHandlers()[1]);
        $this->assertNotSame($secondPersistenceHandler, $this->persistenceService->getSecondaryPersistenceHandlers()[0]);
        $this->assertNotSame($firstPersistenceHandler, $this->persistenceService->getSecondaryPersistenceHandlers()[1]);
    }

    public function testPersistAndRetrievePrimary()
    {
        $profile = new Profile();

        $this->persistenceService->setPrimaryPersistenceHandler(new PersistenceHandler\Memory());
        $this->persistenceService->addSecondaryPersistenceHandler(new PersistenceHandler\NullObject());
        $this->persistenceService->persist($profile);
        $this->assertSame($profile, $this->persistenceService->retrieve($profile->getIdentifier()));
    }
}