<?php

/**
 * FunctionCallTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;
use Link0\Profiler\Metric\Cpu;
use Link0\Profiler\Metric\Memory;

/**
 * Class FunctionCallTest
 *
 * @package Link0\Profiler
 */
class FunctionCallTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the constructor and all parameters as properties with getters
     */
    public function testConstructorArguments()
    {
        $functionCall = new FunctionCall('foo', 'bar', 1, 2, 3, 4, 5);
        $this->assertEquals('foo', $functionCall->getFunctionName());
        $this->assertEquals('bar', $functionCall->getCaller());
        $this->assertEquals(1, $functionCall->getCallCount());
        $this->assertEquals(2, $functionCall->getCpu()->getWall());
        $this->assertEquals(3, $functionCall->getCpu()->getCpuTime());
        $this->assertEquals(4, $functionCall->getMemory()->getMemoryUsage());
        $this->assertEquals(5, $functionCall->getMemory()->getPeakMemoryUsage());
    }

    /**
     * Testing all setters and getters have similar values
     */
    public function testSetters()
    {
        $functionCall = new FunctionCall('bar', 'foo', 5, 4, 3, 2, 1);

        $functionCall->setFunctionName('foo');
        $this->assertEquals('foo', $functionCall->getFunctionName());

        $functionCall->setCaller('bar');
        $this->assertEquals('bar', $functionCall->getCaller());

        $functionCall->setCallCount(1);
        $this->assertEquals(1, $functionCall->getCallCount());

        $cpu = new Cpu(1234, 4321);
        $functionCall->setCpu($cpu);
        $this->assertSame($cpu, $functionCall->getCpu());

        $memory = new Memory(1234, 4321);
        $functionCall->setMemory($memory);
        $this->assertSame($memory, $functionCall->getMemory());
    }
}