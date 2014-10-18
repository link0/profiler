<?php

/**
 * Memory.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\Metric;

use Link0\Profiler\Metric;

/**
 * Memory statistics
 *
 * @package Link0\Profiler\Metric
 */
final class Memory extends Metric
{
    /**
     * @var int $memoryUsage Memory usage in bytes
     */
    private $memoryUsage;

    /**
     * @var int $peakMemoryUsage Peak memory usage in bytes
     */
    private $peakMemoryUsage;

    /**
     * @param int $memoryUsage Memory usage in bytes
     * @param int $peakMemoryUsage Peak memory usage in bytes
     */
    public function __construct($memoryUsage, $peakMemoryUsage)
    {
        $this->memoryUsage = $memoryUsage;
        $this->peakMemoryUsage = $peakMemoryUsage;
    }

    /**
     * @return int $memoryUsage Memory usage in bytes
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * @return int $peakMemoryUsage Peak memory usage in bytes
     */
    public function getPeakMemoryUsage()
    {
        return $this->peakMemoryUsage;
    }
}
