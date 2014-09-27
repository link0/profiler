<?php

/**
 * UprofilerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;

/**
 *
 */
class UprofilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Link0\Profiler\ProfilerAdapterInterface $profilerAdapter
     */
    protected $profilerAdapter;

    public function setUp()
    {
        $this->profilerAdapter = new Uprofiler();
        $this->assertInstanceOf('Link0\Profiler\ProfilerAdapter\Uprofiler', $this->profilerAdapter);
    }

    public function testExtensionName()
    {
        $this->assertEquals('uprofiler', $this->profilerAdapter->getExtensionName());
    }

    public function testGetFileOutputDirectory()
    {
        $this->assertEquals(ini_get('uprofiler.output_dir'), $this->profilerAdapter->getFileOutputDirectory());
    }

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