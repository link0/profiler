<?php

/**
 * MongoClient.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler\MongoDbHandler;

/**
 * Extension of the MongoClient class to adhere to the interface we want to talk to
 *
 * @package Link0\Profiler\PersistenceHandler\MongoDbHandler
 */
final class MongoClient implements MongoClientInterface
{
    /**
     * @var \MongoDB\Driver\Manager
     */
    private $driverManager;

    public function __construct($uri = 'mongodb://127.0.0.1', $uriOptions = [], $driverOptions = [])
    {
        $this->driverManager = new \MongoDB\Driver\Manager($uri, $uriOptions, $driverOptions);
    }


    /**
     * @param string $namespace
     * @param array|object $filter
     * @param array $queryOptions
     * @return string[]
     *
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws \MongoDB\Driver\Exception\AuthenticationException if authentication is needed and fails
     * @throws \MongoDB\Driver\Exception\ConnectionException if connection to the server fails for other then authentication reasons
     * @throws \MongoDB\Driver\Exception\RuntimeException on other errors (invalid command, command arguments, ...)
     */
    public function executeQuery($namespace, $filter, $queryOptions = array())
    {
        $query = new \MongoDB\Driver\Query($filter, $queryOptions);

        return iterator_to_array($this->driverManager->executeQuery($namespace, $query));
    }
}
