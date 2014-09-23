<?php

/**
 * Xhprof.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Xhprof class implements the xhprof-extension
 *
 * @package Link0\Profiler
 */
final class Xhprof
{
    /**
     * @var int $flags Contains XHProf specific options
     */
    protected $flags;

    /**
     * @var array $options Contains XHProf specific options
     */
    protected $options;

    /**
     * @var PersistenceService $persistenceService
     */
    protected $persistenceService;

    /**
     * @param PersistenceHandlerInterface|null $persistenceHandler
     * @param int                              $flags
     * @param array                            $options
     */
    public function __construct(PersistenceHandlerInterface $persistenceHandler = null, $flags = 4 /* XHPROF_FLAGS_MEMORY */, $options = array())
    {
        $this->persistenceService = new PersistenceService($persistenceHandler);
        $this->flags = $flags;
        $this->options = $options;
    }

    /**
     * @return int $flags Contiains
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param int $flag
     * @return bool $hasFlag
     */
    public function hasFlag($flag)
    {
        return ($this->getFlags() & $flag) == $flag;
    }

    /**
     * @return array $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Starts the profiling
     *
     * @throws Exception
     */
    public function start()
    {
        $this->checkExtensionLoaded();
        xhprof_enable($this->getFlags(), $this->getOptions());
    }

    /**
     * Stops the profiling and triggers an event with the result data
     */
    public function stop()
    {
        $profile = Profile::fromData(xhprof_disable());
        $this->persistenceService->persist($profile);
        return $profile;
    }

    /**
     * @codeCoverageIgnore It's impossible to test all paths in this method, due to extension_loaded()
     */
    protected function checkExtensionLoaded()
    {
        if(!extension_loaded('xhprof')) {
            throw new Exception("Required extension 'xhprof' not loaded");
        }
    }
}