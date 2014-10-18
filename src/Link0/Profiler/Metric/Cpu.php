<?php

/**
 * Cpu.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\Metric;
use Link0\Profiler\Metric;

/**
 * CPU statistics
 *
 * @package Link0\Profiler\Metric
 */
final class Cpu extends Metric
{
    /**
     * @var int $wall Wall/wait time in milliseconds
     */
    private $wall;

    /**
     * @var int $cpuTime CPU time in milliseconds
     */
    private $cpuTime;

    /**
     * @param int $wall
     * @param int $cpuTime
     */
    public function __construct($wall, $cpuTime)
    {
        $this->wall = $wall;
        $this->cpuTime = $cpuTime;
    }

    /**
     * @return int $wall
     */
    public function getWall()
    {
        return $this->wall;
    }

    /**
     * @return int $cpuTime
     */
    public function getCpuTime()
    {
        return $this->cpuTime;
    }
}