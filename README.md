Link0\Profiler
==============
[![Latest Stable Version](https://poser.pugx.org/link0/profiler/v/stable.svg)](https://packagist.org/packages/link0/profiler)
[![Total Downloads](https://poser.pugx.org/link0/profiler/downloads.svg)](https://packagist.org/packages/link0/profiler)
[![License](https://poser.pugx.org/link0/profiler/license.svg)](https://packagist.org/packages/link0/profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/link0/profiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/link0/profiler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/link0/profiler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/link0/profiler/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/link0/profiler/badges/build.png?b=master)](https://scrutinizer-ci.com/g/link0/profiler/build-status/master)

**Link0/Profiler** as a layer over XHProf profiling, and persisting profiles for later analysis.

The code is quite new, so please report any bugs if you encounter them, even though unit-tests should cover 100% of the code.

All ideas are welcome, and contributors as well.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1151c973-75c3-41ea-b944-1e677e41862a/big.png)](https://insight.sensiolabs.com/projects/1151c973-75c3-41ea-b944-1e677e41862a)

Requirements
============
PHP 5.4 is required but using the latest version of PHP is highly recommended

One of the following profilers to do actual profiling
* [XHProf](http://pecl.php.net/package/xhprof)
* [Uprofiler](https://github.com/FriendsOfPHP/uprofiler)
* [Tideways](https://github.com/tideways/php-profiler-extension)

Installation
============
To add **Link0/Profiler** as a local, per-project dependency to your project, simply require the dependency `link0/profiler` with composer.

    composer require "link0/profiler" "~1.0"

There is also a Symfony2 bundle available, see [Link0/ProfilerBundle](https://github.com/link0/ProfilerBundle). To install it, use the following composer package

    composer require "link0/profiler-bundle" "~1.0"

To install XHProf on your machine

    pecl install -f xhprof

or 

    apt-get install php5-xhprof

or

    # If you have the josegonzalez/homebrew-php formulae tapped, install them with brew.
    # Change your version accordingly
    brew install php55-xhprof

Quick setup with XHGui
===============
To get started with this profiler package and XHGui, setup XHGui to listen to your MongoDB instance.

From every project that you want to profile, and aggregate the results to the centralized server, setup the following config:

```php
    $connectionAddress = 'mongodb://mongodb.example.com:27017';
    $mongoClient = new \Link0\Profiler\PersistenceHandler\MongoDbHandler\MongoClient($connectionAddress);
    $persistenceHandler = new \Link0\Profiler\PersistenceHandler\MongoDbHandler($mongoClient);
    $profiler = new \Link0\Profiler\Profiler($persistenceHandler);
    $profiler->start();
```

More in-depth
===============
The library is all about the [Profiler](https://github.com/link0/profiler/blob/master/src/Link0/Profiler/Profiler.php), you want to instantiate that and let it do it's magic

```php
$profiler = new \Link0\Profiler\Profiler();
$profiler->start();
print_r($profiler->stop());
```

If you want to start profiling using a browser based tool like [XHProf Helper](https://chrome.google.com/webstore/detail/xhprof-helper/adnlhmmjijeflmbmlpmhilkicpnodphi?hl=en), You can use this method
```php
$profiler = new \Link0\Profiler\Profiler();
$profiler->startOn(@$_COOKIE['_profiler']);
// or
$profiler->startOn(@$_COOKIE['XHProf_Profile']);
```

If you want to store the results, you can pass a [PersistenceHandler](https://github.com/dennisdegreef/profiler/tree/cleanup/src/Link0/Profiler/PersistenceHandler) object to the Profiler

```php
$persistenceHandler = new \Link0\Profiler\PersistenceHandler\MemoryHandler();
$profiler = new \Link0\Profiler\Profiler($persistenceHandler);
```

This way, the results are stored in memory, may not be that convienient, but can be nice to play around with.

There is also an implementation to store profiles on the filesystem, using the [Flysystem](http://flysystem.thephpleague.com/) library.

```php
$filesystemAdapter = new \League\Flysystem\Adapter\Local('/tmp/profiler');
$filesystem = new \League\Flysystem\Filesystem($filesystemAdapter);
$persistenceHandler = new \Link0\Profiler\PersistenceHandler\FilesystemHandler($filesystem);
$profiler = new \Link0\Profiler\Profiler($persistenceHandler);
```
