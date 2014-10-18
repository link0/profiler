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
     * @var Profile[] $state
     */
    protected $state = array();

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        return array_keys($this->state);
    }

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        return isset($this->state[$identifier]) === true ? $this->state[$identifier] : null;
    }

    /**
     * @param  Profile                     $profile
     * @return PersistenceHandlerInterface
     */
    public function persist(Profile $profile)
    {
        $this->state[$profile->getIdentifier()] = $profile;

        return $this;
    }
}
