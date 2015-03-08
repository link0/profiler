<?php

/**
 * ProfilerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Class ProfilerTest
 *
 * @package Link0\Profiler
 */
class ProfilerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorArgumentsPassedToProfilerAdapter()
    {
        $flags = 31337;
        $options = array(
            'Foo' => 'Bar',
            'ignored_functions' => array()
        );

        $profiler = new Profiler(null, $flags, $options);

        $this->assertSame($flags, $profiler->getProfilerAdapter()->getFlags());

        // Arrays are not identical since they are merged with internal ignore functions
        foreach($options['ignored_functions'] as $ignoredFunction) {
            $this->assertTrue(in_array($ignoredFunction, $profiler->getProfilerAdapter()->getOptions()));
        }
    }

    public function testCanStart()
    {
        $profiler = new Profiler();
        $this->assertFalse($profiler->isRunning());
        $profiler->start();
        $this->assertTrue($profiler->isRunning());
    }

    public function testWontCanStartOnCookieWithoutCookie()
    {
        $profiler = new Profiler();
        $this->assertFalse($profiler->isRunning());
        $this->assertSame($profiler, $profiler->startOnCookie('foo'));
        $this->assertFalse($profiler->isRunning());
    }

    public function testStartOnCookie()
    {
        $_COOKIE['_profiler'] = true;
        $profiler = new Profiler();
        $this->assertFalse($profiler->isRunning());
        $profiler->startOnCookie('1');
        $this->assertTrue($profiler->isRunning());
    }

    public function testStopReturnsProfile()
    {
        $profiler = new Profiler();
        $this->assertFalse($profiler->isRunning());
        $profile = $profiler->stop();
        $this->assertInstanceOf('Link0\Profiler\Profile', $profile);
    }

    public function testApplicationDataFromProfilerToProfile()
    {
        $applicationData = array(
            'foo' => 'bar',
            1337 => 7331,
        );

        $profiler = new Profiler();
        $profiler->setApplicationData($applicationData);
        $profiler->start();
        $profile = $profiler->stop();

        $this->assertEquals($applicationData, $profile->getApplicationData());
    }

    public function testServerDataFromProfilerToProfile()
    {
        $serverData = array(
            'foo' => 'bar',
        ); // Currently, $_SERVER data is hardcoded empty array, @see Profiler::stop:r256

        $profiler = new Profiler();
        $profiler->start();
        $profile = $profiler->stop();

        // Override $_SERVER for tests
        $profile->setServerData($serverData);

        $this->assertEquals($serverData, $profile->getServerData());
    }

    public function testSetProfilerAdapter()
    {
        $profiler = new Profiler();
        $profilerAdapter = new ProfilerAdapter\NullAdapter();
        $this->assertNotSame($profilerAdapter, $profiler->getProfilerAdapter());
        $profiler->setProfilerAdapter($profilerAdapter);
        $this->assertSame($profilerAdapter, $profiler->getProfilerAdapter());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage No valid profilerAdapter found. Did you forget to install an extension?
     */
    public function testNoPreferredProfilerAdapterCanBeFoundWhenNoneSet()
    {
        $profiler = new Profiler();
        $profiler->setPreferredProfilerAdapters(array());
        $profiler->getPreferredProfilerAdapter();
    }

    public function testSetPreferredAdapters()
    {
        $nullAdapter = new ProfilerAdapter\NullAdapter();
        $profiler = new Profiler();
        $profiler->setPreferredProfilerAdapters(array($nullAdapter));
        $this->assertSame($nullAdapter, $profiler->getPreferredProfilerAdapters()[0]);
    }

    public function testSetCustomProfilerFactory()
    {
        $profileFactory = new ProfileFactory();
        $profiler = new Profiler();
        $this->assertNotSame($profileFactory, $profiler->getProfileFactory());

        $profiler->setProfileFactory($profileFactory);
        $this->assertSame($profileFactory, $profiler->getProfileFactory());
    }
}