<?php

namespace Link0\Profiler;

class XhprofTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorArguments()
    {
        $flags = 31337;
        $options = array(
            'Foo' => 'Bar'
        );
        $xhprof = new Xhprof(null, $flags, $options);

        $this->assertSame($flags, $xhprof->getFlags());
        $this->assertSame($options, $xhprof->getOptions());
    }

    public function testConstructorDefaultArguments()
    {
        $xhprof = new Xhprof();
        $this->assertEquals(XHPROF_FLAGS_MEMORY, $xhprof->getFlags());
        $this->assertEquals(array(), $xhprof->getOptions());
    }

    public function testCanStart()
    {
        $xhprof = new Xhprof();
        $xhprof->start();
    }

    public function testStopReturnsProfile()
    {
        $xhprof = new Xhprof();
        $profile = $xhprof->stop();
        $this->assertInstanceOf('Link0\Profiler\Profile', $profile);
    }

    public function testHasFlag()
    {
        $xhprof = new Xhprof();
        $this->assertTrue($xhprof->hasFlag(XHPROF_FLAGS_MEMORY));
        $this->assertFalse($xhprof->hasFlag(XHPROF_FLAGS_CPU));
    }
}