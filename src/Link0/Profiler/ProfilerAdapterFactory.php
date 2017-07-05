<?php
/**
 * @author  Tim Bazuin <krageon@gmail.com>
 */

namespace Link0\Profiler;


class ProfilerAdapterFactory
{
    /** @var ProfilerAdapterInterface[] */
    private $preferredProfilerAdapters;

    /**
     * ProfilerAdapterFactory constructor. Extend this and add to preferredProfilerAdapters to have that adapter
     *  checked first.
     *
     * @param int   $flags   The configuration flags the adapters should be loaded with
     * @param array $options The options the adapters can parse
     */
    public function __construct($flags, $options)
    {
        $this->preferredProfilerAdapters = array(
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
     * @param array $preferredProfilerAdapters
     */
    public function setPreferredProfilerAdapters($preferredProfilerAdapters)
    {
        $this->preferredProfilerAdapters = array();

        for(end($preferredProfilerAdapters); key($preferredProfilerAdapters) !== null; prev($preferredProfilerAdapters)) {
            $adapter = current($preferredProfilerAdapters);
            if ($adapter instanceof ProfilerAdapterInterface) {
                $this->preferredProfilerAdapters[] = $adapter;
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
        for(end($this->preferredProfilerAdapters); key($this->preferredProfilerAdapters) !== null; prev($this->preferredProfilerAdapters)) {
            /** @var ProfilerAdapter $adapter */
            $adapter = current($this->preferredProfilerAdapters);

            if ($adapter->isExtensionLoaded() === true) {
                return $adapter;
            }
        }

        throw new Exception('No valid profilerAdapter found. Did you forget to install an extension?');
    }

}
