<?php

namespace Link0\Profiler\ProfilerAdapter;

function tideways_enable($flags = 0, $options = array()) {
    return true;
}

function tideways_disable() {
    return array();
}

/**
 * Class TidewaysAdapterTest
 *
 * @package Link0\Profiler\ProfilerAdapter
 */
class TidewaysAdapterTest extends \PHPUnit_Framework_TestCase
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
        $this->profilerAdapter = new TidewaysAdapter();
        $this->assertInstanceOf('Link0\Profiler\ProfilerAdapter\TidewaysAdapter', $this->profilerAdapter);
    }

    /**
     * Asserts the extensionName
     */
    public function testExtensionName()
    {
        $this->assertEquals('tideways', $this->profilerAdapter->getExtensionName());
    }

    /**
     * Tests the outputDirectory property
     */
    public function testGetFileOutputDirectory()
    {
        $this->assertEquals(ini_get('tideways.output_dir'), $this->profilerAdapter->getFileOutputDirectory());
    }

    /**
     * Tests the complete implementation including assertion on running for Tideways
     */
    public function testTidewaysImplementationIfExtensionLoaded()
    {
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
