<?php

/**
 * XhprofAdapterTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;

/**
 * Class XhprofAdapterTest
 *
 * @package Link0\Profiler\ProfilerAdapter
 */
class XhprofAdapterTest extends \PHPUnit_Framework_TestCase
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
        $this->profilerAdapter = new XhprofAdapter();
        $this->assertInstanceOf('Link0\Profiler\ProfilerAdapter\XhprofAdapter', $this->profilerAdapter);
    }

    /**
     * Asserts the extensionName
     */
    public function testExtensionName()
    {
        $this->assertEquals('xhprof', $this->profilerAdapter->getExtensionName());
    }

    /**
     * Tests the outputDirectory property
     */
    public function testGetFileOutputDirectory()
    {
        $this->assertEquals(ini_get('xhprof.output_dir'), $this->profilerAdapter->getFileOutputDirectory());
    }

    /**
     * Tests the complete implementation including assertion on running for Xhprof
     */
    public function testXhprofImplementationIfExtensionLoaded()
    {
        if($this->profilerAdapter->isExtensionLoaded()) {
            function xhprof_start() {}
            function xhprof_stop() { return array(); }
        }

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