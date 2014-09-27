<?php

/**
 * PersistenceService.php
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;
use Link0\Profiler\PersistenceHandler\NullHandler;

/**
 * This service handles all persistence
 *
 * @package Link0\Profiler
 */
final class PersistenceService
{
    /**
     * @var PersistenceHandlerInterface[] $persistenceHandlers
     */
    protected $persistenceHandlers;

    /**
     * @param PersistenceHandlerInterface $handler
     */
    public function __construct(PersistenceHandlerInterface $handler = null)
    {
        if($handler == null) {
            $handler = new NullHandler();
        }

        $this->persistenceHandlers = array($handler);
    }

    /**
     * @param  PersistenceHandlerInterface $handler
     * @return PersistenceService          $this
     */
    public function addPersistenceHandler(PersistenceHandlerInterface $handler)
    {
        $this->persistenceHandlers[] = $handler;
        return $this;
    }

    /**
     * @return PersistenceHandlerInterface[] $handlers
     */
    public function getPersistenceHandlers()
    {
        return $this->persistenceHandlers;
    }

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        return $this->persistenceHandlers[0]->retrieve($identifier);
    }

    /**
     * Persists data to the persistence handlers
     *
     * @param  Profile            $profile
     * @return PersistenceService $this
     */
    public function persist(Profile $profile)
    {
        foreach($this->persistenceHandlers as $persistenceHandler) {
            $persistenceHandler->persist($profile);
        }
        return $this;
    }
}