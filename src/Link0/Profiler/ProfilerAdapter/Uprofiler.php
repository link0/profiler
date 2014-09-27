<?php

/**
 * Uprofiler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;
use Link0\Profiler\ProfilerAdapter;

/**
 * Uprofiler implementation
 *
 * @package Link0\Profiler\Profiler
 */
final class Uprofiler extends ProfilerAdapter
{
    /**
     * @return \Link0\Profiler\ProfilerAdapter
     */
    public function start()
    {
        parent::start();
        return uprofiler_enable($this->getFlags(), $this->getOptions());
    }

    /**
     * @return array $data
     */
    public function stop()
    {
        parent::stop();
        return uprofiler_disable();
    }

    /**
     * @return string $extensionName
     */
    public function getExtensionName()
    {
        return 'uprofiler';
    }

    /**
     * @return false|string $outputDirectory The php.ini value, or false if not set
     */
    public function getFileOutputDirectory()
    {
        return ini_get('uprofiler.output_dir');
    }
}