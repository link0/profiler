<?php

/**
 * NullHandler.php
 *
 * @author Dennis de Greef <gitlab@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;

/**
 * NullHandler implements the PersistenceHandlerInterface but acts upon nothing
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class NullHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        return array();
    }

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