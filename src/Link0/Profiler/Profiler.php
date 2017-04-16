<?php declare(strict_types=1);

/**
 * Profiler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Profiler itself
 *
 * @package Link0\Profiler
 */
final class Profiler
{
    /**
     * @var ProfilerAdapterInterface $profilerAdapter
     */
    protected $profilerAdapter;

    /**
     * @var PersistenceService $persistenceService
     */
    protected $persistenceService;

    /**
     * @var ProfilerAdapterInterface[] $profilerAdapters
     */
    protected $preferredProfilerAdapters;

    /**
     * @var ProfileFactoryInterface $profileFactory
     */
    protected $profileFactory;

    /**
     * @var array $applicationData
     */
    private $applicationData = array();

    /**
     * @param null|PersistenceHandlerInterface $persistenceHandler
     * @param null|int                         $flags
     * @param array                            $options
     */
    public function __construct(PersistenceHandlerInterface $persistenceHandler = null, $flags = null, $options = array())
    {
        if ($flags === null) {
            // Flags for XHProf and Uprofiler adding up to consume memory and cpu statistics
            // Hardcoded to value 6, because if you have either extension, the constants of the other don't exist
            $flags = 6;
        }

        $options = $this->addInternalIgnoreFunctions($options);
        $this->setDefaultPreferredProfileAdapters($flags, $options);

        $this->profilerAdapter = $this->getPreferredProfilerAdapter();
        $this->persistenceService = new PersistenceService($persistenceHandler);
        $this->profileFactory = new ProfileFactory();
    }

    /**
     * Sets default preferred profile adapters
     *
     * @param int $flags
     * @param array $options
     */
    private function setDefaultPreferredProfileAdapters($flags, $options)
    {
        $this->preferredProfilerAdapters = array(
            new ProfilerAdapter\UprofilerAdapter($flags, $options),
            new ProfilerAdapter\XhprofAdapter($flags, $options),
            new ProfilerAdapter\NullAdapter($flags, $options),
        );
    }

    /**
     * Adds internal methods for ignored_functions
     *
     * @param  array $options
     * @return array $options
     */
    private function addInternalIgnoreFunctions($options)
    {
        if(isset($options['ignored_functions']) === false) {
            $options['ignored_functions'] = array();
        }

        $options['ignored_functions'] = array_merge($options['ignored_functions'], array(
            'xhprof_disable',
            'Link0\Profiler\ProfilerAdapter\XhprofAdapter::stop',
            'Link0\Profiler\ProfilerAdapter\UprofilerAdapter::stop',
            'Link0\Profiler\ProfilerAdapter\NullAdapter::stop',
            'Link0\Profiler\ProfilerAdapter::stop',
            'Link0\Profiler\ProfilerAdapter::isRunning',
            'Link0\Profiler\Profiler::getProfilerAdapter',
            'Link0\Profiler\Profiler::getProfileFactory',
            'Link0\Profiler\Profiler::stop',
            'Link0\Profiler\Profiler::isRunning',
            'Link0\Profiler\Profiler::__destruct',
        ));

        return $options;
    }

    /**
     * @param  ProfilerAdapterInterface $profilerAdapter
     * @return Profiler                 $this
     */
    public function setProfilerAdapter(ProfilerAdapterInterface $profilerAdapter)
    {
        $this->profilerAdapter = $profilerAdapter;

        return $this;
    }

    /**
     * @return ProfilerAdapterInterface $profilingAdapter
     */
    public function getProfilerAdapter()
    {
        return $this->profilerAdapter;
    }

    /**
     * @return PersistenceService $persistenceService
     */
    public function getPersistenceService()
    {
        return $this->persistenceService;
    }

    /**
     * @param  ProfilerAdapterInterface[] $preferredProfilerAdapters
     * @return Profiler                   $this
     */
    public function setPreferredProfilerAdapters($preferredProfilerAdapters)
    {
        $this->preferredProfilerAdapters = array();
        foreach ($preferredProfilerAdapters as $preferredProfilerAdapter) {
            if (in_array('Link0\Profiler\ProfilerAdapterInterface', class_implements($preferredProfilerAdapter)) === true) {
                $this->preferredProfilerAdapters[] = $preferredProfilerAdapter;
            }
        }

        return $this;
    }

    /**
     * @return ProfilerAdapterInterface[] $preferredProfilerAdapters
     */
    public function getPreferredProfilerAdapters()
    {
        return $this->preferredProfilerAdapters;
    }

    /**
     * @throws Exception
     * @return ProfilerAdapterInterface $profilerAdapter
     */
    public function getPreferredProfilerAdapter()
    {
        /** @var ProfilerAdapterInterface $adapter */
        foreach ($this->getPreferredProfilerAdapters() as $adapter) {
            if ($adapter->isExtensionLoaded() === true) {
                return $adapter;
            }
        }

        throw new Exception('No valid profilerAdapter found. Did you forget to install an extension?');
    }

    /**
     * @param ProfileFactoryInterface $profileFactory
     * @return Profiler $this
     */
    public function setProfileFactory(ProfileFactoryInterface $profileFactory)
    {
        $this->profileFactory = $profileFactory;

        return $this;
    }

    /**
     * @return ProfileFactoryInterface $profileFactory
     */
    public function getProfileFactory()
    {
        return $this->profileFactory;
    }

    /**
     * @param array $applicationData
     *
     * @return Profiler $this
     */
    public function setApplicationData($applicationData)
    {
        $this->applicationData = $applicationData;

        return $this;
    }

    /**
     * @return array $applicationData
     */
    public function getApplicationData()
    {
        return $this->applicationData;
    }

    /**
     * Starts profiling on the specific adapter
     *
     * @return Profiler $profiler
     */
    public function start()
    {
        $this->getProfilerAdapter()->start();

        return $this;
    }

    /**
     * Starts profiling whenever this evaluation is true
     *
     * @param boolean $boolean
     *
     * @return Profiler $profiler
     */
    public function startOn($boolean)
    {
        if ($boolean) {
            $this->start();
        }

        return $this;
    }

    /**
     * @return boolean $isRunning Whether the profiler is currently running
     */
    public function isRunning()
    {
        return $this->getProfilerAdapter()->isRunning();
    }

    /**
     * Stops profiling and persists and returns the Profile object
     *
     * @return Profile
     */
    public function stop()
    {
        // Create a new profile based upon the data of the adapter
        $profile = $this->getProfileFactory()->create(
            $this->getProfilerAdapter()->stop(),
            $this->getApplicationData(),
            $_SERVER // TODO: Don't want to use this directly, do something smarter
        );

        // Notify and persist the profile on the persistence service
        $this->getPersistenceService()->persist($profile);

        // Return the profile for further handling
        return $profile;
    }

    /**
     * Try and persist the profile on garbage collection
     */
    public function __destruct()
    {
        try {
            if($this->isRunning() === true) {
                $this->stop();
            }
        } catch(Exception $e) {
            // Exceptions can't be thrown in destructors
        }
    }
}
