<?php declare(strict_types=1);

/**
 * MemoryHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\ProfileInterface;

/**
 * MemoryHandler implementation for PersistenceHandler's, can be useful in unit tests
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class MemoryHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var array $state
     */
    protected $state = array();

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return array<integer|string>
     */
    public function getList()
    {
        return array_keys($this->state);
    }

    /**
     * @param  string                $identifier
     * @return ProfileInterface|null $profile
     */
    public function retrieve($identifier)
    {
        if(!isset($this->state[$identifier])) {
            return null;
        }

        return $this->createProfileFromProfileData($this->state[$identifier]);
    }

    /**
     * @param  ProfileInterface            $profile
     * @return PersistenceHandlerInterface
     */
    public function persist(ProfileInterface $profile)
    {
        $this->state[$profile->getIdentifier()] = $this->getSerializer()->serialize($profile->toArray());

        return $this;
    }
}
