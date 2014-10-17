<?php

/**
 * NullAdapter.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\ProfilerAdapter;

use Link0\Profiler\ProfilerAdapter;

/**
 * NullAdapter class fakes an implementation
 *
 * @package Link0\Profiler
 */
final class NullAdapter extends ProfilerAdapter
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

        return array();
    }

    /**
     * @return string
     */
    public function getExtensionName()
    {
        return 'null';
    }

    /**
     * Fake it to make it
     *
     * @return bool $isExtensionLoaded
     */
    public function isExtensionLoaded()
    {
        return true;
    }
}
