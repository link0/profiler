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
final class MongoClient extends \MongoClient implements MongoClientInterface
{
}
