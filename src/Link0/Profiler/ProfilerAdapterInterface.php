<?php

/**
 * ProfilerAdapterInterface.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

/**
 * Interface ProfilerAdapterInterface
 *
 * @package Link0\Profiler
 */
interface ProfilerAdapterInterface
{
    /**
     * @param int   $flags   Flags describing certain behaviour for the specific ProfilerAdapter implementation
     * @param array $options Options altering the behaviour of the specific ProfilerAdapter implementation
     */
    public function __construct($flags = 0, $options = array());

    /**
     * @return Profiler $this
     */
    public function start();

    /**
     * @return array $profile
     */
    public function stop();

    /**
     * @return bool $isRunning
     */
    public function isRunning();

    /**
     * @return int $flags
     */
    public function getFlags();

    /**
     * @param  int  $flag
     * @return bool $hasFlag
     */
    public function hasFlag($flag);

    /**
     * @return array $options
     */
    public function getOptions();

    /**
     * @return bool $isExtensionLoaded
     */
    public function isExtensionLoaded();

    /**
     * @return string $extensionName
     */
    public function getExtensionName();

    /**
     * @return string|false $fileOutputDirectory
     */
    public function getFileOutputDirectory();
}