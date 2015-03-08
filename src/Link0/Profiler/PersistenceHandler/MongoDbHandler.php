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
use Link0\Profiler\ProfileInterface;
use MongoCollection;
use MongoDate;
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
        parent::__construct();

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
     * @param  string                $identifier
     *
     * @return ProfileInterface|null $profile
     */
    public function retrieve($identifier)
    {
        $profileData = $this->collection->findOne([
            'identifier' => $identifier,
        ]);

        if($profileData !== null) {
            return $this->createProfileFromProfileData($profileData['profile']);
        }

        return null;
    }

    /**
     * @param  ProfileInterface            $profile
     *
     * @return PersistenceHandlerInterface $this
     */
    public function persist(ProfileInterface $profile)
    {
        // This is messed up, but this is finally compatible with XHGui, which is more important to me now.
        // Find a way to abstract this nicely! BUT FIRST! Release time! YEAH! (I am _SO_ gonna regret this...)
        $profileArray = $profile->toArray();
        $serverData = $profileArray['serverData'];

        $requestTime = isset($serverData['REQUEST_TIME']) ? $serverData['REQUEST_TIME'] : time();
        $requestTimeFloat = isset($serverData['REQUEST_TIME_FLOAT']) ? $serverData['REQUEST_TIME_FLOAT'] : microtime(true);
        $timeParts = explode('.', $requestTimeFloat);
        if(!isset($timeParts[1])) {
            $timeParts[1] = 0;
        }

        $scriptName = isset($serverData['SCRIPT_NAME']) ? $serverData['SCRIPT_NAME'] : '__unknown__';
        $uri = isset($serverData['REQUEST_URI']) ? $serverData['REQUEST_URI'] : $scriptName;

        $mongoData = array(
            'identifier' => $profile->getIdentifier(),
            'profile' => $profileArray['profileData'],
            'meta' => array(
                'url' => $uri,
                'SERVER' => $profileArray['serverData'],
                'get' => array(),
                'env' => array(),
                'simple_url' => $uri,
                'request_ts' => new MongoDate($requestTime),
                'request_ts_micro' => new MongoDate($timeParts[0], $timeParts[1]),
                'request_date' => date('Y-m-d', $requestTime),
            )
        );

        $this->collection->insert($mongoData);

        return $this;
    }
}
