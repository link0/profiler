Link0\Profiler
==============
[![Latest Stable Version](https://poser.pugx.org/link0/profiler/v/stable.svg)](https://packagist.org/packages/link0/profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/link0/profiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/link0/profiler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/link0/profiler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/link0/profiler/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/link0/profiler/badges/build.png?b=master)](https://scrutinizer-ci.com/g/link0/profiler/build-status/master)

**Link0/Profiler** as a layer over XHProf profiling, and persisting profiles for later analysis.

The code is quite new, so please report any bugs if you encounter them, even though unit-tests should cover 100% of the code.

All ideas are welcome, and contributors as well.

Requirements
============
* PHP 5.4 is required but using the latest version of PHP is highly recommended
* [XHProf](http://pecl.php.net/package/xhprof) or [Uprofiler](https://github.com/FriendsOfPHP/uprofiler) is required to do actual profiling

Installation
============
To add Link0/Profiler as a local, per-project dependency to your project, simply add a dependency on `link0/profiler` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a dependency on Link0/Profiler:

    {
        "require": {
            "link0/profiler": "dev-master"
        }
    }

Getting started
===============
The library is all about the [Profiler](https://github.com/link0/profiler/blob/master/src/Link0/Profiler/Profiler.php), you want to instantiate that and let it do it's magic

```php
$profiler = new \Link0\Profiler\Profiler();
$profiler->start();
print_r($profiler->stop());
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

Future
=====
- Webapplication as frontend with aggregation of multiple profiles
- Add more PersistenceHandlers for flexability

