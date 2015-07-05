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
     * @param null|PersistenceHandlerInterface $handler
     */
    public function __construct(PersistenceHandlerInterface $handler = null)
    {
        if ($handler === null) {
            $handler = new NullHandler(new Serializer(new ProfileFactory()));
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
     * @return string[] $profileIdentifiers
     */
    public function getList()
    {
        return $this->persistenceHandlers[0]->getList();
    }

    /**
     * @param  string                $identifier
     * @return ProfileInterface|null $profile
     */
    public function retrieve($identifier)
    {
        return $this->persistenceHandlers[0]->retrieve($identifier);
    }

    /**
     * Persists data to the persistence handlers
     *
     * @param  ProfileInterface   $profile
     * @return PersistenceService $this
     */
    public function persist(ProfileInterface $profile)
    {
        foreach ($this->persistenceHandlers as $persistenceHandler) {
            $persistenceHandler->persist($profile);
        }

        return $this;
    }
}
