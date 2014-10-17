<?php

/**
 * RedisHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;
use Predis\Client;

/**
 * Redis implementation for Persistence
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class RedisHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var \Predis\Client $engine
     */
    protected $engine;

    /**
     * @var array
     */
    protected $list;

    /**
     * Constructor
     *
     * @param array $parameters Connection parameters for connecting to Redis
     * @see \Predis\Client::__construct
     */
    public function __construct($parameters = array())
    {
        $this->engine = new Client($parameters);
        $this->list = array();
    }

    /**
     * @param  $engine
     * @return $this
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @return Client $engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        // TODO: Implement me!
        return $this->list;
    }

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        return unserialize($this->engine->get($identifier));
    }

    /**
     * @param  Profile                     $profile
     * @return PersistenceHandlerInterface $this
     */
    public function persist(Profile $profile)
    {
        $this->list[] = $profile->getIdentifier();
        $this->engine->set($profile->getIdentifier(), serialize($profile));

        return $this;
    }
}
