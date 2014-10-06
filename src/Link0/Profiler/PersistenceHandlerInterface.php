<?php

/**
 * PersistenceHandlerInterface.php
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Interface for all PersistenceHandler implementations
 *
 * @package Link0\Profiler
 */
interface PersistenceHandlerInterface
{
    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList();

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier);

    /**
     * @param  Profile                     $profile
     * @return PersistenceHandlerInterface $this
     */
    public function persist(Profile $profile);
}
