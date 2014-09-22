<?php

/**
 * PersistenceService.php
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;
use Link0\Profiler\PersistenceHandler\NullObject;

/**
 * This service handles all persistence
 *
 * @package Link0\Profiler
 */
class PersistenceService
{
    /**
     * @var PersistenceHandlerInterface $primaryPersistenceHandler
     */
    protected $primaryPersistenceHandler;

    /**
     * @var PersistenceHandlerInterface[] $secondaryPersistenceHandlers
     */
    protected $secondaryPersistenceHandlers;

    /**
     * @param PersistenceHandlerInterface $handler
     */
    public function __construct(PersistenceHandlerInterface $handler = null)
    {
        if($handler == null) {
            $handler = new NullObject();
        }

        $this->primaryPersistenceHandler = $handler;
        $this->secondaryPersistenceHandlers = array();
    }

    /**
     * @param PersistenceHandlerInterface $handler
     * @return PersistenceService $this
     */
    public function setPrimaryPersistenceHandler(PersistenceHandlerInterface $handler)
    {
        $this->primaryPersistenceHandler = $handler;
        return $this;
    }

    /**
     * @return PersistenceHandlerInterface
     */
    public function getPrimaryPersistenceHandler()
    {
        return $this->primaryPersistenceHandler;
    }

    /**
     * @param PersistenceHandlerInterface $handler
     * @return $this
     */
    public function addSecondaryPersistenceHandler(PersistenceHandlerInterface $handler)
    {
        $this->secondaryPersistenceHandlers[] = $handler;
        return $this;
    }

    /**
     * @return PersistenceHandlerInterface[]
     */
    public function getSecondaryPersistenceHandlers()
    {
        return $this->secondaryPersistenceHandlers;
    }

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        return $this->primaryPersistenceHandler->retrieve($identifier);
    }

    /**
     * Persists data to the persistence handlers
     *
     * @param  Profile            $profile
     * @return PersistenceService $this
     */
    public function persist(Profile $profile)
    {
        $this->primaryPersistenceHandler->persist($profile);
        foreach($this->secondaryPersistenceHandlers as $secondaryPersistenceHandler) {
            $secondaryPersistenceHandler->persist($profile);
        }
        return $this;
    }
}