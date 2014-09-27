<?php

/**
 * Xhprof.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;
use Link0\Profiler\ProfilerAdapter;

/**
 * Xhprof class implements the xhprof-extension
 *
 * @package Link0\Profiler
 */
final class Xhprof extends ProfilerAdapter
{
    /**
     * Starts the profiling
     *
     * @return ProfilerAdapter $this
     * @throws Exception
     */
    public function start()
    {
        parent::start();
        xhprof_enable($this->getFlags(), $this->getOptions());
        return $this;
    }

    /**
     * Stops the profiling and triggers an event with the result data
     *
     * @return array $data
     */
    public function stop()
    {
        parent::stop();
        return xhprof_disable();
    }

    /**
     * @return string
     */
    public function getExtensionName()
    {
        return 'xhprof';
    }

    /**
     * @return string|false The output directory specified in the php.ini configuration
     */
    public function getFileOutputDirectory()
    {
        return ini_get('xhprof.output_dir');
    }
}