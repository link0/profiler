<?php

include(dirname(__FILE__) . '/vendor/autoload.php');

$filesystemAdapter = new \League\Flysystem\Adapter\Local('/tmp/profiler');
$filesystem = new \League\Flysystem\Filesystem($filesystemAdapter);
$persistenceHandler = new \Link0\Profiler\PersistenceHandler\FilesystemHandler($filesystem);
$profiler = new \Link0\Profiler\Profiler($persistenceHandler);
$profiler->start();

print_r($profiler->stop());
