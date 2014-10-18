<?php

/**
 * FunctionCall.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

use Link0\Profiler\Metric\Cpu;
use Link0\Profiler\Metric\Memory;

/**
 * Models a function call
 *
 * @package Link0\Profiler
 */
final class FunctionCall
{
    /**
     * @var string $functionName
     */
    protected $functionName;

    /**
     * @var string $caller
     */
    protected $caller;

    /**
     * @var int $callCount
     */
    protected $callCount;

    /**
     * @var Cpu $cpu
     */
    protected $cpu;

    /**
     * @var Memory $memory
     */
    protected $memory;

    /**
     * @param string $functionName
     * @param string $caller
     * @param int    $callCount
     * @param int    $time
     * @param int    $cpuTime
     * @param int    $memoryUsage
     * @param int    $peakMemoryUsage
     */
    public function __construct($functionName, $caller, $callCount, $time, $cpuTime, $memoryUsage, $peakMemoryUsage)
    {
        $this->functionName = $functionName;
        $this->caller = $caller;
        $this->callCount = $callCount;
        $this->cpu = new Cpu($time, $cpuTime);
        $this->memory = new Memory($memoryUsage, $peakMemoryUsage);
    }

    /**
     * @param  string $functionName
     * @return $this
     */
    public function setFunctionName($functionName)
    {
        $this->functionName = $functionName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }

    /**
     * @param  string $caller
     * @return $this
     */
    public function setCaller($caller)
    {
        $this->caller = $caller;

        return $this;
    }

    /**
     * @return string
     */
    public function getCaller()
    {
        return $this->caller;
    }

    /**
     * @param  int   $callCount
     * @return $this
     */
    public function setCallCount($callCount)
    {
        $this->callCount = $callCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallCount()
    {
        return $this->callCount;
    }

    /**
     * @param  Cpu $cpu
     * @return FunctionCall $this
     */
    public function setCpu(Cpu $cpu)
    {
        $this->cpu = $cpu;

        return $this;
    }

    /**
     * @return Cpu
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * @param  Memory $memory
     * @return FunctionCall $this
     */
    public function setMemory(Memory $memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * @return Memory $memory
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @return string[] $data
     */
    public function toData()
    {
        $data = array();
        foreach (get_object_vars($this) as $key => $value) {
            switch($key) {
                case 'cpu':
                    $data['time'] = $value->getWall();
                    $data['cpuTime'] = $value->getCpuTime();
                    break;
                case 'memory':
                    $data['memoryUsage'] = $value->getMemoryUsage();
                    $data['peakMemoryUsage'] = $value->getPeakMemoryUsage();
                    break;
                default:
                    $data[$key] = $value;
            }
        }

        return $data;
    }
}
