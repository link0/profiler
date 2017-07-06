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
     * @var string
     */
    private $namespace;

    /**
     * @param MongoClientInterface $client
     * @param string $databaseName
     * @param string $collection
     */
    public function __construct(MongoClientInterface $client, $databaseName = 'xhprof', $collection = 'results')
    {
        parent::__construct();

        $this->client = $client;
        $this->namespace = $databaseName . '.' . $collection;
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
        return $this->client->executeQuery($this->namespace, array());
    }

    /**
     * @param  string $identifier
     *
     * @return ProfileInterface|null $profile
     */
    public function retrieve($identifier)
    {
        $profiles = $this->client->executeQuery(
            $this->namespace,
            array(
                'identifier' => $identifier,
            ),
            array(
                'limit' => 1,
            ));

        $profileData = reset($profiles);

        if ($profileData === false) {
            return null;
        }

        if ($profileData !== null) {
            return $this->createProfileFromProfileData($profileData['profile']);
        }

        return null;
    }

    /**
     * @param  ProfileInterface $profile
     *
     * @return PersistenceHandlerInterface $this
     */
    public function persist(ProfileInterface $profile)
    {
        // This is messed up, but this is finally compatible with XHGui, which is more important to me now.
        // Find a way to abstract this nicely! BUT FIRST! Release time! YEAH! (I am _SO_ gonna regret this...)
        $mongoRequestDateTime = $this->getMongoRequestDateTime($profile);
        $uri = $this->getMongoUri($profile);

        $mongoDocument = array(
            'identifier' => $profile->getIdentifier(),
            'profile' => $profile->getProfileData(),
            'meta' => array(
                'url' => $uri,
                'SERVER' => $profile->getServerData(),
                'get' => array(),
                'env' => array(),
                'simple_url' => $uri,
                'request_ts' => $mongoRequestDateTime,
                'request_ts_micro' => $this->getMongoRequestTimestamp($profile),
                'request_date' => $mongoRequestDateTime->toDateTime()->format('Y-m-d'),
            )
        );

        $this->client->insert($this->namespace, $mongoDocument);

        return $this;
    }

    /**
     * @param ProfileInterface $profile
     * @return \MongoDB\BSON\UTCDateTime
     */
    private function getMongoRequestDateTime(ProfileInterface $profile)
    {
        $serverData = $profile->getServerData();

        $requestTimeStamp = isset($serverData['REQUEST_TIME']) ? $serverData['REQUEST_TIME'] : time();
        $requestTime = new \DateTime();
        $requestTime->setTimestamp($requestTimeStamp);

        // NOTE: Even though my local documentation for this class says you cannot pass a DateTimeInterface,
        //  this is actually possible according to php.net. Actually testing it verifies this.
        return new \MongoDB\BSON\UTCDateTime($requestTime);
    }

    /**
     * @param ProfileInterface $profile
     * @return \MongoDB\BSON\Timestamp
     */
    private function getMongoRequestTimestamp(ProfileInterface $profile)
    {
        $requestTimeFloat = isset($serverData['REQUEST_TIME_FLOAT']) ? $serverData['REQUEST_TIME_FLOAT'] : microtime(true);
        $timeParts = explode('.', $requestTimeFloat);
        if (!isset($timeParts[1])) {
            $timeParts[1] = 0;
        }

        return new \MongoDB\BSON\Timestamp($timeParts[1], $timeParts[0]);
    }

    /**
     * @param ProfileInterface $profile
     * @return string
     */
    private function getMongoUri(ProfileInterface $profile)
    {
        $serverData = $profile->getServerData();
        $scriptName = isset($serverData['SCRIPT_NAME']) ? $serverData['SCRIPT_NAME'] : '__unknown__';
        $uri = isset($serverData['REQUEST_URI']) ? $serverData['REQUEST_URI'] : $scriptName;

        return $uri;
    }
}
