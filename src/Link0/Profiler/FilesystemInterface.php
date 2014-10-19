<?php

/**
 * FilesystemInterface.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface as FlysystemInterface;

/**
 * The current FilesystemInterface on the Flysystem improperly annotates the read() and write() methods.
 * This is due to an extension of the AdapterInterface which will be rewritten in the next major version.
 * @see https://github.com/thephpleague/flysystem/issues/287
 *
 * At the moment, we will override the methods on this interface so inspection will not break.
 *
 * @package Link0\Profiler
 */
interface FilesystemInterface extends FlysystemInterface
{
    /**
     * Read a file
     *
     * @param  string                $path path to file
     * @throws FileNotFoundException
     * @return string|false          file contents or FALSE when fails
     *                               to read existing file
     */
    public function read($path);

    /**
     * Write a file
     *
     * @param  string              $path     path to file
     * @param  string              $contents file contents
     * @param  mixed               $config
     * @throws FileExistsException
     * @return boolean             success boolean
     */
    public function write($path, $contents, $config = null);
}
