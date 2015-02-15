<?php

/**
 * FilesystemHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use League\Flysystem\FileNotFoundException;
use Link0\Profiler\FilesystemInterface;
use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\ProfileInterface;

/**
 * FilesystemHandler implementation for PersistenceHandler
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class FilesystemHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var FilesystemInterface $filesystem
     */
    private $filesystem;

    /**
     * @var string $path The path where the profile files are stored
     */
    private $path;

    /**
     * @var string $extension The extension profile files have
     */
    private $extension;

    /**
     * @param FilesystemInterface $filesystem
     * @param string $path      OPTIONAL The path from the root given in the filesystem
     * @param string $extension OPTIONAL The extension of the profile files
     */
    public function __construct(FilesystemInterface $filesystem, $path = '/', $extension = 'profile')
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->path = $path;
        $this->extension = $extension;
    }

    /**
     * @return FilesystemInterface $filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string $extension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param  string $identifier The file identifier
     * @return string $fullPath The full path to the profile file
     */
    public function getFullPath($identifier)
    {
        return rtrim($this->getPath(), '/') . '/' . $identifier . '.' . $this->getExtension();
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        $identifiers = array();

        $fileList = $this->filesystem->listFiles($this->getPath());
        foreach ($fileList as $file) {
            $identifiers[] = $file['filename'];
        }
        return $identifiers;

    }

    /**
     * @param  string                $identifier
     * @return ProfileInterface|null $profile
     */
    public function retrieve($identifier)
    {
        try {
            $fullPath = $this->getFullPath($identifier);
            $content = $this->getFilesystem()->read($fullPath);

            if($content === false) {
                return null;
            }
        } catch(FileNotFoundException $fnfe) {
            return null;
        }

        return $this->getProfileFactory()->fromArray($this->getSerializer()->unserialize($content));
    }

    /**
     * @param  ProfileInterface $profile
     * @throws Exception
     * @return PersistenceHandlerInterface
     */
    public function persist(ProfileInterface $profile)
    {
        $serializer = $this->getSerializer();
        $fullPath = $this->getFullPath($profile->getIdentifier());

        if ($this->getFilesystem()->put($fullPath, $serializer->serialize($profile->toArray())) === false) {
            throw new Exception('Unable to persist Profile[identifier=' . $profile->getIdentifier() . ']');
        }

        return $this;
    }

    /**
     * @throws Exception
     * @return PersistenceHandlerInterface $this
     */
    public function emptyList()
    {
        foreach ($this->getList() as $item) {
            if ($this->getFilesystem()->delete($this->getFullPath($item)) === false) {
                throw new Exception('Unable to delete Profile[identifier=' . $item . ']');
            }
        }

        return $this;
    }
}
