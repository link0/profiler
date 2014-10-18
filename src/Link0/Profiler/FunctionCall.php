<?php

/**
 * FunctionCall.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

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
     * @var int $time
     */
    protected $time;

    /**
     * @var int $cpuTime
     */
    protected $cpuTime;

    /**
     * @var int $memoryUsage
     */
    protected $memoryUsage;

    /**
     * @var int $peakMemoryUsage
     */
    protected $peakMemoryUsage;

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
        $this->time = $time;
        $this->cpuTime = $cpuTime;
        $this->memoryUsage = $memoryUsage;
        $this->peakMemoryUsage = $peakMemoryUsage;
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
     * @param  int   $cpuTime
     * @return $this
     */
    public function setCpuTime($cpuTime)
    {
        $this->cpuTime = $cpuTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpuTime()
    {
        return $this->cpuTime;
    }

    /**
     * @param  int   $memoryUsage
     * @return $this
     */
    public function setMemoryUsage($memoryUsage)
    {
        $this->memoryUsage = $memoryUsage;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * @param  int   $peakMemoryUsage
     * @return $this
     */
    public function setPeakMemoryUsage($peakMemoryUsage)
    {
        $this->peakMemoryUsage = $peakMemoryUsage;

        return $this;
    }

    /**
     * @return int
     */
    public function getPeakMemoryUsage()
    {
        return $this->peakMemoryUsage;
    }

    /**
     * @param  int   $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string[] $data
     */
    public function toData()
    {
        $data = array();
        foreach (get_object_vars($this) as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }
}
