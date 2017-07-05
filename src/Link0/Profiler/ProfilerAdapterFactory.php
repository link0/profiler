<?php

namespace Link0\Profiler;


class ProfilerAdapterFactory
{
    /** @var ProfilerAdapterInterface[] */
    private $possibleProfilerAdapters;

    /**
     * ProfilerAdapterFactory constructor. Extend this and add to preferredProfilerAdapters to have that adapter
     *  checked first.
     *
     * @param int   $flags   The configuration flags the adapters should be loaded with
     * @param array $options The options the adapters can parse
     */
    public function __construct($flags = null, $options = array())
    {
        if ($flags === null) {
            // Flags for XHProf and forks, adding up to consume memory and cpu statistics
            // Hardcoded to value 6, because if you have one extension, the constants of the other(s) don't exist
            $flags = 6;
        }

        $this->possibleProfilerAdapters = array(
            new ProfilerAdapter\NullAdapter($flags, $options),
            new ProfilerAdapter\XhprofAdapter($flags, $options),
            new ProfilerAdapter\UprofilerAdapter($flags, $options),
            new ProfilerAdapter\TidewaysAdapter($flags, $options),
        );
    }

    /**
     * Will reset $this->preferredProfilerAdapters to an empty array. Then adds every item in
     *  $preferredProfilerAdapters to it if it is an instance of ProfilerAdapterInterface.
     *
     * @param array $possibleProfilerAdapters
     */
    public function setPossibleProfilerAdapters($possibleProfilerAdapters)
    {
        $this->possibleProfilerAdapters = array();

        for(end($possibleProfilerAdapters); key($possibleProfilerAdapters) !== null; prev($possibleProfilerAdapters)) {
            $adapter = current($possibleProfilerAdapters);
            if ($adapter instanceof ProfilerAdapterInterface) {
                $this->possibleProfilerAdapters[] = $adapter;
            }
        }
    }

    /**
     * Iterates through $this->preferredProfilerAdapters in reverse order, returning the first adapter that reports
     *  it's extension as being loaded.
     *
     * @return ProfilerAdapter
     * @throws Exception
     */
    public function create()
    {
        for(end($this->possibleProfilerAdapters); key($this->possibleProfilerAdapters) !== null; prev($this->possibleProfilerAdapters)) {
            /** @var ProfilerAdapter $adapter */
            $adapter = current($this->possibleProfilerAdapters);

            if ($adapter->isExtensionLoaded() === true) {
                return $adapter;
            }
        }

        throw new Exception('No valid profilerAdapter found. Did you forget to install an extension?');
    }

}
