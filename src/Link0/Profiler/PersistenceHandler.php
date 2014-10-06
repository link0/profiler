<?php

/**
 * PersistenceHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Abstract PersistenceHandler should be used as a base for all implementations
 *
 * @package Link0\Profiler
 */
abstract class PersistenceHandler
{
    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    abstract public function getList();

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    abstract public function retrieve($identifier);

    /**
     * @param  Profile                     $profile
     * @return PersistenceHandlerInterface $this
     */
    abstract public function persist(Profile $profile);
}
