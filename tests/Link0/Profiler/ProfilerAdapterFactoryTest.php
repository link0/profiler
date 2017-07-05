<?php
namespace Link0\Profiler;

/*
* @package Link0\Profiler
*/
class ProfilerAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage No valid profilerAdapter found. Did you forget to install an extension?
     */
    public function testNoPossibleProfilerAdapterCanBeFoundWhenNoneSet()
    {
        $factory = new ProfilerAdapterFactory();
        $factory->setPossibleProfilerAdapters(array());
        $factory->create();
    }

    public function testSetPossibleAdapters()
    {
        $nullAdapter = new ProfilerAdapter\NullAdapter();
        $factory = new ProfilerAdapterFactory();
        $factory->setPossibleProfilerAdapters(array($nullAdapter));
        $this->assertSame($nullAdapter, $factory->create());
    }

}
