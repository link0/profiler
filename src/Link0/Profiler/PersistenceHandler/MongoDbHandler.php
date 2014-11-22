<?php

/**
 * MongoDbHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandler\MongoDbHandler\MongoClientInterface;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;
use MongoCollection;
use MongoDB;

/**
 * MongoDb implementation for Persistence
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class MongoDbHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var MongoClientInterface
     */
    private $client;

    /**
     * @var MongoDB
     */
    private $database;

    /**
     * @var MongoCollection
     */
    private $collection;

    /**
     * @param MongoClientInterface $client
     * @param string $databaseName
     * @param string $collection
     */
    public function __construct(MongoClientInterface $client, $databaseName = 'xhprof', $collection = 'results')
    {
        $this->client = $client;
        $this->database = $this->client->$databaseName;
        $this->collection = $this->database->$collection;
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        // Awaiting https://github.com/link0/profiler/issues/60 for refactoring
        // The getList interface (or renamed method) should return an Iterator of some kind
        return iterator_to_array($this->collection->find());
    }

    /**
     * @param  string $identifier
     *
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        $profileData = $this->collection->findOne([
            'identifier' => $identifier,
        ]);


        if($profileData !== null) {
            return unserialize($profileData['profile']);
        }

        return null;
    }

    /**
     * @param  Profile $profile
     *
     * @return PersistenceHandlerInterface $this
     */
    public function persist(Profile $profile)
    {
        $mongoData = array(
            'identifier' => $profile->getIdentifier(),
            'profile' => serialize($profile)
        );

        $this->collection->insert($mongoData);

        return $this;
    }
}