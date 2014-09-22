<?php

/**
 * NullObject.php
 *
 * @author Dennis de Greef <gitlab@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;

/**
 * NullObject implements the PresistenceHandlerInterface but acts upon nothing
 *
 * @package Link0\Profiler\PersistenceHandler
 */
class NullObject extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @param  string       $identifier
     * @return Profile|null $data
     */
    public function retrieve($identifier)
    {
        return null;
    }

    /**
     * @param  Profile                     $profile
     * @return PersistenceHandlerInterface $this
     */
    public function persist(Profile $profile)
    {
        return $this;
    }
}