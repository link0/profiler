<?php declare(strict_types=1);

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
     * @param  string                $identifier
     * @return ProfileInterface|null $profile
     */
    public function retrieve($identifier);

    /**
     * @param  ProfileInterface            $profile
     * @return PersistenceHandlerInterface $this
     */
    public function persist(ProfileInterface $profile);
}
