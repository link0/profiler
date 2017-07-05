<?php

namespace Link0\Profiler\ProfilerAdapter;

use Link0\Profiler\ProfilerAdapter;

/**
 * Tideways class implements the tideways-extension
 *
 * @package Link0\Profiler
 */
final class TidewaysAdapter extends ProfilerAdapter
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
        tideways_enable($this->getFlags(), $this->getOptions());

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

        return tideways_disable();
    }

    /**
     * @return string
     */
    public function getExtensionName()
    {
        return 'tideways';
    }

    /**
     * @return string The output directory specified in the php.ini configuration
     */
    public function getFileOutputDirectory()
    {
        return ini_get('tideways.output_dir');
    }
}
