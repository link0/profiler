<?php

/**
 * MemoryHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;

/**
 * MemoryHandler implementation for PersistenceHandler's, can be useful in unit tests
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class MemoryHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var PersistenceHandlerInterface[] $state
     */
    protected $state = array();

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        return isset($this->state[$identifier]) ? $this->state[$identifier] : null;
    }

    /**
     * @param  Profile $profile
     * @return PersistenceHandlerInterface
     */
    public function persist(Profile $profile)
    {
        $this->state[$profile->getIdentifier()] = $profile;
        return $this;
    }
}