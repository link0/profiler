<?php

/**
 * MongoClientInterface.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler\MongoDbHandler;

/**
 * Interface to fake a MongoClient and have a seperate interface to talk to
 *
 * @package Link0\Profiler\PersistenceHandler\MongoDbHandler
 */
interface MongoClientInterface
{
    /**
     * @param string $namespace
     * @param array|object $filter
     * @param array $queryOptions
     * @return string[]
     *
     *
     */
    public function executeQuery($namespace, $filter, $queryOptions = array());


}
