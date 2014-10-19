<?php

/**
 * FilesystemInterface.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler;

use League\Flysystem\Filesystem as Flysystem;

/**
 * The current FilesystemInterface on the Flysystem improperly annotates the read() and write() methods.
 * This is due to an extension of the AdapterInterface which will be rewritten in the next major version.
 * @see https://github.com/thephpleague/flysystem/issues/287
 *
 * At the moment, we will override the methods on this interface so inspection will not break.
 * The Filesystem implementation also needs to be extended to adhere to the new interface
 *
 * @package Link0\Profiler
 */
final class Filesystem extends Flysystem implements FilesystemInterface
{
}