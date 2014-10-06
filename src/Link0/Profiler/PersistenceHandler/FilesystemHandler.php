<?php

/**
 * FilesystemHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use DirectoryIterator;
use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;

/**
 * FilesystemHandler implementation for PersistenceHandler
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class FilesystemHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var \string $directory
     */
    protected $directory;

    /**
     * @var \DirectoryIterator $directoryIterator
     */
    protected $directoryIterator;

    /**
     * @var string $fileSuffix The file suffix for files to be stored
     */
    protected $fileSuffix;

    /**
     * @param string $directory
     * @param string $fileSuffix
     */
    public function __construct($directory = '/tmp/link0-profiler', $fileSuffix = 'profile')
    {
        $this->ensureDirectoryExists($directory);
        $this->directory = $directory;
        $this->directoryIterator = new DirectoryIterator($directory);
        $this->fileSuffix = $fileSuffix;
    }

    /**
     * @param string $directory
     */
    protected function ensureDirectoryExists($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        $profileIdentifiers = array();

        foreach ($this->directoryIterator as $directory) {
            if ($directory->isDot()) {
                continue;
            } else {
                $profileIdentifiers[] = str_replace(".{$this->fileSuffix}", "",  $directory->getFilename());
            }
        }

        return $profileIdentifiers;
    }

    /**
     * @param  string $profileIdentifier
     * @return string $profileFilename
     */
    public function getFilename($profileIdentifier)
    {
        return $this->directoryIterator->getPath() . DIRECTORY_SEPARATOR . $profileIdentifier . '.' . $this->fileSuffix;
    }

    /**
     * @param  string       $identifier
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        $filename = $this->getFilename($identifier);

        if (file_exists($filename) && is_readable($filename)) {
            return unserialize(file_get_contents($filename));
        } else {
            return null;
        }
    }

    /**
     * @param  Profile                     $profile
     * @return PersistenceHandlerInterface
     */
    public function persist(Profile $profile)
    {
        $filename = $this->getFilename($profile->getIdentifier());
        file_put_contents($filename, serialize($profile));

        return $this;
    }

    public function emptyList()
    {
        foreach ($this->directoryIterator as $directory) {
            if ($directory->isDot()) {
                continue;
            } else {
                unlink($this->directory . DIRECTORY_SEPARATOR . $directory->getFilename());
            }
        }
    }
}
