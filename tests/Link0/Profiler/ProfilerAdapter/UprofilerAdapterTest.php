<?php

/**
 * UprofilerAdapterTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;

/**
 * Class UprofilerAdapterTest
 *
 * @package Link0\Profiler\ProfilerAdapter
 */
class UprofilerAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Link0\Profiler\ProfilerAdapterInterface $profilerAdapter
     */
    protected $profilerAdapter;

    /**
     * Sets up the object for testing
     */
    public function setUp()
    {
        $this->profilerAdapter = new UprofilerAdapter();
        $this->assertInstanceOf('Link0\Profiler\ProfilerAdapter\UprofilerAdapter', $this->profilerAdapter);
    }

    /**
     * Asserts the extensionName
     */
    public function testExtensionName()
    {
        $this->assertEquals('uprofiler', $this->profilerAdapter->getExtensionName());
    }

    /**
     * Tests the outputDirectory property
     */
    public function testGetFileOutputDirectory()
    {
        $this->assertEquals(ini_get('uprofiler.output_dir'), $this->profilerAdapter->getFileOutputDirectory());
    }

    /**
     * Tests the complete implementation including assertion on running for Uprofiler
     */
    public function testUprofilerImplementationIfExtensionLoaded()
    {
        if($this->profilerAdapter->isExtensionLoaded()) {
            $this->assertFalse($this->profilerAdapter->isRunning());
            $this->profilerAdapter->start();
            $this->assertTrue($this->profilerAdapter->isRunning());
            $this->profilerAdapter->start();
            $this->assertTrue($this->profilerAdapter->isRunning());

            $this->profilerAdapter->stop();
            $this->assertFalse($this->profilerAdapter->isRunning());
            $this->profilerAdapter->stop();
            $this->assertFalse($this->profilerAdapter->isRunning());
        }
    }
}