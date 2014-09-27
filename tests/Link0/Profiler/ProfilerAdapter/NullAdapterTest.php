<?php

/**
 * NullAdapterTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;

/**
 * NullAdapterTest
 */
class NullAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @const SOME_BITFLAG Just for testing the flags implementation
     */
    const SOME_BITFLAG = 8;

    /**
     * @const SOME_OTHER_BITFLAG Just for testing the flags implementation
     */
    const SOME_OTHER_BITFLAG = 64;

    /**
     * @const SOME_BITFLAG_IT_SHOULD_NOT_HAVE Just for testing the flags implementation
     */
    const SOME_BITFLAG_IT_SHOULD_NOT_HAVE = 256;

    /**
     * @var int $flags
     */
    protected $flags = 0;

    /**
     * @var array $options
     */
    protected $options = array();

    /**
     * @var \Link0\Profiler\ProfilerAdapterInterface $profilerAdapter
     */
    protected $profilerAdapter;

    /**
     * Sets up the object for testing
     */
    public function setUp()
    {
        $this->flags = (self::SOME_BITFLAG | self::SOME_OTHER_BITFLAG);
        $this->options = array(
            'foo' => 'bar'
        );

        $this->profilerAdapter = new NullAdapter($this->flags, $this->options);
        $this->assertTrue($this->profilerAdapter->isExtensionLoaded());
        $this->assertInstanceOf('Link0\Profiler\ProfilerAdapter\NullAdapter', $this->profilerAdapter);
    }

    /**
     * Asserts the fake null extension is needed for this adapter
     */
    public function testExtensionName()
    {
        $this->assertEquals('null', $this->profilerAdapter->getExtensionName());
    }

    /**
     * Tests the default output directory getter
     */
    public function testGetFileOutputDirectory()
    {
        $this->assertFalse($this->profilerAdapter->getFileOutputDirectory());
    }

    /**
     * Asserts that the flags property is set properly
     */
    public function testGetFlags()
    {
        $this->assertSame($this->flags, $this->profilerAdapter->getFlags());
    }

    /**
     * Tests some bitflag operations on the $flags property
     */
    public function testHasFlag()
    {
        $this->assertTrue($this->profilerAdapter->hasFlag(self::SOME_BITFLAG));
        $this->assertTrue($this->profilerAdapter->hasFlag(self::SOME_OTHER_BITFLAG));
        $this->assertFalse($this->profilerAdapter->hasFlag(self::SOME_BITFLAG_IT_SHOULD_NOT_HAVE));
    }

    /**
     * Asserts the options is set correctly
     */
    public function testGetOptions()
    {
        $this->assertSame($this->options, $this->profilerAdapter->getOptions());
    }

    /**
     * Tests that the isRunning flag is handled properly
     */
    public function testUprofilerImplementationIfExtensionLoaded()
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