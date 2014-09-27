<?php

/**
 * ProfilerAdapter.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Abstract ProfilerAdapter to be implemented by PHP profiling extensions
 *
 * @package Link0\Profiler
 */
abstract class ProfilerAdapter implements ProfilerAdapterInterface
{
    /**
     * @var int $flags The configuration flags the adapter should be loaded with
     */
    protected $flags     = 0;

    /**
     * @var array $options The options the specific adapter can parse
     */
    protected $options   = array();

    /**
     * @var bool $isRunning Whether the ProfilerAdapter is currently profiling
     */
    protected $isRunning = false;

    /**
     * @param int   $flags   The configuration flags the adapter should be loaded with
     * @param array $options The options the specific adapter can parse
     */
    public function __construct($flags = 0, $options = array())
    {
        $this->flags = $flags;
        $this->options = $options;
    }

    /**
     * @return int $flags The configuration flags the adapter should be loaded with
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param  int     $flag    The flag you want to check
     * @return boolean $hasFlag Whether this flag is enabled
     */
    public function hasFlag($flag)
    {
        return (($this->getFlags() & $flag) == $flag);
    }

    /**
     * @return array $options Returns the options for this adapter
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Starts the profiling on this adapter
     *
     * @return ProfilerAdapter $this
     */
    public function start()
    {
        $this->isRunning = true;
        return $this;
    }

    /**
     * Stops the profiling on this adapter
     * @return array $data
     */
    public function stop()
    {
        $this->isRunning = false;
        return array();
    }

    /**
     * @return bool $isRunning Whether the adapter is currently profiling
     */
    public function isRunning()
    {
        return $this->isRunning;
    }

    /**
     * @return bool $isExtensionLoaded If the required extension for this adapter is loaded
     */
    public function isExtensionLoaded()
    {
        return extension_loaded($this->getExtensionName());
    }

    /**
     * @return string $extensionName The extensionName that should be loaded for this adapter
     */
    abstract public function getExtensionName();

    /**
     * @return string|false The default file output directory for the extension of this adapter
     */
    public function getFileOutputDirectory()
    {
        return false;
    }
}