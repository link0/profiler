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

    /**
     * @param string $namespace
     * @param array|object $document
     * @param array|null $options
     * @return bool Whether or not the write was acknowledged
     *
     * TODO: Any sort of feedback from insert was never actually processed before.
     *  It would be prettier if it was somehow.
     *
     * TODO: The driver docs I have locally state a different set of possible exceptions than
     *  the documentation on php.net - figuring out what is actually going to happen
     *  would be preferred.
     *
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws \MongoDB\Driver\Exception\AuthenticationException if authentication is needed and fails
     * @throws \MongoDB\Driver\Exception\ConnectionException if connection to the server fails for other then authentication reasons
     * @throws \MongoDB\Driver\Exception\InvalidArgumentException on argument parsing errors
     * @throws \MongoDB\Driver\Exception\BulkWriteException on any write failure (e.g. write error, failure to apply a write concern)
     * @throws \MongoDB\Driver\Exception\RuntimeException on other errors
     */
    public function insert($namespace, $document, $options = null)
    {
        if ($options === null) {
            $options = array(
                'ordered' => false,
            );
        }

        $bulkWrite = new \MongoDB\Driver\BulkWrite($options);

        // NOTE: This function returns a \MongoDB\BSON\ObjectID if the document contained no
        //  ID. This is currently not used anywhere, but the interface will need refactoring
        //  if it is necessary in any place.
        $bulkWrite->insert($document);

        $writeResult = $this->driverManager->executeBulkWrite($namespace, $bulkWrite);

        return $writeResult->isAcknowledged();
    }
}
